@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-kanban-fill text-blue-600"></i>
                Project Management
            </h1>
            <p class="text-gray-500 mt-2">Manage all your projects efficiently in one place</p>
        </div>
        <a href="{{ route('projects.create') }}" 
           class="mt-4 md:mt-0 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium shadow-md transition">
            <i class="bi bi-plus-circle"></i>
            Create New Project
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div id="success-message" 
             class="flex items-center justify-between bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl shadow-sm mb-8">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-green-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="dismissMessage()" class="hover:text-green-900 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- Projects Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-folder2-open"></i>
                                Project
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-exclamation-circle"></i>
                                Issues
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-calendar-event"></i>
                                Timeline
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-gear"></i>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-blue-50 transition">
                            <!-- Project -->
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <i class="bi bi-kanban text-blue-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $project->name }}</div>
                                        <div class="text-gray-500 mt-1 line-clamp-2">{{ Str::limit($project->description, 100) }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Issues -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                    {{ $project->issues_count > 0 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="bi {{ $project->issues_count > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle' }}"></i>
                                    {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                                </div>
                            </td>

                            <!-- Timeline -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1 text-gray-700">
                                        <i class="bi bi-play-circle text-gray-400"></i>
                                        {{ $project->start_date?->format('M d, Y') ?? 'Not set' }}
                                    </div>
                                    <div class="flex items-center gap-1 text-gray-500">
                                        <i class="bi bi-flag text-gray-400"></i>
                                        {{ $project->deadline?->format('M d, Y') ?? 'Not set' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="p-2 rounded-lg text-blue-600 hover:bg-blue-100 transition" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" 
                                       class="p-2 rounded-lg text-green-600 hover:bg-green-100 transition" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="confirmDelete({{ $project->id }}, '{{ addslashes($project->name) }}')" 
                                            class="p-2 rounded-lg text-red-600 hover:bg-red-100 transition" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $project->id }}" action="{{ route('projects.destroy', $project) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="bi bi-inbox text-4xl"></i>
                                    <p class="text-lg font-medium">No projects found</p>
                                    <p class="text-sm">Get started by 
                                        <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">creating a new project</a>.
                                    </p>
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
        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="bi bi-exclamation-octagon text-red-600"></i>
                Confirm Deletion
            </h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-6">
            Are you sure you want to delete project 
            "<span id="project-name" class="font-medium text-gray-900"></span>"? 
            This action cannot be undone.
        </p>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <button type="button" id="confirm-delete" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // Auto-dismiss success message
    setTimeout(() => dismissMessage(), 5000);

    function dismissMessage() {
        const msg = document.getElementById('success-message');
        if (msg) {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 300);
        }
    }

    let currentProjectId = null;

    function confirmDelete(projectId, projectName) {
        currentProjectId = projectId;
        document.getElementById('project-name').textContent = projectName;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        currentProjectId = null;
    }

    document.getElementById('confirm-delete').addEventListener('click', () => {
        if (currentProjectId) {
            document.getElementById('delete-form-' + currentProjectId).submit();
        }
    });

    document.getElementById('delete-modal').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection
