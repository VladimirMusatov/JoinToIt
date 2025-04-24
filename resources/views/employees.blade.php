@extends('main_layout')

@section('title')
    Employees
@endsection

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-2">
        <a href="{{ route('create-employee') }}">
         <button type="button" class="btn btn-block btn-primary">Create Employee</button>
        </a>
    </div>
</div>

<div class="col-md-12 mt-3 mx-auto">
    <div class="card-body p-0">
        <table class="table" id="example">
            <thead>
                <tr>
                    <th style="width: 15px">#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Company Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->first_name }}</td>
                        <td>{{ $item->last_name }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->email}}</td>
                        <td>{{$item->company->name}}</td>
                        <td><a href="{{ route('edit-employee', ['id' => $item->id]) }}"><button class="btn btn-warning">Edit</button></a></td>
                        <td><a href="{{ route('delete_employee', ['id' => $item->id]) }}"><button class="btn btn-danger">Delete</button></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection