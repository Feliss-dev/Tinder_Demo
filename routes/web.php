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
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DownloadStatisticController;

// Route for homepage.
Route::view('/', 'welcome')->name('home');
// Route for logging out.

// Routes for user who has login and authenticated.
Route::middleware('auth')->group(function () {
    // User details
    Route::get('view_my_details', [UserController::class, 'viewMyDetails'])->name('view_my_details');

    // Personal profiles
    Route::get('/profile', [UserController::class, 'userProfile'])->name('profile');
    Route::get('/info', [UserController::class, 'showProfileForm'])->name('info');
    Route::post('/info', [UserController::class, 'updateInfor'])->name('info.update');
    Route::delete('/user/{id}/image', [UserController::class, 'deleteImage'])->name('user.image.delete');

    Route::post('/avatar', [AvatarController::class, 'store'])->name('avatar.store');
    Route::delete('/avatar/{avatar}', [AvatarController::class, 'destroy'])->name('avatar.destroy');
    Route::get('/avatar/{avatar}/set-active', [AvatarController::class, 'setActive'])->name('avatar.setActive');

    // User dashboard
    Route::get('/dashboard', [UserController::class, 'userDashboard'])
        ->middleware('verified')
        ->name('dashboard');

    // Swiper
    Route::get('/filter', Swiper::class)->name('filter');

    // Chatting
    Route::get('/dashboard/chat', Index::class)->name('chat.index');
    Route::get('/dashboard/chat/{chat}', Chat::class)->name('chat');

    // Routes for admins
    Route::middleware('isAdmin')->group(function () {
        Route::get('admin/dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('admin/download-statistic', [DownloadStatisticController::class, 'generatePdf'])->name('name.downloadStatistic');
    });
});

Route::group(['middleware' => ['web']], function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

// Yêu cầu file routes của hệ thống authentication
require __DIR__.'/auth.php';
