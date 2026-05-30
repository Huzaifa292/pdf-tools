<x-guest-layout>
<div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
    <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-1">Create your account</h2>
    <p class="text-gray-500 text-sm text-center mb-6">Free forever — no credit card needed</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-xs" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
        </div>
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
        </div>
        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-base transition shadow-sm">
            Create Account →
        </button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-5">
        Already have an account?
        <a href="{{ route('login') }}" class="text-red-500 hover:text-red-600 font-semibold">Login</a>
    </p>
</div>
</x-guest-layout>
