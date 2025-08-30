@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Tags Management</h1>
            <p class="text-gray-600 mt-2">Organize and categorize issues with tags</p>
        </div>
        <a href="{{ route('tags.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg transition duration-200 flex items-center shadow-md hover:shadow-lg">
            <i class="bi bi-plus-circle mr-2"></i> Create New Tag
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tags Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-tag mr-2"></i>
                                <span>Name</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-palette mr-2"></i>
                                <span>Color</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-bug mr-2"></i>
                                <span>Issues Count</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-gear mr-2"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tags as $tag)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $tag->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full border border-gray-300 shadow-sm tag-color-badge" style="background-color: {{ $tag->color }}"></div>
                                    <span class="ml-2 text-sm text-gray-600 font-mono">{{ $tag->color }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $tag->issues_count }} {{ $tag->issues_count == 1 ? 'issue' : 'issues' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('tags.show', $tag) }}" class="text-blue-600 hover:text-blue-900 flex items-center action-btn" title="View">
                                        <i class="bi bi-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('tags.edit', $tag) }}" class="text-green-600 hover:text-green-900 flex items-center action-btn" title="Edit">
                                        <i class="bi bi-pencil-square mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center action-btn" title="Delete" onclick="return confirm('Are you sure? This will remove the tag from all issues.')">
                                            <i class="bi bi-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="bi bi-tag text-4xl mb-3 opacity-50"></i>
                                    <p class="mb-1">No tags found.</p>
                                    <a href="{{ route('tags.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                        <i class="bi bi-plus-circle mr-1"></i> Create your first tag
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $tags->links() }}
        </div>
    </div>
</div>

<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .tag-color-badge {
        transition: all 0.2s ease;
    }
    .tag-color-badge:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
</style>
@endsection