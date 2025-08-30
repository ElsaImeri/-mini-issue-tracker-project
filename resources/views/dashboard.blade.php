@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-speedometer2 text-blue-600"></i>
                    Dashboard Overview
                </h1>
                <p class="text-gray-600 mt-2">Welcome back! Here's what's happening with your projects today.</p>
            </div>
            <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
                <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-200 flex items-center shadow-md">
                    <i class="bi bi-plus-circle mr-2"></i> New Project
                </a>
                <a href="{{ route('issues.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl transition duration-200 flex items-center shadow-md">
                    <i class="bi bi-plus-circle mr-2"></i> New Issue
                </a>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $statsData = [
                    ['title' => 'Total Projects', 'value' => $stats['total_projects'], 'icon' => 'bi-kanban', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'change' => '+5 from last week', 'changeColor' => 'text-blue-600'],
                    ['title' => 'Total Issues', 'value' => $stats['total_issues'], 'icon' => 'bi-list-task', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'change' => '+12 from last week', 'changeColor' => 'text-purple-600'],
                    ['title' => 'Open Issues', 'value' => $stats['open_issues'], 'icon' => 'bi-circle', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'change' => '-3 from last week', 'changeColor' => 'text-amber-600'],
                    ['title' => 'In Progress', 'value' => $stats['in_progress_issues'], 'icon' => 'bi-arrow-repeat', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'change' => '+7 from last week', 'changeColor' => 'text-orange-600'],
                ];
            @endphp
            @foreach($statsData as $card)
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl {{ $card['bg'] }} {{ $card['text'] }} mr-4 flex items-center justify-center text-2xl">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ $card['title'] }}</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $card['value'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <span class="text-sm font-medium {{ $card['changeColor'] }} flex items-center">
                            <i class="bi bi-arrow-up-short mr-1"></i> {{ $card['change'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Projects & Issues -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Recent Projects -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-clock-history text-blue-600"></i> Recent Projects
                    </h2>
                    <a href="{{ route('projects.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_projects as $project)
                        <div class="px-6 py-4 hover:bg-gray-50 transition flex justify-between items-center">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <div>
                                    <a href="{{ route('projects.show', $project) }}" class="font-medium text-gray-900 hover:text-blue-600">{{ $project->name }}</a>
                                    <p class="text-sm text-gray-500 mt-1">{{ $project->issues_count }} issues</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $project->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400">
                            <i class="bi bi-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No projects found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Issues -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-list-task text-purple-600"></i> Recent Issues
                    </h2>
                    <a href="{{ route('issues.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_issues as $issue)
                        <div class="px-6 py-4 hover:bg-gray-50 transition flex justify-between items-start">
                            <div>
                                <a href="{{ route('issues.show', $issue) }}" class="text-gray-900 font-medium hover:text-blue-600 block">{{ $issue->title }}</a>
                                <div class="flex items-center mt-2 flex-wrap gap-2 text-xs">
                                    <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full font-medium">{{ $issue->project->name }}</span>
                                    <span class="bg-gray-100 text-gray-800 px-2.5 py-0.5 rounded-full font-medium">{{ ucfirst($issue->status) }}</span>
                                    <span class="bg-red-100 text-red-800 px-2.5 py-0.5 rounded-full font-medium">{{ ucfirst($issue->priority) }}</span>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $issue->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400">
                            <i class="bi bi-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No issues found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Projects with Most Issues -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-trophy text-amber-600"></i> Projects with Most Issues
                </h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($projects_with_most_issues as $index => $project)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-400 font-bold">#{{ $index + 1 }}</span>
                                <a href="{{ route('projects.show', $project) }}" class="font-medium text-gray-900 hover:text-blue-600">{{ $project->name }}</a>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">{{ $project->issues_count }} issues</span>
                        </div>
                        <div class="mt-3 bg-gray-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full rounded-full" style="width: {{ ($project->issues_count / ($projects_with_most_issues->first()->issues_count ?: 1)) * 80 + 20 }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400">
                        <i class="bi bi-inbox text-3xl mb-2 opacity-50"></i>
                        <p>No projects found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Issues Status & Priority Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-clipboard-data text-blue-600"></i> Issues Status Overview
                </h2>
                <div class="space-y-4">
                    @php
                        $statusData = [
                            'open' => ['count' => $issues_by_status['open'] ?? 0, 'color' => 'bg-blue-500', 'text' => 'Open'],
                            'in_progress' => ['count' => $issues_by_status['in_progress'] ?? 0, 'color' => 'bg-amber-500', 'text' => 'In Progress'],
                            'closed' => ['count' => $issues_by_status['closed'] ?? 0, 'color' => 'bg-green-500', 'text' => 'Closed']
                        ];
                        $totalIssues = array_sum(array_column($statusData, 'count'));
                    @endphp
                    @foreach($statusData as $status)
                        @if($totalIssues > 0)
                            <div>
                                <div class="flex justify-between mb-1 text-sm font-medium text-gray-700">
                                    <span>{{ $status['text'] }}</span>
                                    <span>{{ $status['count'] }} ({{ round(($status['count'] / $totalIssues) * 100) }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="{{ $status['color'] }} h-2.5 rounded-full" style="width: {{ ($status['count'] / $totalIssues) * 100 }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Priority -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle text-red-600"></i> Issues Priority Overview
                </h2>
                <div class="space-y-4">
                    @php
                        $priorityData = [
                            'high' => ['count' => $issues_by_priority['high'] ?? 0, 'color' => 'bg-red-500', 'text' => 'High Priority'],
                            'medium' => ['count' => $issues_by_priority['medium'] ?? 0, 'color' => 'bg-orange-500', 'text' => 'Medium Priority'],
                            'low' => ['count' => $issues_by_priority['low'] ?? 0, 'color' => 'bg-gray-500', 'text' => 'Low Priority']
                        ];
                    @endphp
                    @foreach($priorityData as $priority)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="{{ $priority['color'] }} w-3 h-3 rounded-full"></span>
                                <span class="text-gray-700 font-medium">{{ $priority['text'] }}</span>
                            </div>
                            <span class="text-gray-900 font-bold">{{ $priority['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
@endsection
