<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-base-100 shadow-xl rounded-lg p-8 space-y-6">

        <h2 class="text-2xl font-bold text-center text-primary mb-4">
            <i class="fa-solid fa-right-to-bracket mr-2"></i>Login to Your Account
        </h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="label">
                    <span class="label-text"><i class="fa-solid fa-envelope mr-2"></i>Email</span>
                </label>
                <input id="email" name="email" type="email" class="input input-bordered w-full"
                    value="{{ old('email') }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-error" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="label">
                    <span class="label-text"><i class="fa-solid fa-lock mr-2"></i>Password</span>
                </label>
                <input id="password" name="password" type="password" class="input input-bordered w-full" required
                    autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-error" />
            </div>

            <!-- Remember Me -->
            {{-- <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="checkbox checkbox-primary mr-2">
                <label for="remember_me" class="text-sm text-gray-600">
                    {{ __('Remember me') }}
                </label>
            </div> --}}

            <!-- Actions -->
            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-primary hover:underline flex items-center">
                        <i class="fa-solid fa-key mr-1"></i> {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i> {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
