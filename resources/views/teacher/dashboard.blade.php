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
            <a href="{{ route('exam.create') }}" class="btn-primary">Tạo bài thi mới</a>
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
                            <a href="{{ route('exam.detail', $exam->ma_bai_thi) }}" class="btn-primary">Chi tiết</a>
                            <a href="{{ route('exam.edit', $exam->ma_bai_thi) }}" class="btn-primary">Sửa</a>
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
            <a href="{{ route('exam.list') }}" class="btn-primary">Quản lý bài thi</a>
        </div>
        <div class="action-card">
            <h2>Xem kết quả</h2>
            <p>Xem kết quả bài thi của học sinh</p>
            <a href="{{ route('results.index') }}" class="btn-primary">Xem kết quả</a>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #3490dc;
    }
    .stat-label {
        font-size: 1.2rem;
        color: #666;
        margin-top: 0.5rem;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .my-exams-section {
        margin-bottom: 2rem;
    }
    .list-exams {
        list-style-type: none;
        padding: 0;
    }
    .exam-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .exam-actions {
        display: flex;
        gap: 0.5rem;
    }
    .exam-details {
        margin-top: 0.5rem;
    }
    .exam-details p {
        margin: 0.25rem 0;
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
</style>
@endsection
