<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request): View
    {
        // Merr të gjitha issue-t me filtra
        $issues = Issue::with(['project', 'tags', 'comments'])
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
            ->paginate(15);

        $statuses = ['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'];
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
        $tags = Tag::all();

        return view('issues.index', compact('issues', 'statuses', 'priorities', 'tags'));
    }

    public function create(): View
    {
        $projects = Project::all();
        $statuses = ['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'];
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];

        return view('issues.create', compact('projects', 'statuses', 'priorities'));
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $issue = Issue::create($request->validated());

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue): View
    {
        // Eager load relacionet për të shmangur N+1 (pa komente - do të ngarkohen me AJAX)
        $issue->load(['project', 'tags']);

        // Merr të gjitha tag-et për modal-in e menaxhimit të tag-eve
        $allTags = Tag::all();

        return view('issues.show', compact('issue', 'allTags'));
    }

    public function edit(Issue $issue): View
    {
        $projects = Project::all();
        $statuses = ['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'];
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
        $allTags = Tag::all();

        return view('issues.edit', compact('issue', 'projects', 'statuses', 'priorities', 'allTags'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());
        
        // Sync tags nëse janë pranuar në request
        if ($request->has('tags')) {
            $issue->tags()->sync($request->tags);
        } else {
            $issue->tags()->detach();
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
     * Update tags for an issue via AJAX
     */
    public function updateTags(Request $request, Issue $issue): JsonResponse
    {
        $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $issue->tags()->sync($request->tags ?? []);

        // Generate updated tags HTML for response
        $tagsHtml = '';
        foreach ($issue->fresh()->tags as $tag) {
            $tagsHtml .= '<span class="px-3 py-1 rounded-full text-xs font-medium" 
                          style="background-color: ' . $tag->color . '20; color: ' . $tag->color . '; border: 1px solid ' . $tag->color . '40;">
                          ' . $tag->name . '
                          </span>';
        }

        if ($issue->tags->count() == 0) {
            $tagsHtml = '<p class="text-gray-500 text-sm">No tags assigned.</p>';
        }

        return response()->json([
            'success' => true,
            'message' => 'Tags updated successfully',
            'tags_html' => $tagsHtml
        ]);
    }

    /**
     * Get comments for an issue via AJAX (paginated)
     */
    public function getComments(Issue $issue, Request $request): JsonResponse
    {
        $comments = $issue->comments()
            ->with('issue')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $commentsHtml = '';
        foreach ($comments as $comment) {
            $commentsHtml .= '<div class="border-b border-gray-200 pb-4 last:border-b-0">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-medium text-gray-900">' . $comment->author_name . '</h4>
                    <span class="text-sm text-gray-500">' . $comment->created_at->diffForHumans() . '</span>
                </div>
                <p class="text-gray-700">' . $comment->body . '</p>
            </div>';
        }

        return response()->json([
            'success' => true,
            'comments' => $commentsHtml,
            'has_more' => $comments->hasMorePages(),
            'next_page' => $comments->nextPageUrl()
        ]);
    }
}