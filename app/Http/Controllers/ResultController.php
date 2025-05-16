<?php

namespace App\Http\Controllers;

use App\Models\KetQuaBaiThi;
use App\Models\TraLoiNguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    /**
     * Hiển thị danh sách kết quả bài thi
     */
    public function index()
    {
        $user = Auth::user();
        
        // Nếu là học sinh, chỉ hiển thị kết quả của họ
        if ($user->vai_tro == 'hoc_sinh') {
            $results = KetQuaBaiThi::where('ma_nguoi_dung', $user->ma_nguoi_dung)
                ->with(['baiThi.monHoc', 'nguoiDung'])
                ->orderBy('ngay_nop', 'desc')
                ->paginate(10);
        } 
        // Nếu là giáo viên, hiển thị kết quả của bài thi do họ tạo
        elseif ($user->vai_tro == 'giao_vien') {
            $results = KetQuaBaiThi::whereHas('baiThi', function($query) use ($user) {
                    $query->where('nguoi_tao', $user->ma_nguoi_dung);
                })
                ->with(['baiThi.monHoc', 'nguoiDung'])
                ->orderBy('ngay_nop', 'desc')
                ->paginate(10);
        } 
        // Nếu là admin, hiển thị tất cả kết quả
        else {
            $results = KetQuaBaiThi::with(['baiThi.monHoc', 'nguoiDung'])
                ->orderBy('ngay_nop', 'desc')
                ->paginate(10);
        }
        
        return view('results.index', compact('results'));
    }

    /**
     * Hiển thị chi tiết kết quả bài thi
     */
    public function show($id)
    {
        $result = KetQuaBaiThi::with([
            'baiThi.monHoc', 
            'nguoiDung',
            'traLoi.cauHoi.dapAn'
        ])->findOrFail($id);
        
        $user = Auth::user();
        
        // Kiểm tra quyền xem kết quả
        if ($user->vai_tro == 'hoc_sinh' && $result->ma_nguoi_dung != $user->ma_nguoi_dung) {
            return redirect()->route('results.index')->with('error', 'Bạn không có quyền xem kết quả này!');
        }
        
        if ($user->vai_tro == 'giao_vien' && $result->baiThi->nguoi_tao != $user->ma_nguoi_dung) {
            return redirect()->route('results.index')->with('error', 'Bạn không có quyền xem kết quả này!');
        }
        
        // Tính toán thống kê
        $totalQuestions = $result->traLoi->count();
        $correctAnswers = $result->traLoi->where('dung_sai', 1)->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;
        $percentCorrect = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        
        return view('results.show', compact('result', 'totalQuestions', 'correctAnswers', 'wrongAnswers', 'percentCorrect'));
    }

    /**
     * Xuất kết quả bài thi dưới dạng PDF
     */
    public function exportPdf($id)
    {
        $result = KetQuaBaiThi::with([
            'baiThi.monHoc', 
            'nguoiDung',
            'traLoi.cauHoi.dapAn'
        ])->findOrFail($id);
        
        $user = Auth::user();
        
        // Kiểm tra quyền xuất kết quả
        if ($user->vai_tro == 'hoc_sinh' && $result->ma_nguoi_dung != $user->ma_nguoi_dung) {
            return redirect()->route('results.index')->with('error', 'Bạn không có quyền xuất kết quả này!');
        }
        
        if ($user->vai_tro == 'giao_vien' && $result->baiThi->nguoi_tao != $user->ma_nguoi_dung) {
            return redirect()->route('results.index')->with('error', 'Bạn không có quyền xuất kết quả này!');
        }
        
        // Tính toán thống kê
        $totalQuestions = $result->traLoi->count();
        $correctAnswers = $result->traLoi->where('dung_sai', 1)->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;
        $percentCorrect = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        
        $pdf = Pdf::loadView('results.pdf', compact('result', 'totalQuestions', 'correctAnswers', 'wrongAnswers', 'percentCorrect'));
        
        return $pdf->download('ket-qua-bai-thi-' . $result->ma_ket_qua . '.pdf');
    }
    
    /**
     * Hiển thị thống kê kết quả bài thi
     */
    public function statistics()
    {
        // Chỉ admin và giáo viên mới có quyền xem thống kê
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $user = Auth::user();
        
        // Nếu là giáo viên, chỉ thống kê bài thi của họ
        if ($user->vai_tro == 'giao_vien') {
            $examStats = \DB::table('BaiThi')
                ->select('BaiThi.ma_bai_thi', 'BaiThi.ten_bai_thi', 
                    \DB::raw('COUNT(KetQuaBaiThi.ma_ket_qua) as total_attempts'),
                    \DB::raw('AVG(KetQuaBaiThi.diem) as average_score'),
                    \DB::raw('MAX(KetQuaBaiThi.diem) as highest_score'),
                    \DB::raw('MIN(KetQuaBaiThi.diem) as lowest_score'))
                ->leftJoin('KetQuaBaiThi', 'BaiThi.ma_bai_thi', '=', 'KetQuaBaiThi.ma_bai_thi')
                ->where('BaiThi.nguoi_tao', $user->ma_nguoi_dung)
                ->groupBy('BaiThi.ma_bai_thi', 'BaiThi.ten_bai_thi')
                ->get();
        } 
        // Nếu là admin, thống kê tất cả bài thi
        else {
            $examStats = \DB::table('BaiThi')
                ->select('BaiThi.ma_bai_thi', 'BaiThi.ten_bai_thi', 
                    \DB::raw('COUNT(KetQuaBaiThi.ma_ket_qua) as total_attempts'),
                    \DB::raw('AVG(KetQuaBaiThi.diem) as average_score'),
                    \DB::raw('MAX(KetQuaBaiThi.diem) as highest_score'),
                    \DB::raw('MIN(KetQuaBaiThi.diem) as lowest_score'))
                ->leftJoin('KetQuaBaiThi', 'BaiThi.ma_bai_thi', '=', 'KetQuaBaiThi.ma_bai_thi')
                ->groupBy('BaiThi.ma_bai_thi', 'BaiThi.ten_bai_thi')
                ->get();
        }
        
        return view('results.statistics', compact('examStats'));
    }
}