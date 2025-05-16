@extends('layout.main')

@section('title', 'Trang chủ')

@section('content')
    <div class="welcome-section">
        <h1>Chào mừng đến với Hệ thống thi trực tuyến</h1>
        <p>Nền tảng thi trực tuyến hiện đại và tiện lợi</p>
        
        @guest
            <div class="action-buttons">
                <a href="{{ route('login.view') }}" class="btn-primary">Đăng nhập</a>
                <a href="{{ route('register.view') }}" class="btn-primary" style="margin-left: 10px;">Đăng ký</a>
            </div>
        @else
            <div class="action-buttons">
                <a href="{{ route('exam.list') }}" class="btn-primary">Xem danh sách bài thi</a>
                
                @if(Auth::user()->vai_tro != 'hoc_sinh')
                    <a href="{{ route('exam.create') }}" class="btn-primary" style="margin-left: 10px;">Tạo bài thi mới</a>
                @endif
            </div>
        @endguest
    </div>

    @auth
        <div class="recent-exams">
            <h2>Bài thi gần đây</h2>
            
            @if(isset($baiThis) && count($baiThis) > 0)
                <ul class="list-exams">
                    @foreach($baiThis as $baiThi)
                        <li>
                            <div class="exam-item">
                                <h3>{{ $baiThi->ten_bai_thi }}</h3>
                                <p>Môn học: {{ $baiThi->monHoc->ten_mon_hoc }}</p>
                                <p>Số câu hỏi: {{ $baiThi->tong_so_cau }} | Thời gian: {{ $baiThi->thoi_gian }} phút</p>
                                <a href="{{ route('exam.detail', $baiThi->ma_bai_thi) }}" class="btn-primary">Chi tiết</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Không có bài thi nào.</p>
            @endif
        </div>
        
        @if(isset($monHocs) && count($monHocs) > 0)
            <div class="subjects-section">
                <h2>Môn học</h2>
                <div class="subjects-list">
                    @foreach($monHocs as $monHoc)
                        <div class="subject-item">
                            <h3>{{ $monHoc->ten_mon_hoc }}</h3>
                            <p>{{ $monHoc->mo_ta ?? 'Không có mô tả' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth
    
    <div class="features-section">
        <h2>Tính năng nổi bật</h2>
        <div class="features-list">
            <div class="feature-item">
                <h3>Đa dạng bài thi</h3>
                <p>Hỗ trợ nhiều loại câu hỏi và bài thi từ nhiều môn học khác nhau.</p>
            </div>
            <div class="feature-item">
                <h3>Dễ dàng sử dụng</h3>
                <p>Giao diện thân thiện, dễ sử dụng cho cả giáo viên và học sinh.</p>
            </div>
            <div class="feature-item">
                <h3>Kết quả ngay lập tức</h3>
                <p>Nhận kết quả và đánh giá ngay sau khi hoàn thành bài thi.</p>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .welcome-section {
        text-align: center;
        margin-bottom: 2rem;
    }
    .action-buttons {
        margin-top: 1.5rem;
    }
    .features-list, .subjects-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }
    .feature-item, .subject-item {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .recent-exams, .subjects-section, .features-section {
        margin-top: 2rem;
    }
    .exam-item {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
</style>
@endsection