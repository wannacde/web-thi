<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Kiểm tra nếu đang chạy qua Expose hoặc proxy khác
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            // Khi sử dụng Expose
            $schema = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'https';
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
            
            // Đặt URL gốc cho tất cả các URL được tạo
            URL::forceRootUrl($schema . '://' . $host);
            
            // Đặt schema HTTPS nếu cần
            if ($schema == 'https') {
                URL::forceScheme('https');
            }
            
            // Đặt domain cho session cookie để hoạt động với domain Expose
            config(['session.domain' => null]);
            config(['session.secure' => $schema == 'https']);
        } else {
            // Khi không sử dụng Expose (local development)
            $appUrl = config('app.url');
            
            // Nếu APP_URL trong .env là HTTPS, đảm bảo tất cả URL cũng là HTTPS
            if (strpos($appUrl, 'https://') === 0) {
                URL::forceScheme('https');
            }
        }
    }
}
