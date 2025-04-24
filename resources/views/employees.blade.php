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
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "processing": true,        
                "serverSide": true,       
                "ajax": {
                    "url": "{{ route('employees-data') }}",
                    "type": "GET", 
                    "dataSrc": function (json) {
                        console.log(json);
                        return json.data;
                    }
                },
                "columns": [
                    { "data": "id" }, 
                    { "data": "first_name" },
                    { "data": "last_name" },
                    { "data": "phone" },
                    { "data": "email" },
                    { "data": "company.name" },
                    { "data": null, "render": function(data, type, row) {
                        return '<a href="/edit_employee/' + row.id + '"><button class="btn btn-warning">Edit</button></a>';
                    }},
                    { "data": null, "render": function(data, type, row) {
                        return '<a href="/delete_employee/' + row.id + '"><button class="btn btn-danger">Delete</button></a>';
                    }},
                ],
                "pageLength": 10,
                "lengthChange": false,
                "ordering": true,
                "searching": true,
            });
        });
    </script>
@endsection
