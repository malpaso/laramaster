<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;

class EmployeeController extends Controller
{


    /**
     * Store a newly created employee in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->back()->with('success', 'Employee created successfully');
    }


    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully');
    }
}
