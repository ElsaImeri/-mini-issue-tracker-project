@extends('layouts.app')

@section('content')
    <!-- Full-width wrapper -->
    <div class="w-full px-4 md:px-8 lg:px-12">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 w-full">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow">
                        <i class="bi bi-speedometer2 text-white"></i>
                    </div>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700">
                        Dashboard Overview
                    </span>
                </h1>
                <p class="text-gray-600 mt-2 ml-1">Welcome back! Here's what's happening with your projects today.</p>
            </div>
            <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
                <a href="{{ route('projects.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-5 py-3 rounded-xl transition-all duration-300 flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-plus-circle mr-2"></i> New Project
                </a>
                <a href="{{ route('issues.create') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-5 py-3 rounded-xl transition-all duration-300 flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-plus-circle mr-2"></i> New Issue
                </a>
            </div>
        </div>

        <!-- Quick Stats Cards (Bigger version) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 w-full">
            @php
                $statsData = [
                    ['title' => 'Total Projects', 'value' => $stats['total_projects'], 'icon' => 'bi-kanban', 'bg' => 'from-blue-50 to-blue-100', 'iconBg' => 'bg-blue-500', 'text' => 'text-blue-700', 'change' => '+5 from last week', 'changeColor' => 'text-blue-600'],
                    ['title' => 'Total Issues', 'value' => $stats['total_issues'], 'icon' => 'bi-list-task', 'bg' => 'from-purple-50 to-purple-100', 'iconBg' => 'bg-purple-500', 'text' => 'text-purple-700', 'change' => '+12 from last week', 'changeColor' => 'text-purple-600'],
                    ['title' => 'Open Issues', 'value' => $stats['open_issues'], 'icon' => 'bi-circle', 'bg' => 'from-amber-50 to-amber-100', 'iconBg' => 'bg-amber-500', 'text' => 'text-amber-700', 'change' => '-3 from last week', 'changeColor' => 'text-amber-600'],
                    ['title' => 'In Progress', 'value' => $stats['in_progress_issues'], 'icon' => 'bi-arrow-repeat', 'bg' => 'from-orange-50 to-orange-100', 'iconBg' => 'bg-orange-500', 'text' => 'text-orange-700', 'change' => '+7 from last week', 'changeColor' => 'text-orange-600'],
                ];
            @endphp
            @foreach($statsData as $card)
                <div class="bg-gradient-to-b {{ $card['bg'] }} rounded-2xl shadow-lg p-10 border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 w-full h-full flex flex-col justify-between">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl {{ $card['iconBg'] }} text-white mr-5 flex items-center justify-center text-3xl shadow-md">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-600">{{ $card['title'] }}</p>
                            <h3 class="text-5xl font-extrabold text-gray-900 mt-2">{{ $card['value'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <span class="text-base font-medium {{ $card['changeColor'] }} flex items-center">
                            <i class="bi bi-arrow-up-short mr-1 text-xl"></i> {{ $card['change'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Projects & Issues -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10 w-full">
            <!-- Recent Projects -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg w-full">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="bi bi-clock-history text-blue-600"></i>
                        </div>
                        Recent Projects
                    </h2>
                    <a href="{{ route('projects.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition-colors">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_projects as $project)
                        <div class="px-6 py-4 hover:bg-blue-50 transition-all duration-200 flex justify-between items-center group w-full">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded-lg group-hover:bg-blue-200 transition-colors">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <div>
                                    <a href="{{ route('projects.show', $project) }}" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">{{ $project->name }}</a>
                                    <p class="text-sm text-gray-500 mt-1">{{ $project->issues_count }} issues</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $project->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400 w-full">
                            <i class="bi bi-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No projects found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Issues -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg w-full">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <i class="bi bi-list-task text-purple-600"></i>
                        </div>
                        Recent Issues
                    </h2>
                    <a href="{{ route('issues.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition-colors">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_issues as $issue)
                        <div class="px-6 py-4 hover:bg-purple-50 transition-all duration-200 flex justify-between items-start group w-full">
                            <div>
                                <a href="{{ route('issues.show', $issue) }}" class="text-gray-900 font-medium hover:text-blue-600 transition-colors block">{{ $issue->title }}</a>
                                <div class="flex items-center mt-2 flex-wrap gap-2 text-xs">
                                    <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full font-medium">{{ $issue->project->name }}</span>
                                    <span class="bg-gray-100 text-gray-800 px-2.5 py-0.5 rounded-full font-medium">{{ ucfirst($issue->status) }}</span>
                                    <span class="bg-red-100 text-red-800 px-2.5 py-0.5 rounded-full font-medium">{{ ucfirst($issue->priority) }}</span>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $issue->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400 w-full">
                            <i class="bi bi-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No issues found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Projects with Most Issues -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-10 transition-all duration-300 hover:shadow-lg w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <div class="bg-amber-100 p-2 rounded-lg">
                        <i class="bi bi-trophy text-amber-600"></i>
                    </div>
                    Projects with Most Issues
                </h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($projects_with_most_issues as $index => $project)
                    <div class="px-6 py-4 hover:bg-amber-50 transition-all duration-200 w-full">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-400 font-bold text-lg">#{{ $index + 1 }}</span>
                                <a href="{{ route('projects.show', $project) }}" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">{{ $project->name }}</a>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">{{ $project->issues_count }} issues</span>
                        </div>
                        <div class="mt-3 bg-gray-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-1000" style="width: {{ ($project->issues_count / ($projects_with_most_issues->first()->issues_count ?: 1)) * 80 + 20 }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400 w-full">
                        <i class="bi bi-inbox text-3xl mb-2 opacity-50"></i>
                        <p>No projects found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Issues Status & Priority Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 w-full">
            <!-- Status -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg w-full">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <i class="bi bi-clipboard-data text-blue-600"></i>
                    </div>
                    Issues Status Overview
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
                                    <div class="{{ $status['color'] }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ ($status['count'] / $totalIssues) * 100 }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Priority -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg w-full">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="bg-red-100 p-2 rounded-lg">
                        <i class="bi bi-exclamation-circle text-red-600"></i>
                    </div>
                    Issues Priority Overview
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
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
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
<style>
    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        min-height: 100vh;
        width: 100%;
        background: white !important;
    }
</style>
@endsection