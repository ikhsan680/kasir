<?php

use App\Http\Controllers\CashierQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CashierQuizController::class, 'home'])->name('cashier.home');
Route::get('/start', [CashierQuizController::class, 'start'])->name('cashier.start');
Route::get('/quiz', [CashierQuizController::class, 'quiz'])->name('cashier.quiz');
Route::get('/next-question', [CashierQuizController::class, 'nextQuestion'])->name('cashier.next');
Route::post('/submit', [CashierQuizController::class, 'submit'])->name('cashier.submit');
Route::get('/result', [CashierQuizController::class, 'result'])->name('cashier.result');
Route::get('/reset', [CashierQuizController::class, 'reset'])->name('cashier.reset');
Route::post('/finish-exam', [CashierQuizController::class, 'finishExam'])->name('cashier.finishExam');
Route::get('/restart-same-quiz', [CashierQuizController::class, 'restartSameQuiz'])->name('cashier.restartSameQuiz');
