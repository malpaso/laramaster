<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache the total number of companies and employees for 60 seconds (Normally would be longer in production)
        $totalCompanies = Cache::remember('dashboard.total_companies', 60, fn() => Company::count());
        $totalEmployees = Cache::remember('dashboard.total_employees', 60, fn() => Employee::count());

        return Inertia::render('Dashboard', [
            'totalCompanies' => $totalCompanies,
            'totalEmployees' => $totalEmployees,
        ]);

    }
}
