@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="bi bi-list-task mr-2"></i> All Issues
        </h1>
        <a href="{{ route('issues.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="bi bi-plus-circle mr-2"></i> Create New Issue
        </a>
    </div>

    <!-- Filtro Form -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <form action="{{ route('issues.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="bi bi-circle-half mr-1"></i> Status
                </label>
                <select name="status" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="bi bi-exclamation-circle mr-1"></i> Priority
                </label>
                <select name="priority" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $value => $label)
                        <option value="{{ $value }}" {{ request('priority') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="bi bi-tag mr-1"></i> Tag
                </label>
                <select name="tag" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md w-full flex items-center justify-center">
                    <i class="bi bi-funnel mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Lista e Issue-ve -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-card-heading mr-1"></i> Title
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-kanban mr-1"></i> Project
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-circle-half mr-1"></i> Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-exclamation-circle mr-1"></i> Priority
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-calendar-event mr-1"></i> Due Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-gear mr-1"></i> Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('issues.show', $issue) }}" class="hover:text-blue-600 flex items-center">
                                        <i class="bi bi-card-text mr-1"></i> {{ $issue->title }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">{{ Str::limit($issue->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="bi bi-kanban mr-1"></i> {{ $issue->project->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $statusColors = ['open' => 'bg-blue-100 text-blue-800', 'in_progress' => 'bg-yellow-100 text-yellow-800', 'closed' => 'bg-green-100 text-green-800']; @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$issue->status] }} flex items-center">
                                    @if($issue->status == 'open')
                                        <i class="bi bi-circle mr-1"></i>
                                    @elseif($issue->status == 'in_progress')
                                        <i class="bi bi-arrow-repeat mr-1"></i>
                                    @else
                                        <i class="bi bi-check-circle mr-1"></i>
                                    @endif
                                    {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $priorityColors = ['low' => 'bg-gray-100 text-gray-800', 'medium' => 'bg-orange-100 text-orange-800', 'high' => 'bg-red-100 text-red-800']; @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$issue->priority] }} flex items-center">
                                    @if($issue->priority == 'low')
                                        <i class="bi bi-arrow-down-circle mr-1"></i>
                                    @elseif($issue->priority == 'medium')
                                        <i class="bi bi-dash-circle mr-1"></i>
                                    @else
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                    @endif
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($issue->due_date)
                                    <i class="bi bi-calendar-check mr-1"></i> {{ $issue->due_date->format('M d, Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('issues.show', $issue) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                        <i class="bi bi-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('issues.edit', $issue) }}" class="text-green-600 hover:text-green-900 flex items-center">
                                        <i class="bi bi-pencil mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                <i class="bi bi-inbox mr-1"></i> No issues found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $issues->links() }}
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
@endsection