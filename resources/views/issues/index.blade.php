@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-list-task mr-3 text-blue-500"></i> All Issues
            </h1>
            <p class="text-gray-600 mt-2">Manage and track all project issues in one place</p>
        </div>
        <a href="{{ route('issues.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg transition duration-200 flex items-center justify-center shadow-md">
            <i class="bi bi-plus-circle mr-2"></i> Create New Issue
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
            <i class="bi bi-funnel mr-2 text-blue-500"></i> Filter Issues
        </h2>
        <form action="{{ route('issues.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <i class="bi bi-circle-half mr-2 text-gray-500"></i> Status
                </label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <i class="bi bi-exclamation-circle mr-2 text-gray-500"></i> Priority
                </label>
                <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $value => $label)
                        <option value="{{ $value }}" {{ request('priority') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <i class="bi bi-tag mr-2 text-gray-500"></i> Tag
                </label>
                <select name="tag" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg w-full flex items-center justify-center shadow-md">
                    <i class="bi bi-funnel mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Issues List Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-100 border-b border-gray-200">
            <div class="col-span-4 text-sm font-medium text-gray-700 uppercase tracking-wider">
                <i class="bi bi-card-heading mr-2"></i> Issue
            </div>
            <div class="col-span-2 text-sm font-medium text-gray-700 uppercase tracking-wider">
                <i class="bi bi-kanban mr-2"></i> Project
            </div>
            <div class="col-span-2 text-sm font-medium text-gray-700 uppercase tracking-wider">
                <i class="bi bi-circle-half mr-2"></i> Status
            </div>
            <div class="col-span-2 text-sm font-medium text-gray-700 uppercase tracking-wider">
                <i class="bi bi-exclamation-circle mr-2"></i> Priority
            </div>
            <div class="col-span-2 text-sm font-medium text-gray-700 uppercase tracking-wider">
                <i class="bi bi-calendar-event mr-2"></i> Due Date
            </div>
        </div>

        <!-- Issues List -->
        <div class="divide-y divide-gray-200">
            @forelse($issues as $issue)
                <div class="issue-card bg-white p-6 transition-all duration-200">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        <!-- Issue Details -->
                        <div class="md:col-span-4">
                            <a href="{{ route('issues.show', $issue) }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                <i class="bi bi-card-text mr-2 text-blue-500"></i> 
                                {{ $issue->title }}
                            </a>
                            <p class="text-gray-600 mt-1 text-sm">{{ Str::limit($issue->description, 60) }}</p>
                            
                            <!-- Mobile Only: Status, Priority, Due Date -->
                            <div class="mt-3 flex flex-wrap gap-2 md:hidden">
                                @php 
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-800', 
                                        'in_progress' => 'bg-yellow-100 text-yellow-800', 
                                        'closed' => 'bg-green-100 text-green-800'
                                    ];
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800', 
                                        'medium' => 'bg-orange-100 text-orange-800', 
                                        'high' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                
                                <span class="status-badge {{ $statusColors[$issue->status] }}">
                                    @if($issue->status == 'open')
                                        <i class="bi bi-circle mr-1"></i>
                                    @elseif($issue->status == 'in_progress')
                                        <i class="bi bi-arrow-repeat mr-1"></i>
                                    @else
                                        <i class="bi bi-check-circle mr-1"></i>
                                    @endif
                                    {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                                </span>
                                
                                <span class="priority-badge {{ $priorityColors[$issue->priority] }}">
                                    @if($issue->priority == 'low')
                                        <i class="bi bi-arrow-down-circle mr-1"></i>
                                    @elseif($issue->priority == 'medium')
                                        <i class="bi bi-dash-circle mr-1"></i>
                                    @else
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                    @endif
                                    {{ ucfirst($issue->priority) }}
                                </span>
                                
                                @if($issue->due_date)
                                    <span class="bg-gray-100 text-gray-800 status-badge">
                                        <i class="bi bi-calendar-check mr-1"></i> {{ $issue->due_date->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Project Name (Desktop) -->
                        <div class="hidden md:block md:col-span-2">
                            <div class="text-gray-700 flex items-center">
                                <i class="bi bi-kanban mr-2 text-gray-500"></i> 
                                {{ $issue->project->name }}
                            </div>
                        </div>
                        
                        <!-- Status (Desktop) -->
                        <div class="hidden md:block md:col-span-2">
                            <span class="status-badge {{ $statusColors[$issue->status] }}">
                                @if($issue->status == 'open')
                                    <i class="bi bi-circle mr-1"></i>
                                @elseif($issue->status == 'in_progress')
                                    <i class="bi bi-arrow-repeat mr-1"></i>
                                @else
                                    <i class="bi bi-check-circle mr-1"></i>
                                @endif
                                {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                            </span>
                        </div>
                        
                        <!-- Priority (Desktop) -->
                        <div class="hidden md:block md:col-span-2">
                            <span class="priority-badge {{ $priorityColors[$issue->priority] }}">
                                @if($issue->priority == 'low')
                                    <i class="bi bi-arrow-down-circle mr-1"></i>
                                @elseif($issue->priority == 'medium')
                                    <i class="bi bi-dash-circle mr-1"></i>
                                @else
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                @endif
                                {{ ucfirst($issue->priority) }}
                            </span>
                        </div>
                        
                        <!-- Due Date (Desktop) -->
                        <div class="hidden md:block md:col-span-1">
                            @if($issue->due_date)
                                <div class="text-gray-700 flex items-center">
                                    <i class="bi bi-calendar-event mr-2 text-gray-500"></i> 
                                    {{ $issue->due_date->format('M d, Y') }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="md:col-span-1 flex justify-end md:justify-start">
                            <div class="flex space-x-3">
                                <a href="{{ route('issues.show', $issue) }}" class="text-blue-500 hover:text-blue-700 transition-colors duration-200" title="View">
                                    <i class="bi bi-eye text-lg"></i>
                                </a>
                                <a href="{{ route('issues.edit', $issue) }}" class="text-green-500 hover:text-green-700 transition-colors duration-200" title="Edit">
                                    <i class="bi bi-pencil text-lg"></i>
                                </a>
                                <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-200" title="Delete" onclick="return confirm('Are you sure you want to delete this issue?')">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <i class="bi bi-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-lg">No issues found</p>
                    <p class="text-gray-400 mt-2">Get started by creating a new issue</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($issues->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="pagination flex justify-center">
                    {{ $issues->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<style>
    .issue-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .status-badge, .priority-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.65rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .pagination .page-item {
        display: inline-block;
        margin: 0 0.15rem;
    }
    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        font-weight: 500;
    }
    .pagination .page-link:hover {
        background-color: #f3f4f6;
    }
    .pagination .active .page-link {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const issueCards = document.querySelectorAll('.issue-card');
        issueCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('shadow-md');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('shadow-md');
            });
        });
    });
</script>
@endsection