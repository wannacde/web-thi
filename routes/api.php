<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Các route API được định nghĩa ở đây, có middleware 'api' mặc định.
|
*/

// Auth - đăng ký, đăng nhập, đăng xuất
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Exam - bài thi
Route::post('/exams', [ExamController::class, 'createExam']);
Route::get('/exams', [ExamController::class, 'getExams']);
Route::get('/exams/{id}', [ExamController::class, 'getExam']);
Route::put('/exams/{id}', [ExamController::class, 'updateExam']);
Route::delete('/exams/{id}', [ExamController::class, 'deleteExam']);

// Question - câu hỏi
Route::post('/questions', [QuestionController::class, 'createQuestion']);
Route::get('/questions', [QuestionController::class, 'getQuestions']);
Route::get('/questions/{id}', [QuestionController::class, 'getQuestion']);
Route::put('/questions/{id}', [QuestionController::class, 'updateQuestion']);
Route::delete('/questions/{id}', [QuestionController::class, 'deleteQuestion']);

// Result - kết quả bài thi
Route::post('/results', [ResultController::class, 'saveResult']);
Route::get('/results/user/{ma_nguoi_dung}', [ResultController::class, 'getResults']);
Route::get('/results/{id}', [ResultController::class, 'getResult']);
