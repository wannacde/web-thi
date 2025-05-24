@extends('layout.main')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Đổi mật khẩu</h1>
        
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.change') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                @error('current_password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Xác nhận mật khẩu mới</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary btn-block">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </button>
            </div>

            <div class="auth-links">
                <a href="{{ route('home') }}">Quay lại trang chủ</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 2rem;
    }
    .auth-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(99,102,241,0.1);
        width: 100%;
        max-width: 500px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .auth-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
    }
    .auth-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .auth-form .form-group {
        margin-bottom: 1.5rem;
    }
    .auth-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #4b5563;
    }
    .auth-form .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #c7d2fe;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .auth-form .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        outline: none;
    }
    .auth-form .is-invalid {
        border-color: #ef4444;
    }
    .auth-form .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .btn-block {
        width: 100%;
        justify-content: center;
    }
    .auth-links {
        margin-top: 1.5rem;
        text-align: center;
        font-size: 0.95rem;
    }
    .auth-links a {
        color: #6366f1;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .auth-links a:hover {
        color: #4f46e5;
        text-decoration: underline;
    }
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
</style>
@endsection