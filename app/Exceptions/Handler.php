<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['error' => 'Chưa đăng nhập'], 401);
            }
            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json(['error' => 'Không có quyền truy cập'], 403);
            }
        }
        return parent::render($request, $exception);
    }
}
