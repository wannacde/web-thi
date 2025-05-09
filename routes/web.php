<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;

// Route cho đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route cho đăng ký
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.view');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Route cho bài thi
Route::get('/exams/{id}', [ExamController::class, 'detailView'])->name('exam.detail');
Route::post('/exams/{id}/submit', [ExamController::class, 'submitExam'])->name('exam.submit');

