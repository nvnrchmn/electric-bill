<x-guest-layout>
    <h2 class="text-2xl font-bold text-center mb-6 text-primary"><i class="fa-solid fa-user-plus me-2"></i> Register
        Account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="form-control">
            <label for="name" class="label">
                <span class="label-text"><i class="fa-solid fa-user me-2"></i> Name</span>
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name" class="input input-bordered w-full" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Email -->
        <div class="form-control">
            <label for="email" class="label">
                <span class="label-text"><i class="fa-solid fa-envelope me-2"></i> Email</span>
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" class="input input-bordered w-full" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div class="form-control">
            <label for="password" class="label">
                <span class="label-text"><i class="fa-solid fa-lock me-2"></i> Password</span>
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="input input-bordered w-full" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div class="form-control">
            <label for="password_confirmation" class="label">
                <span class="label-text"><i class="fa-solid fa-lock me-2"></i> Confirm Password</span>
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" class="input input-bordered w-full" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-sm" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:underline">
                <i class="fa-solid fa-arrow-left me-1"></i> Already registered?
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-user-plus me-1"></i> Register
            </button>
        </div>
    </form>
</x-guest-layout>
