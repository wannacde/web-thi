<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: white;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            color:rgb(255, 255, 255) !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #718096;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Đặt lại mật khẩu</h1>
    </div>
    
    <div class="content">
        <p>Xin chào,</p>
        
        <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
        
        <p>Vui lòng nhấp vào nút bên dưới để đặt lại mật khẩu của bạn:</p>
        
        <p style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">Đặt lại mật khẩu</a>
        </p>
        
        <p>Liên kết đặt lại mật khẩu này sẽ hết hạn sau 60 phút.</p>
        
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này.</p>
        
        <p>Trân trọng,<br>Hệ thống thi trực tuyến</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Hệ thống thi trực tuyến. Tất cả các quyền được bảo lưu.</p>
    </div>
</body>
</html>
