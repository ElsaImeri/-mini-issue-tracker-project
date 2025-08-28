<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['open', 'in_progress', 'closed'];
        $priorities = ['low', 'medium', 'high'];
        
        $issueTitles = [
            'Login page not loading correctly',
            'Shopping cart calculation error',
            'Mobile responsive layout broken',
            'Database connection timeout',
            'Email notifications not sending',
            'Image upload functionality failing',
            'Search results not accurate',
            'Checkout process too slow',
            'User profile not updating',
            'Password reset not working',
            'API endpoint returning 500 error',
            'Date filter not functioning properly',
            'Report generation takes too long',
            'Language translation missing',
            'Button click not triggering action'
        ];

        $issueDescriptions = [
            'Users are experiencing issues when trying to access the login page. The page loads partially and then freezes, preventing successful authentication.',
            'The shopping cart is incorrectly calculating totals when multiple items are added. Discounts and taxes are not being applied properly.',
            'On mobile devices, the layout breaks and content overlaps. This affects usability and creates a poor user experience.',
            'Database connections are timing out during peak usage hours, causing service interruptions and data retrieval failures.',
            'Email notifications are not being sent to users for important account activities such as registration confirmations and password resets.',
            'The image upload functionality fails with large files or specific image formats. Users receive error messages when trying to upload content.',
            'Search results are not returning relevant content. The algorithm seems to be malfunctioning and not properly indexing new items.',
            'The checkout process is taking too long to complete, causing frustration for customers and potentially leading to abandoned carts.',
            'User profile information is not updating correctly. Changes made by users are not being saved or reflected in the system.',
            'The password reset functionality is not working. Users are not receiving reset emails or the reset links are expired.',
            'The API endpoint is returning 500 internal server errors when processing specific requests, affecting integration with third-party services.',
            'The date filter is not functioning properly in reports and analytics. Results are not filtered correctly based on selected date ranges.',
            'Report generation is taking an excessive amount of time, sometimes timing out and preventing users from accessing important business data.',
            'Several language translations are missing from the interface, causing parts of the application to display in the default language instead of the user selected language.',
            'Button clicks are not triggering the expected actions in certain parts of the application. This appears to be a JavaScript compatibility issue.'
        ];
        
        return [
            'title' => $this->faker->randomElement($issueTitles),
            'description' => $this->faker->randomElement($issueDescriptions),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+2 months'),
        ];
    }
}