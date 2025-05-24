<?php
namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Đăng ký người dùng
    public function register(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:NguoiDung',
            'mat_khau' => 'required|min:6|confirmed',
            'ho_ten' => 'required',
            'email' => 'required|email|unique:NguoiDung',
            'vai_tro' => 'required|in:quan_tri,giao_vien,hoc_sinh',
        ]);

        $user = NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'mat_khau' => Hash::make($request->mat_khau),
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'vai_tro' => $request->vai_tro,
        ]);

        // Đăng nhập người dùng sau khi đăng ký
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    // Đăng nhập người dùng
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required',
            'mat_khau' => 'required',
        ]);

        // Tìm người dùng theo tên đăng nhập
        $user = NguoiDung::where('ten_dang_nhap', $request->ten_dang_nhap)->first();
        
        // Kiểm tra người dùng tồn tại và mật khẩu đúng
        if ($user && Hash::check($request->mat_khau, $user->mat_khau)) {
            Auth::login($user);
            $request->session()->regenerate();
            
            // Chuyển hướng dựa trên vai trò
            if ($user->vai_tro == 'quan_tri') {
                return redirect()->route('admin.dashboard');
            } else {
                // Cả giáo viên và học sinh đều chuyển hướng về trang chủ
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'ten_dang_nhap' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->except('mat_khau'));
    }

    // Đăng xuất người dùng
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}

