<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistikat e përgjithshme
        $stats = [
            'total_projects' => Project::count(),
            'total_issues' => Issue::count(),
            'open_issues' => Issue::where('status', 'open')->count(),
            'in_progress_issues' => Issue::where('status', 'in_progress')->count(),
        ];

        // Projekti i fundit i krijuar
        $recent_projects = Project::withCount('issues')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Issue-t e fundit
        $recent_issues = Issue::with(['project', 'tags', 'assignedUsers'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Issue-t sipas statusit (për grafik)
        $issues_by_status = Issue::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Issue-t sipas prioritetit (për grafik)
        $issues_by_priority = Issue::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        // Projekti me më shumë issue
        $projects_with_most_issues = Project::withCount('issues')
            ->orderBy('issues_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recent_projects',
            'recent_issues',
            'issues_by_status',
            'issues_by_priority',
            'projects_with_most_issues'
        ));
    }
}