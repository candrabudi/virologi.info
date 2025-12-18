<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AgentAiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('/home/latest-articles', [HomeController::class, 'latestArticles']);
Route::get('/about-us', [AboutUsController::class, 'index']);
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'detail']);
Route::get('/products', function () {
    return view('products.index');
});

Route::get('/threat-map', function () {
    return view('raven');
});

Route::prefix('ai-agent')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('ai.login');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('ai.register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('ai.register.store');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp']);
});

Route::middleware('auth')->prefix('ai-agent')->group(function () {
    Route::get('/chat', [AgentAiController::class, 'index'])
     ->name('ai.chat');

    Route::get('/sessions', [AgentAiController::class, 'sessions']);
    Route::post('/sessions', [AgentAiController::class, 'createSession']);
    Route::get('/sessions/{token}', [AgentAiController::class, 'messages']);
    Route::post('/sessions/{token}/message', [AgentAiController::class, 'storeMessage']);
    Route::get('/chat/{token}', [AgentAiController::class, 'index'])
    ->name('ai.chat');

    Route::post('/sessions/{token}/pin', [AgentAiController::class, 'togglePin']);
    Route::delete('/sessions/{token}', [AgentAiController::class, 'deleteSession']);
});
