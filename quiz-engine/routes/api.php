<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/quizzes', [QuizController::class, 'store']);
Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);