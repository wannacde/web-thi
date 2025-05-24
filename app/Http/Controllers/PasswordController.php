<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use App\Mail\ResetPasswordMail;

class PasswordController extends Controller
{
    // Hiển thị form quên mật khẩu
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Xử lý yêu cầu đặt lại mật khẩu
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = NguoiDung::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Không tìm thấy email này trong hệ thống.']);
        }

        // Tạo token reset password
        $token = Str::random(60);
        
        // Kiểm tra xem bảng password_resets có tồn tại không
        try {
            // Lưu token vào database
            \DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );
        } catch (\Exception $e) {
            // Tạo bảng password_resets nếu chưa tồn tại
            \Schema::create('password_resets', function ($table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
            
            // Thử lại việc lưu token
            \DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );
        }

        // Gửi email với link reset password
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ], false));

        // Gửi email
        Mail::to($request->email)->send(new ResetPasswordMail($resetUrl));

        return back()->with('status', 'Chúng tôi đã gửi email chứa liên kết đặt lại mật khẩu!');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Xử lý đặt lại mật khẩu
public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    // Lấy token từ database
    $tokenRecord = \DB::table('password_resets')
        ->where('email', $request->email)
        ->first();

    if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
        return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn.']);
    }

    // Kiểm tra thời gian tạo token (60 phút)
    if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
        return back()->withErrors(['email' => 'Token đã hết hạn.']);
    }

    // Tìm user và cập nhật mật khẩu
    $user = NguoiDung::where('email', $request->email)->first();
    
    if (!$user) {
        return back()->withErrors(['email' => 'Không tìm thấy người dùng với email này.']);
    }

    $user->mat_khau = Hash::make($request->password);
    $user->save();

    // Xóa token đã sử dụng
    \DB::table('password_resets')->where('email', $request->email)->delete();

    return redirect()->route('login.view')->with('status', 'Mật khẩu đã được đặt lại!');
}


    // Hiển thị form đổi mật khẩu (khi đã đăng nhập)
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Xử lý đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->mat_khau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // Cập nhật mật khẩu mới
        $user->mat_khau = Hash::make($request->password);
        $user->save();

        return redirect()->route('home')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}