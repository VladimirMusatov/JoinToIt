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
        return view('employees');
    }

    public function create()
    {
        $companies = Company::select(['id', 'name'])->get();

        return view('employees_form', compact('companies'));
    }

    public function getData()
    {
        $draw = request('draw');
        $start = request('start');
        $length = request('length');
        $search = request('search')['value'];
        $orderColumnIndex = request('order')[0]['column'];
        $orderDirection = request('order')[0]['dir'];

        $columns = [
            'id', 'first_name', 'last_name', 'phone', 'email', 'company.name'
        ];

        $query = Employee::with('company');

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
            });
        }

        $query->orderBy($columns[$orderColumnIndex], $orderDirection);

        $recordsTotal = $query->count();

        $employees = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $employees,
        ]);
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

    public function edit($id)
    {
        $employee = Employee::find($id);
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $companies = Company::select('id', 'name')->get();

        return view('edit-employess', ['employee' => $employee, 'companies' => $companies]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'nullable|email',
            'company_id' => 'required|exists:companies,id',
            'phone' => 'nullable|regex:/^\+?[0-9]{10,15}$/',
        ]);
    
        DB::beginTransaction();
    
        try {
            $employee = Employee::findOrFail($id);
    
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->company_id = $request->company_id;
            $employee->phone = $request->phone;
    
            $employee->save();
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Employee successfully updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the employee: ' . $e->getMessage()]);
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