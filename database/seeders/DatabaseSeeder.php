<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 4 companies, each with 3 employees
        Company::factory()->count(4)->create()->each(function ($company) {
            $company->employees()->saveMany(
                Employee::factory()->count(3)->make()
            );
        });

    }
}
