@extends('layouts.app')

@section('title')
    <li><a href="{{ route('users.index') }}">Employee</a></li>
    <li><span>Index</span></li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Employee</h3>
    </div>
    <div class="card-body " >
        <div class="data-tables">
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
                {{--                @foreach ($users as $user)--}}
                {{--                    <tr>--}}
                {{--                        <td>{{ $loop->iteration }}</td>--}}
                {{--                        <td>{{ $user->uuid }}</td>--}}
                {{--                        <td>{{ $user->name }}</td>--}}
                {{--                        <td>{{ $user->divisi_name }}</td>--}}
                {{--                        <td>{{ $user->posisi_name }}</td>--}}
                {{--                        <td>{{ $user->email }}</td>--}}
                {{--                        <td>{{ $user->cuti }} Days</td>--}}
                {{--                        <td>{{ $user->join_date }}</td>--}}
                {{--                        <td>--}}
                {{--                            <a href="{{ route('users.edit', $user->id) }}"><i class="fa fa-pencil"></i></a> |--}}
                {{--                            <a href="#" onclick="event.preventDefault();deleteItem('#deleteUser-{{ $user->id }}', '{{ $user->name }}')"><i class="fa fa-trash" style="color: red;"></i></a>--}}
                {{--                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="deleteUser-{{ $user->id }}" style="display: inline-block;">--}}
                {{--                                @csrf--}}
                {{--                                @method('DELETE')--}}
                {{--                            </form>--}}
                {{--                        </td>--}}
                {{--                    </tr>--}}
                {{--                @endforeach--}}
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready( function () {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.data.json') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'divisi_name',
                        name: 'divisi_name'
                    },
                    {
                        data: 'posisi_name',
                        name: 'posisi_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'cuti',
                        name: 'cuti'
                    },
                    {
                        data: 'join_date',
                        name: 'join_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ],
                pageLength: 35,
            });
        } );
    </script>
@endsection
