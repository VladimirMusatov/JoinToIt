<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = Employee::with('company')->get();

        return view('employees', compact("employees"));
    }

    public function create()
    {
        $companies = Company::select(['id', 'name'])->get();

        return view('employees_form', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'nullable|email',
            'company_id' => 'required|exists:companies,id',
            'phone' => 'nullable|regex:/^\+?[0-9]{10,15}$/',
        ]);

        DB::beginTransaction();

        Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'company_id' => $request->company_id,
            'phone' => $request->phone,
        ]);

        try {
            DB::commit();
            return redirect()->back()->with('success', 'Employee successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while adding the employees: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $employee->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully.');
    }
}