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
                    <th>Posisi</th>
                    <th>Email</th>
                    <th>Cuti</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->uuid }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->divisi_name }}</td>
                        <td>{{ $user->posisi_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->cuti }} Days</td>
                        <td>{{ $user->join_date }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}"><i class="fa fa-pencil"></i></a> |
                            <a href="#" onclick="event.preventDefault();deleteItem('#deleteUser-{{ $user->id }}', '{{ $user->name }}')"><i class="fa fa-trash" style="color: red;"></i></a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="deleteUser-{{ $user->id }}" style="display: inline-block;">
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
            $('#usersTable').DataTable({
                "pageLength": 25,
            });
        } );
    </script>
@endsection
