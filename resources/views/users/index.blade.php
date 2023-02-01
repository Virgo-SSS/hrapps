@extends('layouts.app')

@section('title')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li><span>Index</span></li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Users</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="usersTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>UUID</th>
                    <th>Name</th>
                    <th>Divisi</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->uuid }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ ($user->divisi) ? $user->divisi->name : '-' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <a href="#" class="#"><i class="fa fa-pencil"></i></a> |
                            <a href="#"><i class="fa fa-trash" style="color: red;"></i></a>
                            <form action="#" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready( function () {
            $('#usersTable').DataTable();
        } );
    </script>
@endsection
