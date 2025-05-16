<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// Route cho trang chính
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route cho xác thực
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.view');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('role:quan_tri');
    Route::get('/teacher/dashboard', [HomeController::class, 'teacherDashboard'])->name('teacher.dashboard')->middleware('role:giao_vien');
    
    // QUAN TRỌNG: Đặt route create trước route detail để tránh xung đột
    // Route cho quản lý bài thi (chỉ admin và giáo viên)
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exam.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('exam.store');
        Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])->name('exam.edit');
        Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exam.update');
        Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exam.destroy');
    });
    
    // Route cho bài thi
    Route::get('/exams', [ExamController::class, 'index'])->name('exam.list');
    Route::get('/exams/{id}', [ExamController::class, 'showDetail'])->name('exam.detail');
    Route::get('/exams/{id}/take', [ExamController::class, 'takeExam'])->name('exam.take')->middleware('role:hoc_sinh');
    Route::post('/exams/{id}/submit', [ExamController::class, 'submitExam'])->name('exam.submit')->middleware('role:hoc_sinh');
    
    // Route cho câu hỏi (chỉ admin và giáo viên)
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::resource('questions', QuestionController::class);
        Route::get('/chuong/{maMonHoc}', [QuestionController::class, 'getChuongsByMonHoc']);
        Route::get('/questions/by-chuong/{maChuong}', [QuestionController::class, 'getQuestionsByChuong']);
    });
    
    // Route cho môn học
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::resource('subjects', SubjectController::class);
        Route::get('/subjects/{id}/chapters', [SubjectController::class, 'manageChapters'])->name('subjects.chapters');
        Route::post('/subjects/{id}/chapters', [SubjectController::class, 'storeChapter'])->name('subjects.chapters.store');
        Route::put('/subjects/{id}/chapters/{chapterId}', [SubjectController::class, 'updateChapter'])->name('subjects.chapters.update');
        Route::delete('/subjects/{id}/chapters/{chapterId}', [SubjectController::class, 'destroyChapter'])->name('subjects.chapters.destroy');
    });
    
    // Route cho kết quả
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/{id}', [ResultController::class, 'show'])->name('results.show');
    Route::get('/results/{id}/pdf', [ResultController::class, 'exportPdf'])->name('results.pdf');
    
    // Route cho thống kê (chỉ admin và giáo viên)
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::get('/statistics', [ResultController::class, 'statistics'])->name('results.statistics');
    });
});
