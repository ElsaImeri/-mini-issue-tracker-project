<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        
        foreach ($projects as $project) {
            Issue::factory()
                ->count(rand(3, 8))
                ->for($project)
                ->create();
        }
    }
}