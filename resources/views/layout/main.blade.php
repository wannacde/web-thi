<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - Online Exam</title>
    <style>
        /* Reset & basic */
        body, html {
            margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f5f7fa;
            color: #333;
        }
        a {
            color: #3490dc; text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        nav {
            background-color: #2d3748; color: white; padding: 1rem 2rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        nav a {
            color: white; margin-left: 1rem; font-weight: 600;
        }
        nav a:first-child {
            margin-left: 0;
            font-size: 1.25rem; font-weight: bold;
        }
        .container {
            max-width: 960px; margin: 2rem auto; background: white;
            padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
        }
        h1, h2 {
            color: #2d3748; margin-bottom: 1rem;
        }
        form input, form select, form button, form textarea {
            display: block; width: 100%; padding: 0.5rem; margin-bottom: 1rem; border-radius: 4px;
            border: 1px solid #ccc; box-sizing: border-box;
            font-size: 1rem;
        }
        form button {
            background-color: #3490dc; color: white; border: none;
            cursor: pointer; font-weight: 600; transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #2779bd;
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
            background-color: #3490dc; color: white; border: none;
            padding: 0.5rem 1rem; border-radius: 4px;
            cursor: pointer; font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #2779bd;
        }
        .alert {
            padding: 0.75rem 1rem; background-color: #48bb78; color: white;
            border-radius: 4px; margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ url('/') }}">OnlineExam</a>
        <div>
            <a href="{{ route('exam.list') }}">Bài thi</a>
            <a href="{{ route('login.view') }}">Đăng nhập</a>
            <a href="{{ route('register.view') }}">Đăng ký</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>

