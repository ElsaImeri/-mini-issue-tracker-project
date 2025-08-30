<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class ProjectController extends Controller
{
    use AuthorizesRequests; 

    public function index(): View
    {
        $projects = Project::withCount('issues')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        
        $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
        $validated['deadline'] = Carbon::parse($validated['deadline'])->format('Y-m-d');
        
        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $project->load(['issues' => function ($query) {
            $query->withCount('comments')
                  ->orderBy('created_at', 'desc');
        }, 'issues.tags']);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        
        $validated = $request->validated();
        
        $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
        $validated['deadline'] = Carbon::parse($validated['deadline'])->format('Y-m-d');
        
        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}