@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $issue->title }}</h1>
            <div class="flex items-center mt-2 space-x-4">
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
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$issue->status] }}">
                    {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                </span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $priorityColors[$issue->priority] }}">
                    {{ ucfirst($issue->priority) }} Priority
                </span>
                @if($issue->due_date)
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                        Due: {{ $issue->due_date->format('M d, Y') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('issues.edit', $issue) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="bi bi-pencil mr-2"></i> Edit Issue
            </a>
            <a href="{{ route('issues.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Back to Issues
            </a>
        </div>
    </div>

    <!-- Project Reference -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-800">
            <span class="font-medium">Project:</span> 
            <a href="{{ route('projects.show', $issue->project) }}" class="text-blue-600 hover:text-blue-800 underline">
                {{ $issue->project->name }}
            </a>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Issue Details -->
        <div class="lg:col-span-2">
            <!-- Description Card -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                <div class="prose max-w-none text-gray-700">
                    @if($issue->description)
                        {{ $issue->description }}
                    @else
                        <p class="text-gray-500 italic">No description provided.</p>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white shadow-md rounded-lg p-6" id="comments-section">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>
                
                <!-- Comment Form -->
                <form id="comment-form" class="mb-6">
                    @csrf
                    <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                    <div class="mb-3">
                        <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                        <input type="text" name="author_name" id="author_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p id="author_name_error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                        <textarea name="body" id="body" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Add your comment here..."></textarea>
                        <p id="body_error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Add Comment
                    </button>
                </form>

                <!-- Comments List -->
                <div id="comments-container">
                    <div class="space-y-4">
                        @foreach($issue->comments as $comment)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $comment->author_name }}</h4>
                                    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700">{{ $comment->body }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination (Will be used for AJAX loading) -->
                <div id="comments-pagination" class="mt-4 hidden">
                    <button id="load-more-comments" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition duration-200">
                        Load More Comments
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column - Meta Information -->
        <div class="space-y-6">
            <!-- Tags Card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Tags</h2>
                    <button id="manage-tags-btn" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Manage Tags
                    </button>
                </div>
                <div id="tags-container" class="flex flex-wrap gap-2">
                    @foreach($issue->tags as $tag)
                        <span class="px-3 py-1 rounded-full text-xs font-medium" 
                              style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}; border: 1px solid {{ $tag->color }}40;">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                    @if($issue->tags->count() == 0)
                        <p class="text-gray-500 text-sm">No tags assigned.</p>
                    @endif
                </div>
            </div>

            <!-- Details Card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Details</h2>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Created:</span>
                        <p class="text-sm text-gray-800">{{ $issue->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Last Updated:</span>
                        <p class="text-sm text-gray-800">{{ $issue->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Project:</span>
                        <p class="text-sm text-gray-800">{{ $issue->project->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Tags Modal -->
<div id="tags-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Manage Tags</h3>
            
            <!-- Available Tags -->
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Available Tags</h4>
                <div id="available-tags" class="flex flex-wrap gap-2">
                    @foreach($allTags as $tag)
                        <button type="button" 
                                data-tag-id="{{ $tag->id }}"
                                class="tag-toggle-btn px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 
                                       {{ $issue->tags->contains($tag->id) ? 'ring-2 ring-offset-2' : 'opacity-70' }}"
                                style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}; border: 1px solid {{ $tag->color }}40;">
                            {{ $tag->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Create New Tag -->
            <div class="border-t pt-4 mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Create New Tag</h4>
                <form id="create-tag-form" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Tag name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p id="tag-name-error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <div>
                        <input type="color" name="color" value="#3b82f6"
                            class="w-full h-10 px-1 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition duration-200">
                        Create Tag
                    </button>
                </form>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button id="close-tags-modal" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">
                    Cancel
                </button>
                <button id="save-tags-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition duration-200">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for AJAX functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manage Tags Modal
    const tagsModal = document.getElementById('tags-modal');
    const manageTagsBtn = document.getElementById('manage-tags-btn');
    const closeTagsModal = document.getElementById('close-tags-modal');
    const saveTagsBtn = document.getElementById('save-tags-btn');
    const availableTags = document.getElementById('available-tags');
    const tagsContainer = document.getElementById('tags-container');
    const createTagForm = document.getElementById('create-tag-form');
    const tagNameError = document.getElementById('tag-name-error');

    let selectedTags = new Set(@json($issue->tags->pluck('id')));

    // Toggle tag selection
    availableTags.addEventListener('click', function(e) {
        if (e.target.classList.contains('tag-toggle-btn')) {
            const tagId = e.target.dataset.tagId;
            if (selectedTags.has(tagId)) {
                selectedTags.delete(tagId);
                e.target.classList.remove('ring-2', 'ring-offset-2');
                e.target.classList.add('opacity-70');
            } else {
                selectedTags.add(tagId);
                e.target.classList.add('ring-2', 'ring-offset-2');
                e.target.classList.remove('opacity-70');
            }
        }
    });

    // Open modal
    manageTagsBtn.addEventListener('click', function() {
        tagsModal.classList.remove('hidden');
    });

    // Close modal
    closeTagsModal.addEventListener('click', function() {
        tagsModal.classList.add('hidden');
    });

    // Save tags
    saveTagsBtn.addEventListener('click', function() {
        fetch('{{ route('issues.update-tags', $issue) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                tags: Array.from(selectedTags)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update tags display
                tagsContainer.innerHTML = data.tags_html;
                tagsModal.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Create new tag
    createTagForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route('tags.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                tagNameError.textContent = data.errors.name ? data.errors.name[0] : '';
                tagNameError.classList.remove('hidden');
            } else if (data.tag) {
                // Add new tag to available tags
                const newTagBtn = document.createElement('button');
                newTagBtn.type = 'button';
                newTagBtn.dataset.tagId = data.tag.id;
                newTagBtn.classList.add('tag-toggle-btn', 'px-3', 'py-1', 'rounded-full', 'text-xs', 'font-medium', 'transition-all', 'duration-200', 'opacity-70');
                newTagBtn.style.backgroundColor = data.tag.color + '20';
                newTagBtn.style.color = data.tag.color;
                newTagBtn.style.border = '1px solid ' + data.tag.color + '40';
                newTagBtn.textContent = data.tag.name;
                
                availableTags.appendChild(newTagBtn);
                createTagForm.reset();
                tagNameError.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Add comment via AJAX
    const commentForm = document.getElementById('comment-form');
    const authorNameError = document.getElementById('author_name_error');
    const bodyError = document.getElementById('body_error');

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route('comments.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                authorNameError.textContent = data.errors.author_name ? data.errors.author_name[0] : '';
                authorNameError.classList.toggle('hidden', !data.errors.author_name);
                bodyError.textContent = data.errors.body ? data.errors.body[0] : '';
                bodyError.classList.toggle('hidden', !data.errors.body);
            } else if (data.comment) {
                // Prepend new comment
                const commentsContainer = document.querySelector('#comments-container .space-y-4');
                const newComment = document.createElement('div');
                newComment.className = 'border-b border-gray-200 pb-4';
                newComment.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-gray-900">${data.comment.author_name}</h4>
                        <span class="text-sm text-gray-500">Just now</span>
                    </div>
                    <p class="text-gray-700">${data.comment.body}</p>
                `;
                
                commentsContainer.insertBefore(newComment, commentsContainer.firstChild);
                
                // Clear form
                commentForm.reset();
                authorNameError.classList.add('hidden');
                bodyError.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection