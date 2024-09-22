<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Models\User;

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

Route::view('/', 'welcome')->name('home');

Route::middleware('auth')->group(function () {
   Route::get('view_my_details', [UserController::class, 'viewMyDetails'])->name('view_my_details');
   

   Route::get('/profile', [UserController::class, 'userProfile'])->name('profile');
   Route::get('/info', [UserController::class, 'showProfileForm'])->name('info');
   Route::post('/info', [UserController::class, 'updateInfor'])->name('info.update');

   Route::get('/dashboard', [UserController::class, 'userDashboard'])
        ->middleware('verified')
        ->name('dashboard');

        Route::get('/dashboard/chat', Index::class)->name('chat.index');
        Route::get('/dashboard/chat/{chat}', Chat::class)->name('chat');

        Route::get('admin/dashboard', [UserController::class, 'admin_dashboard'])
        ->middleware('isAdmin')
        ->name('admin.dashboard');
});



require __DIR__.'/auth.php';
