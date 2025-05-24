<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonHoc;
use App\Models\Chuong;
use App\Models\CauHoi;
use App\Models\BaiThi;
use App\Models\BaiThiCauHoi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RandomExamController extends Controller
{
    public function getChapterQuestionCount(Request $request)
{
    $monHocId = $request->input('mon_hoc_id');
    $chuongs = Chuong::where('ma_mon_hoc', $monHocId)->get();
    
    $result = [];
    foreach ($chuongs as $chuong) {
        $questionCount = CauHoi::where('ma_chuong', $chuong->ma_chuong)->count();
        $result[] = [
            'ma_chuong' => $chuong->ma_chuong,
            'ten_chuong' => $chuong->ten_chuong,
            'muc_do' => $chuong->muc_do,
            'so_cau_hoi' => $questionCount
        ];
    }
    
    return response()->json($result);
}

public function create(Request $request)
{
    $request->validate([
        'ten_bai_thi' => 'required|string|max:255',
        'ma_mon_hoc' => 'required|exists:MonHoc,ma_mon_hoc',
        'thoi_gian' => 'required|integer|min:1',
        'tong_so_cau' => 'required|integer|min:1',
        'chuong_so_luong' => 'required|array',
        'chuong_so_luong.*' => 'integer|min:0',
    ]);
    
    // Kiểm tra tổng số câu hỏi từ các chương có bằng tổng số câu đã nhập
    $tongSoCauTuChuong = array_sum($request->chuong_so_luong);
    if ($tongSoCauTuChuong != $request->tong_so_cau) {
        return back()->withErrors(['tong_so_cau' => 'Tổng số câu hỏi từ các chương phải bằng tổng số câu hỏi đã nhập'])->withInput();
    }
    
    // Kiểm tra số lượng câu hỏi có đủ không
    foreach ($request->chuong_so_luong as $maChuong => $soLuong) {
        if ($soLuong > 0) {
            $availableQuestions = CauHoi::where('ma_chuong', $maChuong)->count();
            if ($availableQuestions < $soLuong) {
                $chuong = Chuong::find($maChuong);
                return back()->withErrors([
                    'chuong_so_luong' => "Chương '{$chuong->ten_chuong}' chỉ có {$availableQuestions} câu hỏi, không đủ {$soLuong} câu hỏi yêu cầu. Vui lòng giảm số lượng hoặc tạo thêm câu hỏi."
                ])->withInput();
            }
        }
    }
    
    // Bắt đầu transaction
    DB::beginTransaction();
    
    try {
        // Tạo bài thi mới
        $baiThi = BaiThi::create([
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'ten_bai_thi' => $request->ten_bai_thi,
            'tong_so_cau' => $request->tong_so_cau,
            'thoi_gian' => $request->thoi_gian,
            'nguoi_tao' => Auth::id(),
        ]);
        
        // Lấy câu hỏi ngẫu nhiên từ các chương
        foreach ($request->chuong_so_luong as $maChuong => $soLuong) {
            if ($soLuong > 0) {
                $cauHois = CauHoi::where('ma_chuong', $maChuong)
                    ->inRandomOrder()
                    ->limit($soLuong)
                    ->get();
                
                // Thêm câu hỏi vào bài thi
                foreach ($cauHois as $cauHoi) {
                    BaiThiCauHoi::create([
                        'ma_bai_thi' => $baiThi->ma_bai_thi,
                        'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                    ]);
                }
            }
        }
        
        DB::commit();
        
        return redirect()->route('exam.detail', $baiThi->ma_bai_thi)
            ->with('success', 'Bài thi đã được tạo thành công với các câu hỏi ngẫu nhiên');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo bài thi: ' . $e->getMessage()])->withInput();
    }
}

public function generateQuestions(Request $request)
{
    $request->validate([
        'ma_mon_hoc' => 'required|exists:MonHoc,ma_mon_hoc',
        'ten_bai_thi' => 'required|string|max:255',
        'tong_so_cau' => 'required|integer|min:1',
        'thoi_gian' => 'required|integer|min:1',
        'chuong_so_luong' => 'required|array',
        'chuong_so_luong.*' => 'integer|min:0',
    ]);
    
    // Kiểm tra tổng số câu hỏi từ các chương có bằng tổng số câu đã nhập
    $tongSoCauTuChuong = array_sum($request->chuong_so_luong);
    if ($tongSoCauTuChuong != $request->tong_so_cau) {
        return response()->json([
            'success' => false,
            'message' => 'Tổng số câu hỏi từ các chương phải bằng tổng số câu hỏi đã nhập'
        ]);
    }
    
    // Kiểm tra số lượng câu hỏi có đủ không
    foreach ($request->chuong_so_luong as $maChuong => $soLuong) {
        if ($soLuong > 0) {
            $availableQuestions = CauHoi::where('ma_chuong', $maChuong)->count();
            if ($availableQuestions < $soLuong) {
                $chuong = Chuong::find($maChuong);
                return response()->json([
                    'success' => false,
                    'message' => "Chương '{$chuong->ten_chuong}' chỉ có {$availableQuestions} câu hỏi, không đủ {$soLuong} câu hỏi yêu cầu."
                ]);
            }
        }
    }
    
    $selectedQuestions = [];
    
    // Lấy câu hỏi ngẫu nhiên từ các chương
    foreach ($request->chuong_so_luong as $maChuong => $soLuong) {
        if ($soLuong > 0) {
            $cauHois = CauHoi::with(['dapAn', 'chuong'])
                ->where('ma_chuong', $maChuong)
                ->inRandomOrder()
                ->limit($soLuong)
                ->get();
            
            foreach ($cauHois as $cauHoi) {
                $selectedQuestions[] = $cauHoi;
            }
        }
    }
    
    return response()->json([
        'success' => true,
        'questions' => $selectedQuestions
    ]);
}

}