@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 rounded-full text-sm font-medium" 
                  style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}; border: 1px solid {{ $tag->color }}40;">
                {{ $tag->name }}
            </span>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('tags.edit', $tag) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Edit Tag
            </a>
            <a href="{{ route('tags.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Back to Tags
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tag Details -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tag Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-600">Name:</span>
                    <p class="text-sm text-gray-800">{{ $tag->name }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Color:</span>
                    <div class="flex items-center mt-1">
                        <div class="w-8 h-8 rounded-full border border-gray-300" style="background-color: {{ $tag->color }}"></div>
                        <span class="ml-2 text-sm text-gray-600">{{ $tag->color }}</span>
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Created:</span>
                    <p class="text-sm text-gray-800">{{ $tag->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Last Updated:</span>
                    <p class="text-sm text-gray-800">{{ $tag->updated_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Issues with this Tag ({{ $tag->issues->count() }})</h2>
            
            @if($tag->issues->count() > 0)
                <div class="space-y-3">
                    @foreach($tag->issues as $issue)
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                            <a href="{{ route('issues.show', $issue) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $issue->title }}
                            </a>
                            <div class="flex items-center mt-1 space-x-2">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                    {{ $issue->project->name }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($issue->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No issues are using this tag.</p>
            @endif
        </div>
    </div>
</div>
@endsection 