<?php
namespace App\Http\Controllers;

use App\Models\BaiThi;
use App\Models\CauHoi;
use App\Models\MonHoc;
use App\Models\KetQuaBaiThi;
use App\Models\TraLoiNguoiDung;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Hiển thị danh sách bài thi
     */
    public function index()
    {
        $exams = BaiThi::with('monHoc')->get();
        return view('exams.index', compact('exams'));
    }
    
    /**
     * Hiển thị form tạo bài thi mới
     */
    public function create()
    {
        // Kiểm tra quyền tạo bài thi
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('exam.list')->with('error', 'Bạn không có quyền tạo bài thi!');
        }
        
        $monHocs = MonHoc::all();
        return view('exams.create', compact('monHocs'));
    }

    /**
     * Lưu bài thi mới
     */
    public function store(Request $request)
    {
        // Kiểm tra quyền tạo bài thi
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('exam.list')->with('error', 'Bạn không có quyền tạo bài thi!');
        }
        
        $request->validate([
            'ma_mon_hoc' => 'required|exists:MonHoc,ma_mon_hoc',
            'ten_bai_thi' => 'required|string|max:255',
            'tong_so_cau' => 'required|integer|min:1',
            'thoi_gian' => 'required|integer|min:1',
        ]);

        $exam = BaiThi::create([
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'ten_bai_thi' => $request->ten_bai_thi,
            'tong_so_cau' => $request->tong_so_cau,
            'thoi_gian' => $request->thoi_gian,
            'nguoi_tao' => Auth::id(),
        ]);

        // Nếu có câu hỏi được chọn
        if ($request->has('cau_hoi')) {
            $exam->cauHoi()->attach($request->cau_hoi);
        }

        return redirect()->route('exam.list')->with('success', 'Bài thi đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết bài thi
     */
    public function showDetail($id)
    {
        $exam = BaiThi::with(['monHoc', 'cauHoi.dapAn'])->findOrFail($id);
        
        // Thêm thông tin người tạo nếu có
        if ($exam->nguoi_tao) {
            $exam->nguoiTao = NguoiDung::find($exam->nguoi_tao);
        }
        
        return view('exams.detail', compact('exam'));
    }

    /**
     * Hiển thị form làm bài thi
     */
    public function takeExam($id)
    {
        $exam = BaiThi::with(['monHoc', 'cauHoi.dapAn'])->findOrFail($id);
        
        // Kiểm tra xem người dùng đã làm bài thi này chưa
        $userId = Auth::id();
        $hasAttempted = KetQuaBaiThi::where('ma_bai_thi', $id)
            ->where('ma_nguoi_dung', $userId)
            ->exists();
            
        if ($hasAttempted) {
            return redirect()->route('exam.list')->with('info', 'Bạn đã làm bài thi này rồi!');
        }
        
        return view('exams.take', compact('exam'));
    }

    /**
     * Xử lý nộp bài thi
     */
    public function submitExam(Request $request, $id)
    {
        $exam = BaiThi::with(['cauHoi.dapAn'])->findOrFail($id);
        $userId = Auth::id();
        
        // Kiểm tra xem người dùng đã làm bài thi này chưa
        $hasAttempted = KetQuaBaiThi::where('ma_bai_thi', $id)
            ->where('ma_nguoi_dung', $userId)
            ->exists();
            
        if ($hasAttempted) {
            return redirect()->route('exam.list')->with('info', 'Bạn đã làm bài thi này rồi!');
        }
        
        // Tính điểm
        $score = 0;
        $totalQuestions = $exam->cauHoi->count();
        
        // Tạo kết quả bài thi
        $result = KetQuaBaiThi::create([
            'ma_bai_thi' => $id,
            'ma_nguoi_dung' => $userId,
            'diem' => 0, // Sẽ cập nhật sau
        ]);
        
        // Xử lý từng câu trả lời
        foreach ($exam->cauHoi as $question) {
            $questionId = $question->ma_cau_hoi;
            $userAnswer = $request->input('question.' . $questionId);
            $correctAnswer = $question->dapAn->where('dung_sai', 1)->first();
            
            // Kiểm tra đáp án
            $isCorrect = false;
            if ($question->loai_cau_hoi == 'trac_nghiem') {
                $isCorrect = ($userAnswer == $correctAnswer->ma_dap_an);
            } else { // dien_khuyet
                $isCorrect = (strtolower(trim($userAnswer)) == strtolower(trim($correctAnswer->noi_dung)));
            }
            
            if ($isCorrect) {
                $score++;
            }
            
            // Lưu câu trả lời của người dùng
            TraLoiNguoiDung::create([
                'ma_ket_qua' => $result->ma_ket_qua,
                'ma_cau_hoi' => $questionId,
                'dap_an_chon' => $userAnswer,
                'dung_sai' => $isCorrect,
            ]);
        }
        
        // Cập nhật điểm
        $finalScore = ($totalQuestions > 0) ? ($score / $totalQuestions) * 10 : 0;
        $result->update(['diem' => $finalScore]);
        
        return redirect()->route('results.show', $result->ma_ket_qua)->with('success', 'Bạn đã hoàn thành bài thi!');
    }

    /**
     * Hiển thị form chỉnh sửa bài thi
     */
    public function edit($id)
    {
        $exam = BaiThi::findOrFail($id);
        
        // Kiểm tra quyền chỉnh sửa
        if (Auth::user()->vai_tro == 'hoc_sinh' || 
            (Auth::user()->vai_tro == 'giao_vien' && $exam->nguoi_tao != Auth::id())) {
            return redirect()->route('exam.list')->with('error', 'Bạn không có quyền chỉnh sửa bài thi này!');
        }
        
        $monHocs = MonHoc::all();
        $selectedQuestions = $exam->cauHoi->pluck('ma_cau_hoi')->toArray();
        
        return view('exams.edit', compact('exam', 'monHocs', 'selectedQuestions'));
    }

    /**
     * Cập nhật bài thi
     */
    public function update(Request $request, $id)
    {
        $exam = BaiThi::findOrFail($id);
        
        // Kiểm tra quyền chỉnh sửa
        if (Auth::user()->vai_tro == 'hoc_sinh' || 
            (Auth::user()->vai_tro == 'giao_vien' && $exam->nguoi_tao != Auth::id())) {
            return redirect()->route('exam.list')->with('error', 'Bạn không có quyền chỉnh sửa bài thi này!');
        }
        
        $request->validate([
            'ma_mon_hoc' => 'required|exists:MonHoc,ma_mon_hoc',
            'ten_bai_thi' => 'required|string|max:255',
            'tong_so_cau' => 'required|integer|min:1',
            'thoi_gian' => 'required|integer|min:1',
        ]);

        $exam->update([
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'ten_bai_thi' => $request->ten_bai_thi,
            'tong_so_cau' => $request->tong_so_cau,
            'thoi_gian' => $request->thoi_gian,
        ]);

        // Cập nhật câu hỏi
        if ($request->has('cau_hoi')) {
            $exam->cauHoi()->sync($request->cau_hoi);
        } else {
            $exam->cauHoi()->detach();
        }

        return redirect()->route('exam.list')->with('success', 'Bài thi đã được cập nhật thành công!');
    }

    /**
     * Xóa bài thi
     */
    public function destroy($id)
    {
        $exam = BaiThi::findOrFail($id);
        
        // Kiểm tra quyền xóa
        if (Auth::user()->vai_tro == 'hoc_sinh' || 
            (Auth::user()->vai_tro == 'giao_vien' && $exam->nguoi_tao != Auth::id())) {
            return redirect()->route('exam.list')->with('error', 'Bạn không có quyền xóa bài thi này!');
        }
        
        // Kiểm tra xem bài thi đã có kết quả chưa
        $hasResults = KetQuaBaiThi::where('ma_bai_thi', $id)->exists();
        if ($hasResults) {
            return redirect()->route('exam.list')->with('error', 'Không thể xóa bài thi đã có người làm!');
        }
        
        // Xóa liên kết với câu hỏi
        $exam->cauHoi()->detach();
        
        // Xóa bài thi
        $exam->delete();
        
        return redirect()->route('exam.list')->with('success', 'Bài thi đã được xóa thành công!');
    }
}

