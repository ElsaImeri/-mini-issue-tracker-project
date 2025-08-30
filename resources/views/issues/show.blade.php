@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
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

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-800">
            <span class="font-medium">Project:</span> 
            <a href="{{ route('projects.show', $issue->project) }}" class="text-blue-600 hover:text-blue-800 underline">
                {{ $issue->project->name }}
            </a>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
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

            <div class="bg-white shadow-md rounded-lg p-6" id="comments-section">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>
                
                <form id="comment-form" class="mb-6">
                    @csrf
                    <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                    <div class="mb-3">
                        <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label>
                        <input type="text" name="author_name" id="author_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p id="author_name_error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Comment *</label>
                        <textarea name="body" id="body" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Add your comment here..."></textarea>
                        <p id="body_error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        Add Comment
                    </button>
                </form>

                <div id="comments-container">
                    <div class="space-y-4" id="comments-list">
                        <!-- Comments will be loaded here via JavaScript -->
                    </div>
                    
                    <div id="comments-loading" class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <p class="mt-2 text-gray-500">Loading comments...</p>
                    </div>
                </div>

                <div id="load-more-container" class="mt-4 hidden">
                    <button id="load-more-comments" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition duration-200">
                        Load More Comments
                    </button>
                </div>

                <div id="no-comments" class="hidden text-center py-8">
                    <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Assigned Users</h2>
                    <button id="assign-user-btn" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Manage Users
                    </button>
                </div>
                <div id="assigned-users-container">
                    @if($issue->assignedUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($issue->assignedUsers as $user)
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No users assigned.</p>
                    @endif
                </div>
            </div>

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

<!-- Tags Modal -->
<div id="tags-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Manage Tags</h3>
            
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Available Tags</h4>
                <div id="available-tags" class="flex flex-wrap gap-2">
                    @foreach($allTags as $tag)
                        <button type="button" 
                                data-tag-id="{{ $tag->id }}"
                                class="tag-toggle-btn px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 
                                       {{ $issue->tags->contains($tag->id) ? 'ring-2 ring-blue-500 ring-offset-2' : 'opacity-70' }}"
                                style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}; border: 1px solid {{ $tag->color }}40;">
                            {{ $tag->name }}
                        </button>
                    @endforeach
                </div>
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

