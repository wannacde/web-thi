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
                <a href="{{ route('exams.index') }}" class="btn-primary">Xem danh sách bài thi</a>
                
                @if(Auth::user()->vai_tro != 'hoc_sinh')
                    <a href="{{ route('exams.create') }}" class="btn-primary" style="margin-left: 10px;">Tạo bài thi mới</a>
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
                                <a href="{{ route('exams.show', $baiThi->slug) }}" class="btn-primary">Chi tiết</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Không có bài thi nào.</p>
            @endif
        </div>
        
    @endauth
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
    .home-header h1:before {
        content: '\f015';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .home-content {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(52,144,220,0.12);
        padding: 2rem 3rem;
        text-align: center;
        margin-top: 2rem;
    }
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