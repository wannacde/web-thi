@extends('layout.main')

@section('title', 'Trang chủ')

@section('content')
    <!-- Hero Banner Section -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1><i class="fas fa-graduation-cap"></i> Hệ thống thi trực tuyến</h1>
            <p class="hero-subtitle">Nền tảng học tập và kiểm tra kiến thức hiện đại, tiện lợi và hiệu quả</p>
            
            @guest
                <div class="hero-buttons">
                    <a href="{{ route('login.view') }}" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                    <a href="{{ route('register.view') }}" class="btn-primary"><i class="fas fa-user-plus"></i> Đăng ký</a>
                </div>
            @else
                <div class="hero-buttons">
                    <a href="{{ route('exams.index') }}" class="btn-primary"><i class="fas fa-list-alt"></i> Xem danh sách bài thi</a>
                    
                    @if(Auth::user()->vai_tro != 'hoc_sinh')
                        <a href="{{ route('exams.create') }}" class="btn-primary"><i class="fas fa-plus-circle"></i> Tạo bài thi mới</a>
                    @endif
                </div>
            @endguest
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <h2 class="section-title"><i class="fas fa-star"></i> Tính năng nổi bật</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-clock"></i></div>
                <h3>Tiết kiệm thời gian</h3>
                <p>Tạo và tham gia bài thi mọi lúc, mọi nơi với giao diện thân thiện</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Thống kê chi tiết</h3>
                <p>Theo dõi kết quả học tập và tiến độ qua các biểu đồ trực quan</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Bảo mật cao</h3>
                <p>Bảo vệ thông tin cá nhân và kết quả bài thi của người dùng</p>
            </div>
        </div>
    </div>

    @auth
        <!-- Recent Exams Section -->
        <div class="recent-exams-section">
            <h2 class="section-title"><i class="fas fa-history"></i> Bài thi gần đây</h2>
            
            @if(isset($baiThis) && count($baiThis) > 0)
                <div class="exams-grid">
                    @foreach($baiThis as $baiThi)
                        <div class="exam-card">
                            <div class="exam-header">
                                <h3>{{ $baiThi->ten_bai_thi }}</h3>
                                <span class="exam-subject">{{ $baiThi->monHoc->ten_mon_hoc }}</span>
                            </div>
                            <div class="exam-info">
                                <div class="info-item">
                                    <i class="fas fa-question-circle"></i>
                                    <span>{{ $baiThi->tong_so_cau }} câu hỏi</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $baiThi->thoi_gian }} phút</span>
                                </div>
                            </div>
                            <a href="{{ route('exams.show', $baiThi->slug) }}" class="btn-primary"><i class="fas fa-eye"></i> Chi tiết</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-exams">
                    <i class="fas fa-info-circle"></i>
                    <p>Không có bài thi nào gần đây.</p>
                </div>
            @endif
        </div>
    @endauth

    <!-- How It Works Section -->
    <div class="how-it-works-section">
        <h2 class="section-title"><i class="fas fa-question-circle"></i> Cách thức hoạt động</h2>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Đăng ký tài khoản</h3>
                    <p>Tạo tài khoản học sinh hoặc giáo viên để bắt đầu</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Chọn bài thi</h3>
                    <p>Tìm và chọn bài thi phù hợp với môn học của bạn</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Làm bài và nhận kết quả</h3>
                    <p>Hoàn thành bài thi và nhận kết quả ngay lập tức</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="footer-content">
        <div class="footer-section">
            <h3>Về chúng tôi</h3>
            <p>Hệ thống thi trực tuyến cung cấp nền tảng học tập và kiểm tra kiến thức hiện đại, tiện lợi và hiệu quả cho học sinh và giáo viên.</p>
        </div>
        <div class="footer-section">
            <h3>Liên kết nhanh</h3>
            <ul>
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('exams.index') }}">Bài thi</a></li>
                @auth
                    <li><a href="{{ route('results.index') }}">Kết quả</a></li>
                @else
                    <li><a href="{{ route('login.view') }}">Đăng nhập</a></li>
                    <li><a href="{{ route('register.view') }}">Đăng ký</a></li>
                @endauth
            </ul>
        </div>
        <div class="footer-section">
            <h3>Liên hệ</h3>
            <p><i class="fas fa-envelope"></i> Email: contact@thitructuyen.com</p>
            <p><i class="fas fa-phone"></i> Điện thoại: 0123 456 789</p>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Hệ thống thi trực tuyến. Tất cả các quyền được bảo lưu.</p>
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
        background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
        color: #333;
    }
    
    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(135deg, #6a82fb 0%, #3490dc 100%);
        border-radius: 16px;
        padding: 3rem 2rem;
        margin-bottom: 2.5rem;
        text-align: center;
        color: white;
        box-shadow: 0 10px 30px rgba(52,144,220,0.2);
    }
    .hero-content h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }
    .hero-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    .hero-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    
    /* Buttons */
    .btn-primary, .btn-secondary {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        background-color: #fff;
        color: #1a56db;
    }
    .btn-primary:hover, .btn-secondary:hover {
        background-color: #f0f4f8;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Section Titles */
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    
    /* Features Section */
    .features-section {
        margin-bottom: 3rem;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .feature-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .feature-icon {
        font-size: 2.5rem;
        color: #3490dc;
        margin-bottom: 1rem;
    }
    .feature-card h3 {
        font-size: 1.3rem;
        margin-bottom: 0.8rem;
        color: #2d3748;
    }
    .feature-card p {
        color: #6b7280;
    }
    
    /* Recent Exams Section */
    .recent-exams-section {
        margin-bottom: 3rem;
    }
    .exams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .exam-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .exam-header {
        background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
        color: white;
        padding: 1.2rem;
    }
    .exam-header h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.2rem;
    }
    .exam-subject {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .exam-info {
        padding: 1.2rem;
        display: flex;
        justify-content: space-between;
    }
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4a5568; /* Màu đậm hơn để dễ đọc */
        font-weight: 500;
    }
    .exam-card .btn-primary {
        margin: 0 1.2rem 1.2rem 1.2rem;
        width: calc(100% - 2.4rem);
        justify-content: center;
        background-color: #3490dc; /* Màu nền xanh */
        color: white; /* Màu chữ trắng */
    }
    .exam-card .btn-primary:hover {
        background-color: #2779bd;
        color: white;
    }
    .no-exams {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        color: #6b7280;
    }
    .no-exams i {
        font-size: 2.5rem;
        color: #3490dc;
        margin-bottom: 1rem;
    }
    
    /* How It Works Section */
    .how-it-works-section {
        margin-bottom: 3rem;
    }
    .steps-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    .step {
        flex: 1;
        min-width: 250px;
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .step-number {
        background: #3490dc;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .step-content h3 {
        margin-top: 0;
        margin-bottom: 0.5rem;
        color: #2d3748;
    }
    .step-content p {
        margin: 0;
        color: #6b7280;
    }
    
    /* Footer Styles */
    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        padding: 3rem 0;
    }
    .footer-section h3 {
        color: white;
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .footer-section p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 0.8rem;
    }
    .footer-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer-section ul li {
        margin-bottom: 0.5rem;
    }
    .footer-section ul li a {
        color: white;
        text-decoration: none;
        transition: color 0.2s;
    }
    .footer-section ul li a:hover {
        color: #ffd200; /* Màu vàng khi hover */
        text-decoration: underline;
    }
    .social-icons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    .social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .social-icon:hover {
        background: white;
        color: #3490dc;
        transform: translateY(-3px);
    }
    .footer-bottom {
        text-align: center;
        padding: 1.5rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2rem;
        }
        .hero-subtitle {
            font-size: 1rem;
        }
        .hero-buttons {
            flex-direction: column;
            gap: 0.8rem;
        }
        .steps-container {
            flex-direction: column;
        }
    }
</style>
@endsection