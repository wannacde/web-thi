@extends('layout.main')

@section('title', 'Thêm môn học mới')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1><i class="fas fa-plus-circle"></i> Thêm môn học mới</h1>
        </div>
        <div class="page-actions">
            <a href="{{ route('subjects.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('subjects.store') }}">
            @csrf
            
            <div class="form-card">
                <div class="form-card-header">
                    <h2><i class="fas fa-info-circle"></i> Thông tin môn học</h2>
                </div>
                
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="ten_mon_hoc">Tên môn học <span class="required">*</span></label>
                        <input type="text" id="ten_mon_hoc" name="ten_mon_hoc" value="{{ old('ten_mon_hoc') }}" required
                            placeholder="Nhập tên môn học" class="form-control">
                        <small class="form-text">Tên môn học sẽ được hiển thị trong danh sách môn học và bài thi</small>
                    </div>

                    <div class="form-group">
                        <label for="mo_ta">Mô tả</label>
                        <textarea id="mo_ta" name="mo_ta" rows="4" class="form-control"
                            placeholder="Nhập mô tả về môn học (không bắt buộc)">{{ old('mo_ta') }}</textarea>
                        <small class="form-text">Mô tả ngắn gọn về nội dung và mục tiêu của môn học</small>
                    </div>
                </div>
                
                <div class="form-card-footer">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Lưu môn học
                    </button>
                    <a href="{{ route('subjects.index') }}" class="btn-light">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
    }
    
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-title h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .page-title h1 i {
        color: #3490dc;
    }
    
    /* Alert */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .alert-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    .alert i {
        font-size: 1.2rem;
        margin-top: 0.2rem;
    }
    .alert ul {
        margin: 0;
        padding-left: 1rem;
    }
    
    /* Form Container */
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    /* Form Card */
    .form-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
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
    .form-card-footer {
        padding: 1.5rem;
        border-top: 1px solid #e2e8f0;
        background-color: #f8fafc;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    /* Form Controls */
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group:last-child {
        margin-bottom: 0;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #4b5563;
    }
    .required {
        color: #ef4444;
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
    .form-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    /* Buttons */
    .btn-primary, .btn-secondary, .btn-light {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #3490dc;
        color: white;
    }
    .btn-primary:hover {
        background-color: #2779bd;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(52, 144, 220, 0.2);
    }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(107, 114, 128, 0.2);
    }
    .btn-light {
        background-color: #f3f4f6;
        color: #4b5563;
    }
    .btn-light:hover {
        background-color: #e5e7eb;
        transform: translateY(-2px);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .form-card-footer {
            flex-direction: column;
        }
        .btn-primary, .btn-light {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection