<?php

use App\Models\User;
use App\Models\Swipe;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Livewire\Swiper\Swiper;
use App\Livewire\Actions\Logout;
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Route cho trang chủ
Route::view('/', 'welcome')->name('home');
// Route đăng xuất


// Routes dành cho các người dùng đã đăng nhập và đã được xác thực (auth middleware)
Route::middleware('auth')->group(function () {

    // Trang xem chi tiết người dùng
    Route::get('view_my_details', [UserController::class, 'viewMyDetails'])->name('view_my_details');

    // Trang thông tin cá nhân
    Route::get('/profile', [UserController::class, 'userProfile'])->name('profile');
    Route::get('/info', [UserController::class, 'showProfileForm'])->name('info');
    Route::post('/info', [UserController::class, 'updateInfor'])->name('info.update');

    // Trang dashboard cho người dùng
    Route::get('/dashboard', [UserController::class, 'userDashboard'])
        ->middleware('verified')
        ->name('dashboard');

    // Trang bộ lọc Swiper
    Route::get('/filter', Swiper::class)->name('filter');

    // Chat functionality
    Route::get('/dashboard/chat', Index::class)->name('chat.index');
    Route::get('/dashboard/chat/{chat}', Chat::class)->name('chat');

    // Routes cho admin (sử dụng middleware để kiểm tra isAdmin)
    Route::middleware('isAdmin')->group(function () {
        Route::get('admin/dashboard', [UserController::class, 'admin_dashboard'])->name('admin.dashboard');
    });
});

// Yêu cầu file routes của hệ thống authentication
require __DIR__.'/auth.php';
