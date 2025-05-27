<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to use
 
Hướng dẫn cài đặt với Laragon
Clone repository vào thư mục www của Laragon:

cd C:\laragon\www
git clone <repository-url> webthitructuyen

Copy
Cài đặt dependencies:

cd webthitructuyen
composer install

Copy
Tạo file .env:

Copy file .env.example thành .env

Hoặc tạo file .env mới với nội dung cơ bản:

APP_NAME="Hệ thống thi trực tuyến"
APP_ENV=local
APP_KEY=base64:F+KayPpOOq2ewSKGlWlxu5f6ZBX3HqRqK7LJOON5AXM=
APP_DEBUG=true
APP_URL=http://webthitructuyen.test

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Hệ thống thi trực tuyến"


Copy
Tạo database:

Mở Laragon và click vào "Database" để mở HeidiSQL

Tạo database mới tên là "web" (hoặc tên khác nếu đã cấu hình trong .env)

Chạy migration và seeder:

php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link

Copy
Khởi động website:

Khởi động Laragon (nếu chưa chạy)

Click vào "Start All"

Truy cập website tại: http://webthitructuyen.test

Xóa cache nếu cần:

php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

Copy
Lưu ý quan trọng cho người dùng Laragon:
Virtual Host: Laragon tự động tạo virtual host dựa trên tên thư mục. Nếu thư mục là "webthitructuyen", URL sẽ là http://webthitructuyen.test

Cấu hình .env:

Đảm bảo APP_URL trỏ đến virtual host: APP_URL=http://webthitructuyen.test

Không cần các cấu hình Expose như SESSION_DOMAIN và SANCTUM_STATEFUL_DOMAINS

Cấu hình MySQL:

Laragon thường sử dụng MySQL với username "root" và password trống

Đảm bảo cấu hình database trong .env phù hợp

Quyền thư mục:

Laragon thường không gặp vấn đề về quyền thư mục, nhưng nếu có lỗi, đảm bảo thư mục storage và bootstrap/cache có quyền ghi

SSL (nếu cần):

Laragon có thể tạo SSL tự ký bằng cách click chuột phải vào icon Laragon > Apache > SSL > Enable

Sau đó URL sẽ là https://webthitructuyen.test


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

