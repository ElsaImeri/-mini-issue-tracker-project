<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Bug', 'color' => '#FF5733'],
            ['name' => 'Feature', 'color' => '#33FF57'],
            ['name' => 'Enhancement', 'color' => '#3357FF'],
            ['name' => 'Documentation', 'color' => '#F3FF33'],
            ['name' => 'Urgent', 'color' => '#FF33F3'],
            ['name' => 'Low Priority', 'color' => '#33FFF3'],
            ['name' => 'UI/UX', 'color' => '#FF8C33'],
            ['name' => 'Backend', 'color' => '#338CFF'],
        ];
        
        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}