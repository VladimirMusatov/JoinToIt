<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewCompanyNotification;


class CompaniesController extends Controller
{
    public function index(){

        return view('companies');
    }

    public function getData(Request $request)
    {
        $draw = $request->draw;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search['value'];
        $orderColumnIndex = $request->order[0]['column']; 
        $orderDirection = $request->order[0]['dir'];

        $columns = [
            'id', 'name', 'email', 'logo_src', 'website'
        ];

        $query = Company::query();

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('website', 'like', "%$search%");
            });
        }

        $query->orderBy($columns[$orderColumnIndex], $orderDirection);

        $recordsTotal = Company::count();

        $companies = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $query->count(),
            'data' => $companies,
        ]);
    }

    public function create()
    {
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

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo_src' => $fullUrl,
            'website' => $request->website,
        ]);
        
        try {

            $to_mail = env('TO_MAIL');

            Mail::to($to_mail)->send(new NewCompanyNotification($company));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while sending the email: ' . $e->getMessage()]);
        }
    
        return redirect()->back()->with('success', 'Company successfully created and email sent.');
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
