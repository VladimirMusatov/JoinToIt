@extends('main_layout')

@section('title')
    Edit Company
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
                <h3 class="card-title">Edit Company</h3>
            </div>
            <!-- form start -->
            <form method="POST" action="{{ route('update-company', ['id' => $company->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ old('name', $company->name) }}">
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email', $company->email) }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Logo</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="logo" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                            </div>
                        </div>
                        @if($company->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Current Logo" width="100">
                            </div>
                        @endif
                    </div>
                    <div class="form-group mt-3">
                        <label>Website</label>
                        <input type="url" name="website" class="form-control" placeholder="Enter website" value="{{ old('website', $company->website) }}">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection