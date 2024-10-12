<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.login')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if(auth()->user() && auth()->user()->is_admin == 1){
            return redirect()->route('admin.dashboard');
        } else if(auth()->user()) {
            return redirect()->route('dashboard');
        }

        return redirect('/');
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <h1 class="flex justify-center text-3xl mb-8 font-bold">Welcome</h1>

        <!-- TODO: Adjust the error displays to not explicitly say which part is wrong. -->

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input type="email"
                          wire:model="form.email"
                          class="block mt-1 w-full"
                          id="email" name="email"
                          required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input type="password"
                          wire:model="form.password"
                          id="password" name="password"
                          class="block mt-1 w-full"
                          required autocomplete="current-password"/>

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login Button -->
        <button type="submit" class="bg-register">
            {{ __('Log in') }}
        </button>
    </form>

    @if (Route::has('password.request'))
        <div class="flex mt-3 justify-center">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                Forgot your password?
            </a>
        </div>
    @endif

    <div class="mt-4 relative flex justify-center text-sm">
        <span class="px-2 bg-white text-gray-500">OR Login with...</span>
    </div>

    <div class="flex justify-evenly mt-2">
        <a href="{{ route('auth.google')  }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" viewBox="0 0 488 512">
                <path fill="#4285F4" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/>
            </svg>
        </a>
    </div>

    <hr class="mt-4 border-gray-300"/>

    <a class="flex mt-3 justify-center text-sm text-gray-600" href="{{route('register')}}">
        Don't have an account? Sign up now.
    </a>
</div>
