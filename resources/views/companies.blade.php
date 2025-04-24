@extends('main_layout')

@section('title')
    Companies
@endsection

@section('content')
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
                @foreach($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->email }}</td>
                        <td style="width: 50px; height: 50px;">
                            <img src="{{ $company->logo_src }}" alt="logo" style="max-width: 100%; max-height: 100%;">
                        </td>
                        <td>{{ $company->website }}</td>
                        <td><a href="{{ route('edit_company', ['id' => $company->id]) }}"><button class="btn btn-warning">Edit</button></a></td>
                        <td><a href="{{ route('delete_company', ['id' => $company->id]) }}"><button class="btn btn-danger">Delete</button></a></td>
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

