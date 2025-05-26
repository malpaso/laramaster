<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->withoutVite();
});
test('it can render the index page', function () {
    $response = $this->get('/companies');

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Companies/Index')
        ->has('companies')
    );
});
test('it can render the create page', function () {
    $response = $this->get('/companies/create');

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Companies/Create')
    );
});
test('it can render the edit page', function () {
    $company = Company::factory()->create();

    $response = $this->get("/companies/{$company->id}/edit");

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Companies/Edit')
        ->has('company')
    );
});
test('it can store a new company', function () {
    $data = [
        'name' => 'Test Company',
        'email' => 'company@example.com',
        'abn' => '12345678901',
        'address' => '123 Test St'
    ];
    $response = $this->post('/companies', $data);
    $response->assertRedirect('/companies');
    $this->assertDatabaseHas('companies', [
        'name' => 'Test Company',
        'email' => 'company@example.com',
        'abn' => '12345678901',
        'address' => '123 Test St'
    ]);
});
test('it can update a company', function () {
    $company = Company::factory()->create();
    $data = [
        'name' => 'Updated Company',
        'email' => 'company@example.com',
        'abn' => '12345678901',
        'address' => '123 Updated St'
    ];
    $response = $this->put("/companies/{$company->id}", $data);
    $response->assertRedirect();
    $this->assertDatabaseHas('companies', [
        'id' => $company->id,
        'name' => 'Updated Company',
        'email' => 'company@example.com',
        'abn' => '12345678901',
        'address' => '123 Updated St'
    ]);
});
