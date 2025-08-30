<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request): View
    {
        // Get all issues with filters
        $issues = Issue::with(['project', 'tags', 'assignedUsers'])
            ->withCount('comments')
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                return $query->where('priority', $priority);
            })
            ->when($request->tag, function ($query, $tagId) {
                return $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(15);

        $statuses = Issue::getStatuses();
        $priorities = Issue::getPriorities();
        $tags = Tag::all();
        $users = User::all();

        return view('issues.index', compact('issues', 'statuses', 'priorities', 'tags', 'users'));
    }

    public function create(): View
    {
        $projects = Project::all();
        $statuses = Issue::getStatuses();
        $priorities = Issue::getPriorities();
        $tags = Tag::all();
        $users = User::all();

        return view('issues.create', compact('projects', 'statuses', 'priorities', 'tags', 'users'));
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $issue = Issue::create($request->validated());
        
        // Sync tags if provided
        if ($request->filled('tags')) {
            $issue->tags()->sync($request->tags);
        }
        
        // Sync assigned users if provided
        if ($request->filled('assigned_users')) {
            $issue->assignedUsers()->sync($request->assigned_users);
        }

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue): View
    {
        // Eager load relationships to avoid N+1
        $issue->load(['project', 'tags', 'assignedUsers', 'comments']);

        // Get all tags for tag management modal
        $allTags = Tag::all();
        $users = User::all();

        return view('issues.show', compact('issue', 'allTags', 'users'));
    }

    public function edit(Issue $issue): View
    {
        $projects = Project::all();
        $statuses = Issue::getStatuses();
        $priorities = Issue::getPriorities();
        $allTags = Tag::all();
        $users = User::all();

        // Eager load relationships for edit form
        $issue->load(['tags', 'assignedUsers']);

        return view('issues.edit', compact('issue', 'projects', 'statuses', 'priorities', 'allTags', 'users'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());
        
        // Sync tags if provided in request
        if ($request->filled('tags')) {
            $issue->tags()->sync($request->tags);
        } else {
            $issue->tags()->detach();
        }
        
        // Sync assigned users if provided
        if ($request->filled('assigned_users')) {
            $issue->assignedUsers()->sync($request->assigned_users);
        } else {
            $issue->assignedUsers()->detach();
        }

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    /**
     * Search issues via AJAX with debounce
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:open,in_progress,closed',
            'priority' => 'nullable|in:low,medium,high',
            'tag' => 'nullable|exists:tags,id',
        ]);

        $issues = Issue::with(['project', 'tags', 'assignedUsers'])
            ->withCount('comments')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                return $query->where('priority', $priority);
            })
            ->when($request->tag, function ($query, $tagId) {
                return $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            })
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'issues' => $issues,
            'html' => view('issues.partials.search_results', compact('issues'))->render()
        ]);
    }

    /**
     * Update tags for an issue via AJAX
     */
    public function updateTags(Request $request, Issue $issue): JsonResponse
    {
        $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $issue->tags()->sync($request->tags ?? []);

        // Load fresh tags with issue
        $issue->load('tags');

        return response()->json([
            'success' => true,
            'message' => 'Tags updated successfully',
            'tags' => $issue->tags,
            'tags_html' => view('tags.partials.tags_list', ['tags' => $issue->tags])->render()
        ]);
    }

    /**
     * Update assigned users for an issue via AJAX
     */
    public function updateAssignedUsers(Request $request, Issue $issue): JsonResponse
    {
        $request->validate([
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $issue->assignedUsers()->sync($request->assigned_users ?? []);
        $issue->load('assignedUsers');

        return response()->json([
            'success' => true,
            'message' => 'Assigned users updated successfully',
            'assigned_users' => $issue->assignedUsers,
            'assigned_users_html' => view('issues.partials.assigned_users_list', ['assignedUsers' => $issue->assignedUsers])->render()
        ]);
    }

    /**
     * Get comments for an issue via AJAX (paginated)
     */
    public function getComments(Issue $issue, Request $request): JsonResponse
    {
        $comments = $issue->comments()
            ->latest()
            ->paginate(10, ['*'], 'page', $request->page);

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'html' => view('comments.partials.comments_list', compact('comments'))->render(),
            'has_more' => $comments->hasMorePages()
        ]);
    }

    /**
     * Store a new comment via AJAX
     */
    public function storeComment(StoreCommentRequest $request, Issue $issue): JsonResponse
    {
        try {
            // Create comment for the given issue
            $comment = $issue->comments()->create([
                'author_name' => $request->author_name,
                'body' => $request->body
            ]);

            // Load the comment with any necessary relationships
            $comment->load('issue');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment,
                'comment_html' => view('comments.partials.comment_item', compact('comment'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign a user to an issue via AJAX
     */
    public function assignUser(Request $request, Issue $issue): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if user is already assigned
        if (!$issue->assignedUsers()->where('user_id', $request->user_id)->exists()) {
            $issue->assignedUsers()->attach($request->user_id);
        }

        $issue->load('assignedUsers');

        return response()->json([
            'success' => true,
            'message' => 'User assigned successfully',
            'assigned_users' => $issue->assignedUsers,
            'assigned_users_html' => view('issues.partials.assigned_users_list', ['assignedUsers' => $issue->assignedUsers])->render()
        ]);
    }

    /**
     * Detach a user from an issue via AJAX
     */
    public function detachUser(Issue $issue, User $user): JsonResponse
    {
        $issue->assignedUsers()->detach($user->id);
        $issue->load('assignedUsers');

        return response()->json([
            'success' => true,
            'message' => 'User removed successfully',
            'assigned_users' => $issue->assignedUsers,
            'assigned_users_html' => view('issues.partials.assigned_users_list', ['assignedUsers' => $issue->assignedUsers])->render()
        ]);
    }

    /**
     * Get assigned users for an issue via AJAX
     */
    public function getAssignedUsers(Issue $issue): JsonResponse
    {
        $assignedUsers = $issue->assignedUsers;

        return response()->json([
            'success' => true,
            'assigned_users' => $assignedUsers,
            'assigned_users_html' => view('issues.partials.assigned_users_list', ['assignedUsers' => $assignedUsers])->render()
        ]);
    }
}