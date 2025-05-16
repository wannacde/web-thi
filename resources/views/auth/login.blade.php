@extends('layout.main')

@section('title', 'Đăng nhập')

@section('content')
    <h1>Đăng nhập</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="ten_dang_nhap">Tên đăng nhập</label>
            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="mat_khau">Mật khẩu</label>
            <input type="password" id="mat_khau" name="mat_khau" required>
        </div>

        <div class="form-group">
            <button type="submit">Đăng nhập</button>
        </div>

        <p>Chưa có tài khoản? <a href="{{ route('register.view') }}">Đăng ký ngay</a></p>
    </form>
@endsection