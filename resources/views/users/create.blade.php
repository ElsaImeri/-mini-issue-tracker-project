@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Create New User</h1>
            <p class="text-gray-600 mt-2">Fill the form to add a new user to the system</p>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg transition duration-200 shadow-sm">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Users
        </a>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-person-fill mr-1"></i> Name
                </label>
                <input id="name" type="text" 
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                       name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="text-red-600 text-sm mt-1"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-envelope-fill mr-1"></i> Email Address
                </label>
                <input id="email" type="email" 
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                       name="email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-lock-fill mr-1"></i> Password
                </label>
                <input id="password" type="password" 
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                       name="password" required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-shield-lock-fill mr-1"></i> Confirm Password
                </label>
                <input id="password-confirm" type="password" 
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                       name="password_confirmation" required>
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-person-badge-fill mr-1"></i> Role
                </label>
                <select id="role" name="role" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror" required>
                    <option value="">Select a role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="text-red-600 text-sm mt-1"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="bi bi-save mr-1"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
@endsection