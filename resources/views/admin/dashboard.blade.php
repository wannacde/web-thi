<!-- resources/views/admin/dashboard.blade.php -->
@extends('layout.main')

@section('title', 'Bảng điều khiển Admin')

@section('content')
    <h1>Bảng điều khiển Admin</h1>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Người dùng</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalExams }}</div>
            <div class="stat-label">Bài thi</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalSubjects }}</div>
            <div class="stat-label">Môn học</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalQuestions }}</div>
            <div class="stat-label">Câu hỏi</div>
        </div>
    </div>
    
    <div class="dashboard-actions">
        <div class="action-card">
            <h2>Quản lý người dùng</h2>
            <p>Thêm, sửa, xóa người dùng</p>
            <a href="{{ route('admin.users.index') }}" class="btn-primary"><i class="fas fa-users"></i>Quản lý người dùng</a>
        </div>
        <div class="action-card">
            <h2>Quản lý môn học</h2>
            <p>Thêm, sửa, xóa môn học và chương</p>
            <a href="{{ route('subjects.index') }}" class="btn-primary"><i class="fas fa-book"></i>Quản lý môn học</a>
        </div>
        <div class="action-card">
            <h2>Quản lý câu hỏi</h2>
            <p>Thêm, sửa, xóa câu hỏi</p>
            <a href="{{ route('questions.index') }}" class="btn-primary"><i class="fas fa-question"></i>Quản lý câu hỏi</a>
        </div>
        <div class="action-card">
            <h2>Quản lý bài thi</h2>
            <p>Thêm, sửa, xóa bài thi</p>
            <a href="{{ route('exams.index') }}" class="btn-primary"><i class="fas fa-file-alt"></i>Quản lý bài thi</a>
        </div>
        <div class="action-card">
            <h2>Thống kê kết quả</h2>
            <p>Xem thống kê kết quả bài thi</p>
            <a href="{{ route('results.statistics') }}" class="btn-primary"><i class="fas fa-chart-line"></i>Xem thống kê</a>
        </div>
    </div>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
    }
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: linear-gradient(90deg, #6a82fb 0%, #3490dc 100%);
        color: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(52,144,220,0.12);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #fff;
    }
    .stat-label {
        font-size: 1.2rem;
        color: #e0eafc;
        margin-top: 0.5rem;
    }
    .dashboard-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .action-card {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .action-card h2 {
        margin-top: 0;
        margin-bottom: 0.5rem;
    }
    .action-card p {
        margin-bottom: 1.5rem;
        color: #666;
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
</style>
@endsection
