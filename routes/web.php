<?php
// routes/web.php
use App\Http\Controllers\RandomExamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordController;
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
    
    // Route cho quên mật khẩu
    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Route cho đổi mật khẩu
    Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('password.change');
    
    // Dashboard routes
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('role:quan_tri');
    Route::get('/teacher/dashboard', [HomeController::class, 'teacherDashboard'])->name('teacher.dashboard')->middleware('role:giao_vien');
    
   
    // Route cho quản lý bài thi (chỉ admin và giáo viên)
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::get('/bai-thi/tao-moi', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/bai-thi', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/bai-thi/{slug}/sua', [ExamController::class, 'edit'])->name('exams.edit');
        Route::put('/bai-thi/{slug}', [ExamController::class, 'update'])->name('exams.update');
        Route::delete('/bai-thi/{slug}', [ExamController::class, 'destroy'])->name('exams.destroy');
    });
    
    // Route cho bài thi
    Route::get('/bai-thi', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/bai-thi/{slug}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('/bai-thi/{slug}/lam-bai', [ExamController::class, 'takeExam'])->name('exams.take')->middleware('role:hoc_sinh');
    Route::post('/bai-thi/{slug}/nop-bai', [ExamController::class, 'submitExam'])->name('exams.submit')->middleware('role:hoc_sinh');
    
    // Route cho câu hỏi (chỉ admin và giáo viên)
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::get('/chuong/{maMonHoc}', [QuestionController::class, 'getChuongsByMonHoc']);
        Route::get('/questions/by-chuong/{maChuong}', [QuestionController::class, 'getQuestionsByChuong']);
        Route::get('/questions/search', [QuestionController::class, 'search'])->name('questions.search');
        Route::resource('questions', QuestionController::class);
    });
    
    // Route cho môn học
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::resource('subjects', SubjectController::class)->parameters([
            'subjects' => 'slug'
        ]);
        Route::get('/subjects/{slug}/chapters', [SubjectController::class, 'manageChapters'])->name('subjects.chapters');
        Route::post('/subjects/{slug}/chapters', [SubjectController::class, 'storeChapter'])->name('subjects.chapters.store');
        Route::put('/subjects/{slug}/chapters/{chapterId}', [SubjectController::class, 'updateChapter'])->name('subjects.chapters.update');
        Route::delete('/subjects/{slug}/chapters/{chapterId}', [SubjectController::class, 'destroyChapter'])->name('subjects.chapters.destroy');
    });
    
    // Route cho kết quả
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/{id}', [ResultController::class, 'show'])->name('results.show');
    Route::get('/results/{id}/pdf', [ResultController::class, 'exportPdf'])->name('results.pdf');
    
    // Route cho thống kê (chỉ admin và giáo viên)
    Route::middleware('role:quan_tri,giao_vien')->group(function () {
        Route::get('/statistics', [ResultController::class, 'statistics'])->name('results.statistics');
    });

    // Route cho bài thi ngẫu nhiên
    Route::get('/random-exam/chapter-question-count', [RandomExamController::class, 'getChapterQuestionCount'])->name('random-exam.chapter-question-count');
    Route::post('/random-exam/create', [RandomExamController::class, 'create'])->name('random-exam.create');
    Route::post('/random-exam/generate-questions', [RandomExamController::class, 'generateQuestions'])->name('random-exam.generate-questions');
});