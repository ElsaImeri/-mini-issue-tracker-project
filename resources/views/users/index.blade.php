@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            <p class="text-gray-600 mt-2">Manage all system users in one place</p>
        </div>
        
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('users.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition duration-200 shadow-md">
            <i class="bi bi-person-plus-fill mr-2"></i>
            Add User
        </a>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div id="success-message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i class="bi bi-check-circle-fill text-green-500 mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" onclick="dismissMessage()" class="text-green-700 hover:text-green-900">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- Users Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center"><i class="bi bi-hash mr-2"></i>ID</div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center"><i class="bi bi-person-fill mr-2"></i>Name</div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center"><i class="bi bi-envelope-fill mr-2"></i>Email</div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center"><i class="bi bi-award-fill mr-2"></i>Role</div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center"><i class="bi bi-calendar-event mr-2"></i>Registered At</div>
                        </th>
                        @if(Auth::user()->role === 'admin')
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center"><i class="bi bi-gear mr-2"></i>Actions</div>
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-5 font-semibold text-gray-700">{{ $user->id }}</td>
                            <td class="px-6 py-5">{{ $user->name }}</td>
                            <td class="px-6 py-5">{{ $user->email }}</td>
                            <td class="px-6 py-5">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-5">{{ $user->created_at->format('M d, Y') }}</td>
                            @if(Auth::user()->role === 'admin')
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('users.show', $user->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition"
                                       title="View User">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('users.edit', $user->id) }}" 
                                       class="text-green-600 hover:text-green-800 p-2 rounded-full hover:bg-green-100 transition"
                                       title="Edit User">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition"
                                            onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            title="Delete User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'admin' ? '6' : '5' }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-people text-4xl mb-3"></i>
                                    <p class="text-lg">No users found.</p>
                                    @if(Auth::user()->role === 'admin')
                                    <p class="mt-2">Start by <a href="{{ route('users.create') }}" class="text-blue-600 hover:text-blue-800">adding a new user</a>.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6 bg-white px-5 py-3 rounded-lg shadow-sm border border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@if(Auth::user()->role === 'admin')
<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Confirm Deletion</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to delete user "<span id="user-name" class="font-medium"></span>"? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <button type="button" id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Delete User</button>
        </div>
    </div>
</div>
@endif

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<script>
    // Auto-dismiss success message
    setTimeout(() => {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }
    }, 5000);

    function dismissMessage() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }
    }

    @if(Auth::user()->role === 'admin')
    let currentUserId = null;

    function confirmDelete(userId, userName) {
        currentUserId = userId;
        document.getElementById('user-name').textContent = userName;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        currentUserId = null;
    }

    document.getElementById('confirm-delete').addEventListener('click', function() {
        if (currentUserId) {
            document.getElementById('delete-form-' + currentUserId).submit();
        }
    });

    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    @endif
</script>
@endsection