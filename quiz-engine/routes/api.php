<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuizSessionController;
use App\Http\Controllers\QuizController;

Route::post('/quizzes', [QuizController::class, 'store']);
Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);

// Create Session
Route::post('/quizzes/{quiz}/sessions', [QuizSessionController::class, 'start']);
// Finish Session
Route::post('/sessions/{session}/finish', [QuizSessionController::class, 'finish']);

Route::post('/sessions/{session}/submitAnswers', [QuizSessionController::class, 'submitAnswers']);

Route::get('/sessions/{session}/results', [QuizSessionController::class, 'results']);