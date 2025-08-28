<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $issues = Issue::all();
        
        foreach ($issues as $issue) {
            Comment::factory()
                ->count(rand(1, 5))
                ->for($issue)
                ->create();
        }
    }
}