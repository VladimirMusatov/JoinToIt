@extends('main_layout')

@section('title')
    Companies
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-2">
        <a href="{{ route('create-companies') }}">
            <button type="button" class="btn btn-block btn-primary">Create Company</button>
        </a>
    </div>
</div>

<div class="col-md-12 mt-3 mx-auto">
    <div class="card-body p-0">
        <table class="table" id="example">
            <thead>
                <tr>
                    <th style="width: 15px">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Website</th>
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
            "url": "{{ route('companies-data') }}", 
            "type": "GET", 
            "dataSrc": function (json) {
                console.log(json);
                return json.data;
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "email" },
            { "data": "logo_src", "render": function(data, type, row) {
                return '<img src="' + data + '" alt="logo" style="max-width: 100%; max-height: 100%;">';
            }},
            { "data": "website" },
            { "data": null, "render": function(data, type, row) {
                return '<a href="/edit_company/' + row.id + '"><button class="btn btn-warning">Edit</button></a>';
            }},
            { "data": null, "render": function(data, type, row) {
                return '<a href="/delete_company/' + row.id + '"><button class="btn btn-danger">Delete</button></a>';
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

