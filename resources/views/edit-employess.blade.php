@extends('main_layout')

@section('title')
    Edit Employee
@endsection

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-6 mt-3 mx-auto">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Employee</h3>
            </div>

            <form method="POST" action="{{ route('update-employee', $employee->id) }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" 
                               value="{{ old('first_name', $employee->first_name) }}" 
                               placeholder="Enter first name">
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" 
                               value="{{ old('last_name', $employee->last_name) }}" 
                               placeholder="Enter last name">
                    </div>

                    <div class="form-group">
                        <label>Company</label>
                        <select name="company_id" class="form-control">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email', $employee->email) }}" 
                               placeholder="Enter email">
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" 
                               value="{{ old('phone', $employee->phone) }}" 
                               placeholder="Enter phone number">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
