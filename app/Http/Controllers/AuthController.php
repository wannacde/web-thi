<?php
namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Đăng ký người dùng
    public function register(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:NguoiDung',
            'mat_khau' => 'required|min:6',
            'ho_ten' => 'required',
            'email' => 'required|email|unique:NguoiDung',
            'vai_tro' => 'required|in:quan_tri,giao_vien,hoc_sinh',
        ]);

        NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'mat_khau' => Hash::make($request->mat_khau),
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'vai_tro' => $request->vai_tro,
        ]);

        return response()->json(['message' => 'Đăng ký thành công!'], 201);
    }

    // Đăng nhập người dùng
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required',
            'mat_khau' => 'required',
        ]);

        if (Auth::attempt(['ten_dang_nhap' => $request->ten_dang_nhap, 'mat_khau' => $request->mat_khau])) {
            $user = Auth::user();
            return response()->json(['message' => 'Đăng nhập thành công!', 'user' => $user], 200);
        }

        return response()->json(['message' => 'Tên đăng nhập hoặc mật khẩu không đúng!'], 401);
    }

    // Đăng xuất người dùng
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Đăng xuất thành công!'], 200);
    }
}

