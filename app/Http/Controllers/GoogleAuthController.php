<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {
        $google_user = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'google_id' => $google_user->getId(),
        ], [
            'name' => $google_user->getName(),
            'email' => $google_user->getEmail(),
            'google_id' => $google_user->getId(),
        ]);

//        $user = User::where('google_id', $google_user->getId())->first();
//
//        if (!$user) {
//            $user = User::create(['name' => $google_user->getName(), 'email' => $google_user->getEmail(), 'google_id' => $google_user->getId(),]);
//        }

        Auth::login($user);

        return redirect()->intended('dashboard');
    }
}
