<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CompaniesController extends Controller
{
    public function index(Request $request){

        $companies = Company::paginate(10);

        return view('companies', compact('companies'));
    }

    public function create()
    {
        $route = 'Create Company';

        return view('companies_form');
    }

    public function edit($id)
    {
        $company = Company::find($id);

        return view('edit-company', compact('company'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'email',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|dimensions:min_width=100,min_height=100',
            'website' => 'required',
        ]);

        $fullUrl = null;

        if($request->logo)
        {
            $path = $request->file('logo')->store('logos', 'public');
            $fullUrl = asset('storage/' . $path);
        }

        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo_src' => $fullUrl,
            'website' => $request->website,
        ]);

        return redirect()->back();
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'email',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|dimensions:min_width=100,min_height=100',
            'website' => 'required',
        ]);

        $company = Company::find($id);

        if (!$company) {
            return redirect()->back()->withErrors(['Company not found']);
        }

        if($request->logo)
        {
            $old_logo_url = $company->logo_src;

            $baseUrl = URL::to('/');

            $path = Str::replaceFirst($baseUrl . '/storage/', public_path('storage/'), $old_logo_url);

            if (file_exists($path)) {
                unlink($path);
            }

            $newPath = $request->file('logo')->store('logos', 'public');
            $company->logo_src = asset('storage/' . $newPath);
    
        }

        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->save();

        return redirect()->back()->with('success', 'Company successfully updated');

    }

    public function delete($id)
    {
        DB::beginTransaction();
    
        try {
            $company = Company::find($id);
    
            if (!$company) {
                return redirect()->back()->with('error', 'Company not found');
            }

            $logo_src = $company->logo_src;

            $company->delete();
    
            $baseUrl = URL::to('/');
    
            $path = Str::replaceFirst($baseUrl . '/storage/', public_path('storage/'), $logo_src);
    
            if (file_exists($path)) {
                unlink($path);
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Company successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting the company: ' . $e->getMessage()]);
        }
    }
}
