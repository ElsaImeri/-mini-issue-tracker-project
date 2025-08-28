<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProjectSeeder::class,
            TagSeeder::class,
            IssueSeeder::class,
            CommentSeeder::class,
            IssueTagSeeder::class, // Shto këtë rresht
        ]);
    }
}