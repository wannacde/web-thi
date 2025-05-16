@extends('layout.main')

@section('title', 'Đăng ký')

@section('content')
    <h1>Đăng ký tài khoản</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="ho_ten">Họ và tên</label>
            <input type="text" id="ho_ten" name="ho_ten" value="{{ old('ho_ten') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="ten_dang_nhap">Tên đăng nhập</label>
            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" required>
        </div>

        <div class="form-group">
            <label for="mat_khau">Mật khẩu</label>
            <input type="password" id="mat_khau" name="mat_khau" required>
        </div>

        <div class="form-group">
            <label for="mat_khau_confirmation">Xác nhận mật khẩu</label>
            <input type="password" id="mat_khau_confirmation" name="mat_khau_confirmation" required>
        </div>

        <div class="form-group">
            <label for="vai_tro">Vai trò</label>
            <select id="vai_tro" name="vai_tro" required>
                <option value="hoc_sinh" selected>Học sinh</option>
                <option value="giao_vien">Giáo viên</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit">Đăng ký</button>
        </div>

        <p>Đã có tài khoản? <a href="{{ route('login.view') }}">Đăng nhập ngay</a></p>
    </form>
@endsection