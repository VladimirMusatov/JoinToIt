@extends('main_layout')

@section('title')
    Create Employee
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
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create Employee</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{route('store-employee')}}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" value="{{ old('first_name') }}">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" value="{{ old('last_name') }}">
                    </div>
                    <div class="form-group mt-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group mt-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter Phone" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectBorder">Select Company</label>
                        <select name="company_id" class="custom-select form-control-border" id="exampleSelectBorder">
                            @foreach($companies as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
