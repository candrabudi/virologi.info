<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AgentAiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CyberSecurityServiceController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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
Route::get('/fetch/homepage-products', [HomeController::class, 'homepageProducts']);
Route::get('/fetch/homepage-ebooks', [HomeController::class, 'homepageEbooks']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/api/products', [ProductController::class, 'apiIndex']);
Route::get('/api/products/types', [ProductController::class, 'apiTypes']);
Route::get('/api/products/domains', [ProductController::class, 'apiDomains']);
Route::get('/api/products/recent', [ProductController::class, 'apiRecent']);
Route::post('/api/products/{id}/click', [ProductController::class, 'apiTrackClick']);

Route::get('/ebooks', [EbookController::class, 'index']);
Route::get('/ebooks/{slug}', [EbookController::class, 'show']);
Route::get('/ebooks/{slug}/read', [EbookController::class, 'read']);

Route::get('/api/ebooks', [EbookController::class, 'apiIndex']);
Route::get('/api/ebooks/levels', [EbookController::class, 'apiLevels']);
Route::get('/api/ebooks/topics', [EbookController::class, 'apiTopics']);
Route::get('/api/ebooks/recent', [EbookController::class, 'apiRecent']);

Route::get('/services', [CyberSecurityServiceController::class, 'index']);
Route::get('/services/{slug}', [CyberSecurityServiceController::class, 'show']);

Route::get('/api/services', [CyberSecurityServiceController::class, 'apiIndex']);
Route::get('/api/services/categories', [CyberSecurityServiceController::class, 'apiCategories']);
Route::get('/api/services/recent', [CyberSecurityServiceController::class, 'apiRecent']);

Route::get('/about-us', [AboutUsController::class, 'index']);
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'detail']);

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
