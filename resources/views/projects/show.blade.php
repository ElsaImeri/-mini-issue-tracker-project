@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $project->description }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="bi bi-pencil-square mr-2"></i> Edit Project
            </a>
            <a href="{{ route('projects.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Back to Projects
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Project Details -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Project Details</h3>
            </div>
            <div class="space-y-3 text-gray-600">
                <div class="flex items-center">
                    <i class="bi bi-calendar-event mr-3 text-gray-400"></i>
                    <span class="font-medium">Start Date:</span>
                    <span class="ml-2">{{ $project->start_date?->format('M d, Y') ?? 'Not set' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="bi bi-calendar-x mr-3 text-gray-400"></i>
                    <span class="font-medium">Deadline:</span>
                    <span class="ml-2">{{ $project->deadline?->format('M d, Y') ?? 'Not set' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="bi bi-clock-history mr-3 text-gray-400"></i>
                    <span class="font-medium">Created:</span>
                    <span class="ml-2">{{ $project->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Issues Summary -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                    <i class="bi bi-clipboard-data text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Issues Summary</h3>
            </div>
            <div class="space-y-3 text-gray-600">
                <div class="flex justify-between items-center">
                    <span>Total Issues</span>
                    <span class="font-semibold">{{ $project->issues->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <span class="bg-blue-500 w-3 h-3 rounded-full mr-2"></span> Open
                    </span>
                    <span class="font-semibold">{{ $project->issues->where('status', 'open')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <span class="bg-yellow-500 w-3 h-3 rounded-full mr-2"></span> In Progress
                    </span>
                    <span class="font-semibold">{{ $project->issues->where('status', 'in_progress')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <span class="bg-green-500 w-3 h-3 rounded-full mr-2"></span> Closed
                    </span>
                    <span class="font-semibold">{{ $project->issues->where('status', 'closed')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-amber-100 p-3 rounded-lg mr-4">
                    <i class="bi bi-lightning-charge text-amber-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
            </div>
            <div class="space-y-3">
                <a href="{{ route('issues.index') }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 transition duration-200">
                    <div class="flex items-center">
                        <i class="bi bi-plus-circle mr-3 text-indigo-600"></i>
                        <span>Create New Issue</span>
                    </div>
                    <i class="bi bi-arrow-right-short text-gray-400"></i>
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 transition duration-200">
                    <div class="flex items-center">
                        <i class="bi bi-gear mr-3 text-indigo-600"></i>
                        <span>Edit Project Details</span>
                    </div>
                    <i class="bi bi-arrow-right-short text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Project Issues Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="bi bi-clipboard mr-2"></i> Project Issues
            </h2>
            <a href="{{ route('issues.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                <i class="bi bi-plus-lg mr-1"></i> Add Issue
            </a>
        </div>

        @if($project->issues->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($project->issues as $issue)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $issue->title }}</div>
                                    <div class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($issue->description, 100) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = ['open'=>'bg-blue-100 text-blue-800','in_progress'=>'bg-yellow-100 text-yellow-800','closed'=>'bg-green-100 text-green-800'];
                                        $statusIcons = ['open'=>'bi-circle','in_progress'=>'bi-arrow-repeat','closed'=>'bi-check-circle'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full {{ $statusColors[$issue->status] }}">
                                        <i class="bi {{ $statusIcons[$issue->status] }} mr-1"></i>
                                        {{ str_replace('_',' ', ucfirst($issue->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priorityColors = ['low'=>'bg-gray-100 text-gray-800','medium'=>'bg-amber-100 text-amber-800','high'=>'bg-red-100 text-red-800'];
                                        $priorityIcons = ['low'=>'bi-arrow-down','medium'=>'bi-dash','high'=>'bi-arrow-up'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full {{ $priorityColors[$issue->priority] }}">
                                        <i class="bi {{ $priorityIcons[$issue->priority] }} mr-1"></i>
                                        {{ ucfirst($issue->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="bi bi-calendar-event mr-2 text-gray-400"></i>
                                        {{ $issue->due_date?->format('M d, Y') ?? 'Not set' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="bi bi-chat-left-text mr-2 text-gray-400"></i>
                                        <span class="font-medium">{{ $issue->comments_count }}</span>
                                        <span class="ml-1">comments</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($issue->tags as $tag)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full flex items-center" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}; border: 1px solid {{ $tag->color }}40;">
                                                <i class="bi bi-tag-fill mr-1" style="color: {{ $tag->color }}"></i>
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i class="bi bi-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No issues yet</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first issue</p>
                <a href="{{ route('issues.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="bi bi-plus-lg mr-2"></i> New Issue
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .bi::before { display: inline-block; line-height: 1; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection
