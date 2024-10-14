<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.login')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user() && auth()->user()->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()) {
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
            <x-text-input type="email" wire:model="form.email" class="block mt-1 w-full" id="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input type="password" wire:model="form.password" id="password" name="password"
                class="block mt-1 w-full" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('password.request') }}" wire:navigate>
                Forgot your password?
            </a>
        </div>
    @endif

    <div class="mt-4 relative flex justify-center text-sm">
        <span class="px-2 bg-white text-gray-500">OR Login with...</span>
    </div>

    <div class="flex justify-evenly mt-2">
        <a href="{{ route('auth.google') }}">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48"
                class="h-12 w-12">
                <path fill="#FFC107"
                    d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                </path>
                <path fill="#FF3D00"
                    d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                </path>
                <path fill="#4CAF50"
                    d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                </path>
                <path fill="#1976D2"
                    d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                </path>
            </svg>
        </a>
    </div>

    <hr class="mt-4 border-gray-300" />

    <a class="flex mt-3 justify-center text-sm text-gray-600" href="{{ route('register') }}">
        Don't have an account? Sign up now.
    </a>
</div>
