<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Issue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $issues = Issue::all();

        foreach ($issues as $issue) {
            $usersToAssign = collect([$admin, $user])->random(rand(1, 2));
            $issue->assignedUsers()->attach($usersToAssign);
        }

        $this->command->info('Created 2 users: admin and regular user');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('User: user@example.com / password');
        $this->command->info('Assigned users to all issues');
    }
}