<!-- Assign User Modal -->
<div id="assign-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Assign Users</h3>
            
            <div class="mb-4">
                <label for="user-search" class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                <input type="text" id="user-search" placeholder="Search by name or email..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="max-h-60 overflow-y-auto mb-4">
                <div id="users-list" class="space-y-2">
                    @foreach($users as $user)
                        <div class="user-item flex items-center p-2 rounded-md hover:bg-gray-100 cursor-pointer 
                                    {{ $issue->assignedUsers->contains($user->id) ? 'bg-blue-50 border border-blue-200' : '' }}"
                             data-user-id="{{ $user->id }}">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button id="close-assign-user-modal" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">
                    Cancel
                </button>
                <button id="save-assignment-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition duration-200">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const issueId = {{ $issue->id }};
    
    // Tags Modal Functionality
    const tagsModal = document.getElementById('tags-modal');
    const manageTagsBtn = document.getElementById('manage-tags-btn');
    const closeTagsModal = document.getElementById('close-tags-modal');
    const saveTagsBtn = document.getElementById('save-tags-btn');
    const tagsContainer = document.getElementById('tags-container');

    let selectedTags = new Set(@json($issue->tags->pluck('id')));

    document.getElementById('available-tags').addEventListener('click', function(e) {
        if (e.target.classList.contains('tag-toggle-btn')) {
            const tagId = e.target.dataset.tagId;
            if (selectedTags.has(parseInt(tagId))) {
                selectedTags.delete(parseInt(tagId));
                e.target.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                e.target.classList.add('opacity-70');
            } else {
                selectedTags.add(parseInt(tagId));
                e.target.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                e.target.classList.remove('opacity-70');
            }
        }
    });

    manageTagsBtn.addEventListener('click', function() {
        tagsModal.classList.remove('hidden');
    });

    closeTagsModal.addEventListener('click', function() {
        tagsModal.classList.add('hidden');
    });

    saveTagsBtn.addEventListener('click', function() {
        fetch(`/issues/${issueId}/update-tags`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                tags: Array.from(selectedTags)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tagsContainer.innerHTML = data.tags_html;
                tagsModal.classList.add('hidden');
                showTempMessage('Tags updated successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to update tags');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showTempMessage('An error occurred. Please try again.', 'error');
        });
    });

    // Assign User Modal Functionality
    const assignUserModal = document.getElementById('assign-user-modal');
    const assignUserBtn = document.getElementById('assign-user-btn');
    const closeAssignUserModal = document.getElementById('close-assign-user-modal');
    const saveAssignmentBtn = document.getElementById('save-assignment-btn');
    const assignedUsersContainer = document.getElementById('assigned-users-container');
    const userSearch = document.getElementById('user-search');
    const usersList = document.getElementById('users-list');

    let selectedUserIds = new Set(@json($issue->assignedUsers->pluck('id')));

    userSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const userItems = usersList.querySelectorAll('.user-item');
        
        userItems.forEach(item => {
            const userName = item.querySelector('p:first-child').textContent.toLowerCase();
            const userEmail = item.querySelector('p:last-child').textContent.toLowerCase();
            
            if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });

    usersList.addEventListener('click', function(e) {
        const userItem = e.target.closest('.user-item');
        if (userItem) {
            const userId = parseInt(userItem.dataset.userId);
            
            if (selectedUserIds.has(userId)) {
                selectedUserIds.delete(userId);
                userItem.classList.remove('bg-blue-50', 'border', 'border-blue-200');
            } else {
                selectedUserIds.add(userId);
                userItem.classList.add('bg-blue-50', 'border', 'border-blue-200');
            }
        }
    });

    assignUserBtn.addEventListener('click', function() {
        assignUserModal.classList.remove('hidden');
    });

    closeAssignUserModal.addEventListener('click', function() {
        assignUserModal.classList.add('hidden');
    });

    saveAssignmentBtn.addEventListener('click', function() {
        fetch(`/issues/${issueId}/update-assigned-users`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                assigned_users: Array.from(selectedUserIds)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                assignedUsersContainer.innerHTML = data.assigned_users_html;
                assignUserModal.classList.add('hidden');
                showTempMessage('User assignment updated successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to update user assignment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showTempMessage('An error occurred. Please try again.', 'error');
        });
    });

    // Comments Functionality
    const commentsList = document.getElementById('comments-list');
    const commentsLoading = document.getElementById('comments-loading');
    const loadMoreContainer = document.getElementById('load-more-container');
    const loadMoreButton = document.getElementById('load-more-comments');
    const noComments = document.getElementById('no-comments');
    let currentPage = 1;
    let isLoading = false;

    loadComments();

    loadMoreButton.addEventListener('click', function() {
        currentPage++;
        loadComments();
    });

    function loadComments() {
        if (isLoading) return;
        
        isLoading = true;
        commentsLoading.classList.remove('hidden');
        loadMoreContainer.classList.add('hidden');

        fetch(`/issues/${issueId}/comments?page=${currentPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            commentsLoading.classList.add('hidden');

            if (data.success) {
                if (currentPage === 1 && data.comments.data.length === 0) {
                    noComments.classList.remove('hidden');
                    return;
                }

                if (currentPage === 1) {
                    commentsList.innerHTML = data.html;
                } else {
                    commentsList.innerHTML += data.html;
                }

                if (data.has_more) {
                    loadMoreContainer.classList.remove('hidden');
                }
            } else {
                throw new Error(data.message || 'Failed to load comments');
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            commentsLoading.classList.add('hidden');
            showTempMessage('Failed to load comments. Please try again.', 'error');
        })
        .finally(() => {
            isLoading = false;
        });
    }

    // Comment Form Submission
    const commentForm = document.getElementById('comment-form');
    const authorNameError = document.getElementById('author_name_error');
    const bodyError = document.getElementById('body_error');

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/issues/${issueId}/comments`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
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
            } else if (data.success) {
                const newComment = createCommentElement(data.comment);
                commentsList.insertBefore(newComment, commentsList.firstChild);
                
                commentForm.reset();
                authorNameError.classList.add('hidden');
                bodyError.classList.add('hidden');
                noComments.classList.add('hidden');

                showTempMessage('Comment added successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to add comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showTempMessage('An error occurred. Please try again.', 'error');
        });
    });

    function createCommentElement(comment) {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'border-b border-gray-200 pb-4 last:border-b-0';
        commentDiv.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-medium text-gray-900">${comment.author_name}</h4>
                <span class="text-sm text-gray-500">${new Date(comment.created_at).toLocaleString()}</span>
            </div>
            <p class="text-gray-700">${comment.body}</p>
        `;
        return commentDiv;
    }

    function showTempMessage(message, type) {
        const existingMessages = document.querySelectorAll('.temp-message');
        existingMessages.forEach(msg => msg.remove());
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `temp-message fixed top-4 right-4 px-4 py-2 rounded-md shadow-md z-50 ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'
        }`;
        messageDiv.textContent = message;
        
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }
});
</script>
@endsection