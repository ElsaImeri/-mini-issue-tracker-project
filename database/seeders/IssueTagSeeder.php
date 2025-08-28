<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class IssueTagSeeder extends Seeder
{
    public function run(): void
    {
        $issues = Issue::all();
        $tags = Tag::all();
        
        foreach ($issues as $issue) {
            // Attach 1-3 random tags to each issue
            $randomTags = $tags->random(rand(1, 3))->pluck('id')->toArray();
            $issue->tags()->attach($randomTags);
        }
    }
}