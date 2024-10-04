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

        @if (Route::has('password.request'))
            <div class="flex mt-3 justify-center">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif

        <hr class="mt-4 border-gray-300"/>

        <a class="flex mt-3 justify-center text-sm text-gray-600" href="{{route('register')}}">
            {{__("Don't have an account? Sign up now.")}}
        </a>
    </form>
</div>
