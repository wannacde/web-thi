<!-- resources/views/teacher/dashboard.blade.php -->
@extends('layout.main')

@section('title', 'Bảng điều khiển Giáo viên')

@section('content')
    <h1>Bảng điều khiển Giáo viên</h1>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-value">{{ count($myExams) }}</div>
            <div class="stat-label">Bài thi của tôi</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $myQuestions }}</div>
            <div class="stat-label">Câu hỏi của tôi</div>
        </div>
    </div>
    
    <div class="my-exams-section">
        <div class="section-header">
            <h2>Bài thi của tôi</h2>
            <a href="{{ route('exams.create') }}" class="btn-primary">Tạo bài thi mới</a>
        </div>
        
        @if(count($myExams) > 0)
            <ul class="list-exams">
                @foreach($myExams as $exam)
                    <li class="exam-item">
                        <div class="exam-content">
                            <h3>{{ $exam->ten_bai_thi }}</h3>
                            <div class="exam-details">
                                <p><strong>Môn học:</strong> {{ $exam->monHoc->ten_mon_hoc }}</p>
                                <p><strong>Số câu hỏi:</strong> {{ $exam->tong_so_cau }}</p>
                                <p><strong>Thời gian:</strong> {{ $exam->thoi_gian }} phút</p>
                            </div>
                        </div>
                        <div class="exam-actions">
                            <a href="{{ route('exams.show', $exam->slug) }}" class="btn-primary">Chi tiết</a>
                            <a href="{{ route('exams.edit', $exam->slug) }}" class="btn-primary">Sửa</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Bạn chưa tạo bài thi nào.</p>
        @endif
    </div>
    
    <div class="dashboard-actions">
        <div class="action-card">
            <h2>Quản lý câu hỏi</h2>
            <p>Thêm, sửa, xóa câu hỏi</p>
            <a href="{{ route('questions.index') }}" class="btn-primary">Quản lý câu hỏi</a>
        </div>
        <div class="action-card">
            <h2>Quản lý bài thi</h2>
            <p>Thêm, sửa, xóa bài thi</p>
            <a href="{{ route('exams.index') }}" class="btn-primary">Quản lý bài thi</a>
        </div>
        <div class="action-card">
            <h2>Xem kết quả</h2>
            <p>Xem kết quả bài thi của học sinh</p>
            <a href="{{ route('results.index') }}" class="btn-primary">Xem kết quả</a>
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
    .dashboard-stats .stat-card {
        background: linear-gradient(90deg, #6a82fb 0%, #3490dc 100%);
        color: #fff;
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
    .btn-primary i {
        margin-right: 0.5rem;
    }
    .exam-item {
        background: #fff;
        border-left: 4px solid #3490dc;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        padding: 1rem 1.5rem;
        border-radius: 8px;
    }
</style>
@endsection
