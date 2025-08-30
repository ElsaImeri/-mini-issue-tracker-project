<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $projectNames = [
            'E-Commerce Platform Development',
            'Mobile App Redesign',
            'Website Performance Optimization',
            'CRM System Implementation',
            'API Integration Project',
            'Database Migration Initiative',
            'Cloud Infrastructure Setup',
            'Security Enhancement Project',
            'UI/UX Improvement Initiative',
            'Payment Gateway Integration'
        ];

        $projectDescriptions = [
            'Development of a complete e-commerce platform with product management, shopping cart, and payment processing functionality.',
            'Complete redesign of the mobile application to improve user experience and add new features requested by customers.',
            'Optimization of website performance through code refactoring, image compression, and implementation of caching strategies.',
            'Implementation of a Customer Relationship Management system to streamline sales and customer support processes.',
            'Integration of third-party APIs to extend platform functionality and improve interoperability with other systems.',
            'Migration of legacy database to new infrastructure with improved scalability and performance characteristics.',
            'Setup of cloud infrastructure to enhance scalability, reliability, and reduce maintenance overhead.',
            'Comprehensive security audit and implementation of enhanced security measures to protect user data.',
            'User interface and experience improvements based on customer feedback and usability testing results.',
            'Integration of multiple payment gateways to provide customers with flexible payment options.'
        ];

        return [
            'name' => $this->faker->randomElement($projectNames),
            'description' => $this->faker->randomElement($projectDescriptions),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'deadline' => $this->faker->dateTimeBetween('+2 months', '+6 months'),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
        ];
    }
}