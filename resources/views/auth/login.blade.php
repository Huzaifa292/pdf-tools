<x-guest-layout>
<div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
    <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-1">Welcome back</h2>
    <p class="text-gray-500 text-sm text-center mb-6">Login to access all PDF tools</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
        </div>
        <div class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <label class="text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-red-500 hover:text-red-600">Forgot password?</a>
                @endif
            </div>
            <input type="password" name="password" required
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
        </div>
        <div class="flex items-center mb-5">
            <input type="checkbox" name="remember" id="remember" class="accent-red-500 mr-2">
            <label for="remember" class="text-sm text-gray-600">Remember me</label>
        </div>
        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-base transition shadow-sm">
            Login →
        </button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-5">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-red-500 hover:text-red-600 font-semibold">Sign up free</a>
    </p>
</div>
</x-guest-layout>
