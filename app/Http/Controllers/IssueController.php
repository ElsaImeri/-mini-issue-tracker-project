<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        // Eager load relacionet për të shmangur N+1
        $issue->load(['project', 'tags', 'comments' => function ($query) {
            $query->latest();
        }]);

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
}