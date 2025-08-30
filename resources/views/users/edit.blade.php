@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        {{-- Header --}}
        <div class="flex items-center justify-between bg-blue-600 text-white px-6 py-4">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <i class="bi bi-pencil-square"></i>
                Edit User
            </h2>
            <a href="{{ route('users.index') }}" 
               class="flex items-center gap-1 bg-white text-blue-600 hover:bg-gray-100 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-person-circle text-blue-500 mr-1"></i>
                        Name
                    </label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name', $user->name) }}"
                           required autofocus
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-800">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-envelope-at text-blue-500 mr-1"></i>
                        Email Address
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-800">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-lock text-blue-500 mr-1"></i>
                        Password
                    </label>
                    <input id="password" type="password" name="password"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-800">
                    <p class="text-gray-500 text-sm mt-1">Leave blank to keep current password</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-shield-lock text-blue-500 mr-1"></i>
                        Confirm Password
                    </label>
                    <input id="password-confirm" type="password" name="password_confirmation"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-800">
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" 
                            class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
