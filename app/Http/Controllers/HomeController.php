<?php

namespace App\Http\Controllers;

use App\Models\BaiThi;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
{
    $monHocs = MonHoc::all();
    $baiThis = BaiThi::with('monHoc')->orderBy('ngay_tao', 'desc')->take(5)->get();
    
    return view('home', compact('monHocs', 'baiThis'));
}
    
    /**
     * Hiển thị dashboard cho admin
     */
    public function adminDashboard()
    {
        // Kiểm tra quyền admin
        if (Auth::user()->vai_tro !== 'quan_tri') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $totalUsers = \App\Models\NguoiDung::count();
        $totalExams = BaiThi::count();
        $totalSubjects = MonHoc::count();
        $totalQuestions = \App\Models\CauHoi::count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalExams', 'totalSubjects', 'totalQuestions'));
    }
    
    /**
     * Hiển thị dashboard cho giáo viên
     */
    public function teacherDashboard()
    {
        // Kiểm tra quyền giáo viên
        if (Auth::user()->vai_tro !== 'giao_vien') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $userId = Auth::id();
        $myExams = BaiThi::where('nguoi_tao', $userId)->with('monHoc')->get();
        $myQuestions = \App\Models\CauHoi::where('nguoi_tao', $userId)->count();
        
        return view('teacher.dashboard', compact('myExams', 'myQuestions'));
    }
}