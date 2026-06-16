<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuizController;

Route::post('/quizzes', [QuizController::class, 'store']);
Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);