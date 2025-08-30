@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Tag</h1>
        <a href="{{ route('tags.index') }}" class="text-gray-500 hover:text-gray-700">
            ← Back to Tags
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('tags.update', $tag->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tag Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="color" id="color" value="{{ old('color', $tag->color) }}"
                        class="w-12 h-12 p-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('color') border-red-500 @enderror">
                    <input type="text" name="color_text" id="color_text" value="{{ old('color', $tag->color) }}"
                        class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="#3b82f6">
                </div>
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Click the color box to pick a color or enter a hex code.</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('tags.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                    Update Tag
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color_text');
    
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value;
    });
    
    colorTextInput.addEventListener('input', function() {
        if (this.value.startsWith('#') && this.value.length === 7) {
            colorInput.value = this.value;
        }
    });
});
</script>
@endsection