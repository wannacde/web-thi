@extends('layout.main')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="page-header">
        <h1>Thêm người dùng mới</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-user-plus"></i> Thông tin người dùng</h2>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ten_dang_nhap">Tên đăng nhập <span class="required">*</span></label>
                            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" class="form-control" value="{{ old('ten_dang_nhap') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ho_ten">Họ tên <span class="required">*</span></label>
                            <input type="text" id="ho_ten" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vai_tro">Vai trò <span class="required">*</span></label>
                            <select id="vai_tro" name="vai_tro" class="form-control" required>
                                <option value="">-- Chọn vai trò --</option>
                                <option value="quan_tri" {{ old('vai_tro') == 'quan_tri' ? 'selected' : '' }}>Quản trị viên</option>
                                <option value="giao_vien" {{ old('vai_tro') == 'giao_vien' ? 'selected' : '' }}>Giáo viên</option>
                                <option value="hoc_sinh" {{ old('vai_tro') == 'hoc_sinh' ? 'selected' : '' }}>Học sinh</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mat_khau">Mật khẩu <span class="required">*</span></label>
                            <input type="password" id="mat_khau" name="mat_khau" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mat_khau_confirmation">Xác nhận mật khẩu <span class="required">*</span></label>
                            <input type="password" id="mat_khau_confirmation" name="mat_khau_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Lưu</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .page-header {
        margin-bottom: 2rem;
    }
    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #3730a3;
    }
    .form-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(99,102,241,0.1);
    }
    .form-card-header {
        background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
        color: white;
        padding: 1.2rem 1.5rem;
    }
    .form-card-header h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .form-card-body {
        padding: 2rem 1.5rem;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -0.75rem;
        margin-left: -0.75rem;
    }
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #4b5563;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
        border-color: #3490dc;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.25);
    }
    .required {
        color: #ef4444;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
    }
    .btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        box-shadow: 0 4px 16px rgba(99,102,241,0.15);
        transform: translateY(-2px) scale(1.03);
    }
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }
        .col-md-6 {
            max-width: 100%;
            flex: 0 0 100%;
        }
    }
</style>
@endsection