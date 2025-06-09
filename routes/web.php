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
use Illuminate\Http\Request;

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

Route::get('/locale/{code}', function ($code) {
    if (\App\Models\ApplicationLanguage::where('code', $code)->pluck('code')->first() != null) {
        \Illuminate\Support\Facades\Session::put('locale', $code);
    }

    return redirect()->back();
})->name('locale');

// Route for homepage
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'language'])->group(function () {
    Route::middleware(['only-banned'])->group(function () {
        Route::get('/banned', [UserController::class, 'showBan'])->name('banned.user');
    });

    // Routes for user who has login, authenticated and forbid banned user.
    Route::middleware(['forbid-banned'])->group(function () {
        // User details
        Route::get('view_my_details', [UserController::class, 'viewMyDetails'])->name('view_my_details');

        // Visiting users' profile
        Route::get('/users/{id}', [UserController::class, 'visitUserProfile'])->name('users.profile');

        // Personal profiles
        Route::get('/profile', [UserController::class, 'userProfile'])->name('profile');
        Route::get('/info', [UserController::class, 'showProfileForm'])->name('info');
        Route::post('/info', [UserController::class, 'updateInfor'])->name('info.update');
        Route::delete('/user/{id}/image', [UserController::class, 'deleteImage'])->name('user.image.delete');

        Route::get('/settings', [UserController::class, 'showSettings'])->name('settings');

        // User dashboard
        Route::get('/dashboard', [UserController::class, 'userDashboard'])
            ->middleware('verified')
            ->name('dashboard');

        // Swiper
        Route::get('/filter', Swiper::class)->name('filter');

        // Chatting
    //    Route::get('/dashboard/chat', Index::class)->name('chat.index');
        Route::get('/dashboard/chat/{chat}', Chat::class)->name('chat');

        // Routes for admins
        Route::middleware('isAdmin')->group(function () {
            Route::get('admin/dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
        });

        // Route::post('/broadcasting/auth', [PusherController::class, 'pusherAuth']);
    });
});

Route::group(['middleware' => ['web']], function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

// Yêu cầu file routes của hệ thống authentication
require __DIR__.'/auth.php';
