@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between bg-blue-600 text-white px-6 py-4">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <i class="bi bi-person-badge"></i>
                User Details
            </h2>
            <a href="{{ route('users.index') }}" 
               class="flex items-center gap-1 bg-white text-blue-600 hover:bg-gray-100 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-6">
            {{-- ID --}}
            <div class="flex items-center justify-between border-b pb-3">
                <span class="text-gray-600 flex items-center gap-2">
                    <i class="bi bi-hash text-blue-500"></i> ID
                </span>
                <span class="font-medium text-gray-800">{{ $user->id }}</span>
            </div>

            {{-- Name --}}
            <div class="flex items-center justify-between border-b pb-3">
                <span class="text-gray-600 flex items-center gap-2">
                    <i class="bi bi-person-circle text-blue-500"></i> Name
                </span>
                <span class="font-medium text-gray-800">{{ $user->name }}</span>
            </div>

            {{-- Email --}}
            <div class="flex items-center justify-between border-b pb-3">
                <span class="text-gray-600 flex items-center gap-2">
                    <i class="bi bi-envelope-at text-blue-500"></i> Email
                </span>
                <span class="font-medium text-gray-800">{{ $user->email }}</span>
            </div>

            {{-- Registered At --}}
            <div class="flex items-center justify-between">
                <span class="text-gray-600 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-blue-500"></i> Registered At
                </span>
                <span class="font-medium text-gray-800">{{ $user->created_at->format('F j, Y') }}</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3 bg-gray-50 px-6 py-4">
            <a href="{{ route('users.edit', $user->id) }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                <i class="bi bi-arrow-left-circle"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
