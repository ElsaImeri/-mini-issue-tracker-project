<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        $commentBodies = [
            'I have reproduced this issue on my local environment.',
            'This seems to be related to the recent API changes.',
            'The customer reported this problem yesterday.',
            'We need to prioritize this for the next release.',
            'I found a workaround that might help temporarily.',
            'This is a critical issue affecting multiple users.',
            'The root cause appears to be in the database query.',
            'I will need more information to debug this properly.',
            'This might be a caching issue rather than a bug.',
            'I have assigned this to the backend team for investigation.',
            'The mobile team should also look into this.',
            'This is a duplicate of issue #245.',
            'The fix has been deployed to staging environment.',
            'Please test this on the latest version of Chrome.',
            'This should be resolved after clearing browser cache.'
        ];
        
        return [
            'author_name' => $this->faker->name(),
            'body' => $this->faker->randomElement($commentBodies),
        ];
    }
}