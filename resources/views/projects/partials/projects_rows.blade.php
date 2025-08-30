@foreach($projects as $project)
<tr class="hover:bg-blue-50 transition duration-150">
    <td class="px-4 py-4">
        <div class="flex items-start">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mt-1">
                <i class="bi bi-kanban text-blue-600"></i>
            </div>
            <div class="ml-3">
                <div class="text-sm font-semibold text-gray-900">{{ $project->name }}</div>
                <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($project->description, 100) }}</div>
            </div>
        </div>
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->issues_count > 0 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
            <i class="bi {{ $project->issues_count > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle' }} mr-1"></i>
            {{ $project->issues_count }} {{ $project->issues_count === 1 ? 'issue' : 'issues' }}
        </div>
    </td>
    <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">
        <div class="text-sm text-gray-900">
            <div class="flex items-center">
                <i class="bi bi-play-circle mr-1 text-gray-400"></i>
                {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
            </div>
        </div>
        <div class="text-sm text-gray-500 mt-1">
            <div class="flex items-center">
                <i class="bi bi-flag mr-1 text-gray-400"></i>
                {{ $project->deadline ? $project->deadline->format('M d, Y') : 'Not set' }}
            </div>
        </div>
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <div class="flex items-center space-x-2">
            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition" title="View Project">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('projects.edit', $project) }}" class="text-green-600 hover:text-green-800 p-2 rounded-full hover:bg-green-100 transition" title="Edit Project">
                <i class="bi bi-pencil-square"></i>
            </a>
            <button type="button" class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition" onclick="confirmDelete({{ $project->id }}, '{{ addslashes($project->name) }}')" title="Delete Project">
                <i class="bi bi-trash"></i>
            </button>
            <form id="delete-form-{{ $project->id }}" action="{{ route('projects.destroy', $project) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </td>
</tr>
@endforeach