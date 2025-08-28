@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Project Management</h1>
            <p class="text-gray-600 mt-2">Manage all your projects in one place</p>
        </div>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition duration-200 shadow-md">
            <i class="bi bi-plus-circle mr-2"></i>
            Create New Project
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="bi bi-check-circle-fill text-green-500 mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Projects Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-folder2-open mr-2"></i>
                                Project
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-exclamation-circle mr-2"></i>
                                Issues
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-calendar-event mr-2"></i>
                                Timeline
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="bi bi-gear mr-2"></i>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-5">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mt-1">
                                        <i class="bi bi-kanban text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $project->name }}</div>
                                        <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($project->description, 100) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $project->issues_count > 0 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="bi {{ $project->issues_count > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle' }} mr-1"></i>
                                    {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="bi bi-play-circle mr-1 text-gray-400"></i>
                                        {{ $project->start_date?->format('M d, Y') ?? 'Not set' }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <div class="flex items-center">
                                        <i class="bi bi-flag mr-1 text-gray-400"></i>
                                        {{ $project->deadline?->format('M d, Y') ?? 'Not set' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition"
                                       title="View Project">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" 
                                       class="text-green-600 hover:text-green-800 p-2 rounded-full hover:bg-green-100 transition"
                                       title="Edit Project">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition"
                                                onclick="return confirm('Are you sure you want to delete this project?')"
                                                title="Delete Project">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-inbox text-4xl mb-3"></i>
                                    <p class="text-lg">No projects found.</p>
                                    <p class="mt-2">Get started by <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-800">creating a new project</a>.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
    <div class="mt-6 bg-white px-5 py-3 rounded-lg shadow-sm border border-gray-100">
        {{ $projects->links() }}
    </div>
    @endif
</div>

<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        list-style-type: none;
        padding: 0;
    }
    
    .pagination li {
        margin: 0 4px;
    }
    
    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        width: 40px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination a:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }
    
    .pagination .active span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
</style>
@endsection