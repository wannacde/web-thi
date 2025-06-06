<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - Hệ thống thi trực tuyến</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            color: #333;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        nav {
            background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
            color: white;
            padding: 1.2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 24px rgba(52,144,220,0.10);
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            border-radius: 0;
            min-height: 64px;
            box-sizing: border-box;
        }
        nav > div {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1.2rem;
            overflow-x: auto;
        }
        nav a, nav form {
            margin-left: 0 !important;
        }
        nav form {
            display: flex;
            align-items: center;
            margin-left: 1.2rem;
            margin-bottom: 0;
        }
        nav button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            font-size: 1rem;
            padding: 0;
            margin-left: 1.2rem;
        }
        nav form button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            font-size: 1rem;
            padding: 0;
            margin: 0;
            display: inline-flex;
            align-items: center;
        }
        nav a {
            color: white;
            margin-left: 1.2rem;
            font-weight: 600;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        nav a:hover {
            color: #ffd200;
            text-decoration: none;
        }
        nav a.logo {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 1px;
            margin-left: 0;
            display: flex;
            align-items: center;
        }
        nav a.logo i {
            font-size: 2rem;
            margin-right: 0.5rem;
            color: #ffd200;
        }
        body {
            padding-top: 90px;
        }
        .container {
            max-width: 1200px;
            margin: 2.5rem auto;
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(52,144,220,0.10);
        }
        h1, h2 {
            color: #2d3748;
            margin-bottom: 1.2rem;
            font-weight: 700;
        }
        form input, form select, form button, form textarea {
            display: block; width: 100%; padding: 0.5rem; margin-bottom: 1rem; border-radius: 4px;
            border: 1px solid #ccc; box-sizing: border-box;
            font-size: 1rem;
        }
        form button {
            background: linear-gradient(90deg, #6a82fb 0%, #3490dc 100%); color: white; border: none;
            cursor: pointer; font-weight: 600; transition: background 0.3s, box-shadow 0.3s;
            padding: 0.5rem 1rem; border-radius: 4px;
        }
        form button:hover {
            background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
            box-shadow: 0 4px 16px rgba(52,144,220,0.18);
        }
        ul.list-exams, ul.list-questions {
            list-style-type: none; padding: 0;
        }
        ul.list-exams li, ul.list-questions li {
            padding: 12px; border-bottom: 1px solid #eee;
        }
        ul.list-exams li:last-child, ul.list-questions li:last-child {
            border-bottom: none;
        }
        .error {
            color: red; margin-bottom: 1rem;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            background: linear-gradient(90deg, #6a82fb 0%, #3490dc 100%);
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(52,144,220,0.12);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .btn-primary i {
            margin-right: 0.5rem;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
            box-shadow: 0 4px 16px rgba(52,144,220,0.18);
            transform: translateY(-2px);
        }
        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            font-size: 1.05rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        /* Footer Styles */
        footer {
            background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
            color: white;
            padding: 3rem 2rem 1rem;
            margin-top: 3rem;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        @media (max-width: 700px) {
            .container {
                padding: 1rem;
                margin: 1rem;
            }
            nav {
                flex-direction: column;
                padding: 1rem;
            }
        }
        @media (max-width: 900px) {
            nav > div {
                flex-direction: column;
                align-items: flex-end;
                gap: 0.5rem;
            }
            nav {
                padding: 1.2rem 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav>
        <a href="{{ url('/') }}" class="logo"><i class="fa fa-graduation-cap"></i>Hệ thống thi trực tuyến</a>
        <div>
            
            @guest
                <a href="{{ route('login.view') }}"><i class="fa fa-sign-in-alt"></i>Đăng nhập</a>
                <a href="{{ route('register.view') }}"><i class="fa fa-user-plus"></i>Đăng ký</a>
                <a href="{{ route('password.request') }}"><i class="fa fa-key"></i>Quên mật khẩu</a>
            @else
                @if(Auth::user()->vai_tro == 'quan_tri')
                    <a href="{{ route('admin.dashboard') }}"><i class="fa fa-user-shield"></i>Dashboard</a>
                    <a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i>Quản lý người dùng</a>
                @elseif(Auth::user()->vai_tro == 'giao_vien')
                    <a href="{{ route('teacher.dashboard') }}"><i class="fa fa-chalkboard-teacher"></i>Dashboard</a>
                @endif
                <a href="{{ route('exams.index') }}"><i class="fa fa-list-alt"></i>Bài thi</a>
                <a href="{{ route('results.index') }}"><i class="fa fa-poll"></i>Kết quả</a>
                <a href="{{ route('password.change.form') }}"><i class="fa fa-key"></i>Đổi mật khẩu</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i>Đăng xuất ({{ Auth::user()->ho_ten }})</button>
                </form>
            @endguest
        </div>
    </nav>
    
    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <footer>
        <div class="footer-container">
            @yield('footer')
        </div>
    </footer>
    
    @yield('scripts')
</body>
</html>