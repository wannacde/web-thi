<!-- resources/views/auth/register.blade.php -->
@extends('layouts.main')

@section('title', 'Đăng ký')

@section('content')
<h1>Đăng ký tài khoản</h1>
<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Họ và tên</label>
        <input type="text" name="name" id="name" required />
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required />
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" required />
    </div>
    <button type="submit" class="btn-primary">Đăng ký</button>
</form>
@endsection