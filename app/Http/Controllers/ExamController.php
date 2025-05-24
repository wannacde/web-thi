<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaiThi;
use App\Models\MonHoc;
use App\Models\CauHoi;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $exams = BaiThi::with('monHoc')->get();
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $subjects = MonHoc::all();
        return view('exams.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_bai_thi' => 'required|string|max:255',
            'ma_mon_hoc' => 'required|exists:monhoc,ma_mon_hoc',
            'tong_so_cau' => 'required|integer|min:1',
            'thoi_gian' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
        ]);

        $exam = BaiThi::create([
            'ten_bai_thi' => $request->ten_bai_thi,
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'tong_so_cau' => $request->tong_so_cau,
            'thoi_gian' => $request->thoi_gian,
            'nguoi_tao' => Auth::id(),
            'ngay_tao' => now(),
        ]);

        // Thêm các câu hỏi vào bài thi
        foreach ($request->questions as $questionId) {
            $exam->cauHoi()->attach($questionId);
        }

        return redirect()->route('exams.index')->with('success', 'Bài thi đã được tạo thành công.');
    }

    public function show($slug)
    {
        $exam = BaiThi::where('slug', $slug)->with(['monHoc', 'cauHoi.dapAn'])->firstOrFail();
        return view('exams.detail', compact('exam'));
    }

    public function edit($slug)
    {
        $exam = BaiThi::where('slug', $slug)->with(['monHoc', 'cauHoi'])->firstOrFail();
        $subjects = MonHoc::all();
        // Lấy danh sách ID các câu hỏi đã chọn cho bài thi
        $selectedQuestions = $exam->cauHoi->pluck('ma_cau_hoi')->toArray();
        return view('exams.edit', compact('exam', 'subjects', 'selectedQuestions'));
    }

    public function update(Request $request, $slug)
    {
        $exam = BaiThi::where('slug', $slug)->firstOrFail();

        $request->validate([
            'ten_bai_thi' => 'required|string|max:255',
            'ma_mon_hoc' => 'required|exists:monhoc,ma_mon_hoc',
            'tong_so_cau' => 'required|integer|min:1',
            'thoi_gian' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
        ]);

        $exam->update([
            'ten_bai_thi' => $request->ten_bai_thi,
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'tong_so_cau' => $request->tong_so_cau,
            'thoi_gian' => $request->thoi_gian,
        ]);

        // Cập nhật các câu hỏi trong bài thi
        $exam->cauHoi()->sync($request->questions);

        return redirect()->route('exams.index')->with('success', 'Bài thi đã được cập nhật thành công.');
    }

    public function destroy($slug)
    {
        $exam = BaiThi::where('slug', $slug)->firstOrFail();
        
        // Xóa các liên kết với câu hỏi
        $exam->cauHoi()->detach();
        
        // Xóa bài thi
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Bài thi đã được xóa thành công.');
    }

    public function takeExam($slug)
    {
        $exam = BaiThi::where('slug', $slug)
            ->with(['monHoc', 'cauHoi.dapAn'])
            ->firstOrFail();
        return view('exams.take', compact('exam'));
    }

    public function submitExam(Request $request, $slug)
    {
        $exam = BaiThi::where('slug', $slug)->with(['cauHoi.dapAn'])->firstOrFail();
        $user = Auth::user();

        // Kiểm tra học sinh đã làm bài chưa
        $existingResult = \App\Models\KetQuaBaiThi::where('ma_bai_thi', $exam->ma_bai_thi)
            ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->first();
        if ($existingResult) {
            return redirect()->route('results.show', $existingResult->ma_ket_qua)
                ->with('info', 'Bạn đã nộp bài thi này.');
        }

        $answers = $request->input('question', []);
        $totalQuestions = $exam->cauHoi->count();
        $correct = 0;
        $traLoiArr = [];

        foreach ($exam->cauHoi as $cauHoi) {
            $userAnswer = $answers[$cauHoi->ma_cau_hoi] ?? null;
            $isCorrect = false;
            $correctAnswer = null;

            if ($cauHoi->loai_cau_hoi == 'trac_nghiem') {
                foreach ($cauHoi->dapAn as $dapAn) {
                    if ($dapAn->dung_sai && $dapAn->ma_dap_an == $userAnswer) {
                        $isCorrect = true;
                        $correctAnswer = $dapAn->ma_dap_an;
                        break;
                    }
                    if ($dapAn->dung_sai) {
                        $correctAnswer = $dapAn->ma_dap_an;
                    }
                }
            } else { // điền khuyết
                foreach ($cauHoi->dapAn as $dapAn) {
                    if (mb_strtolower(trim($userAnswer)) === mb_strtolower(trim($dapAn->noi_dung))) {
                        $isCorrect = true;
                        $correctAnswer = $dapAn->noi_dung;
                        break;
                    }
                    if ($dapAn->dung_sai) {
                        $correctAnswer = $dapAn->noi_dung;
                    }
                }
            }

            if ($isCorrect) $correct++;

            $traLoiArr[] = [
                'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                'dap_an_chon' => $userAnswer,
                'dung_sai' => $isCorrect,
            ];
        }

        $score = $totalQuestions > 0 ? round($correct * 10 / $totalQuestions, 2) : 0;
        $result = \App\Models\KetQuaBaiThi::create([
            'ma_bai_thi' => $exam->ma_bai_thi,
            'ma_nguoi_dung' => $user->ma_nguoi_dung,
            'diem' => $score,
            'ngay_nop' => now(),
        ]);

        foreach ($traLoiArr as $tl) {
            $tl['ma_ket_qua'] = $result->ma_ket_qua;
            \App\Models\TraLoiNguoiDung::create($tl);
        }

        return redirect()->route('results.show', $result->ma_ket_qua)
            ->with('success', 'Nộp bài thành công!');
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'ma_mon_hoc' => 'required|exists:monhoc,ma_mon_hoc',
            'tong_so_cau' => 'required|integer|min:1',
            'chuong_so_luong' => 'required|array',
        ]);

        $questions = [];
        $totalQuestions = 0;

        foreach ($request->chuong_so_luong as $chuongId => $count) {
            if ($count > 0) {
                $chuongQuestions = CauHoi::where('ma_chuong', $chuongId)
                    ->inRandomOrder()
                    ->limit($count)
                    ->with(['chuong', 'dapAn'])
                    ->get();
                
                $questions = array_merge($questions, $chuongQuestions->toArray());
                $totalQuestions += $chuongQuestions->count();
            }
        }

        if ($totalQuestions != $request->tong_so_cau) {
            return response()->json([
                'success' => false,
                'message' => 'Không đủ câu hỏi để tạo bài thi.'
            ]);
        }

        return response()->json([
            'success' => true,
            'questions' => $questions
        ]);
    }

    public function list()
    {
        $exams = BaiThi::with('monHoc')->where('trang_thai', 1)->get();
        return view('exams.list', compact('exams'));
    }
}