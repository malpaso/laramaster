<?php

use App\Models\Employee;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->withoutVite();
});
test('it can store an employee', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $data = [
        'company_id' => $company->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'jd@example.com',
        'address' => '123 Main St',
    ];

    $response = $this->post('/employees', $data);

    $response->assertRedirect()
        ->assertSessionHas('success', 'Employee created successfully');

    $this->assertDatabaseHas('employees', $data);
});
test('it can delete an employee', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $employee = Employee::factory()->create([
        'company_id' => $company->id,
    ]);

    $response = $this->delete("/employees/{$employee->id}");

    $response->assertRedirect()
        ->assertSessionHas('success', 'Employee deleted successfully');

    $this->assertDatabaseMissing('employees', [
        'id' => $employee->id,
    ]);
});
