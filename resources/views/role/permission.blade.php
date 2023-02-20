@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('role.index') }}">Role</a></li>
<li><span>Permission</span></li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mt-4">
        @can('permission.create')
            <div class="text-right">
                <a href="#" data-toggle="modal" data-target="#createPermission">
                    <button class="btn btn-success mb-3 mt-0" style="padding:7px 12px;">Create Permission</button>
                </a>
            </div>
        @endcan
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Permission</h4>
                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table" id="permissionTable">
                            <thead class="text-uppercase bg-success">
                            <tr class="text-white">
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Last Update</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->created_at }}</td>
                                <td>{{ $permission->updated_at }}</td>
                                <td>
                                    <a href="#" onclick="editItem(this)" data-name="{{ $permission->name }}" data-id="{{ $permission->id }}">
                                        <button class="btn btn-primary" style="padding:2px 25px;">Edit</button>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" onclick="deleteItem('#delPermission-{{ $permission->id }}', '{{ $permission->name }}')" data-id="{{ $permission->id }}">
                                        <button class="btn btn-danger" style="padding:2px 25px;" >Delete</button>
                                    </a>
                                </td>
                                <form action="{{ route('permission.destroy', $permission->id) }}" method="POST" id="delPermission-{{ $permission->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#permissionTable').DataTable().columns.adjust();
        });

        function editItem(e) {
            let name = $(e).data('name');
            let id = $(e).data('id');

            let url = "{{ route('permission.update', ':id') }}";
            url = url.replace(':id', id);

            $('#editPermission input[id="name"]').val(name);
            $('#editPermission form').attr('action', url);
            $('#editPermission').modal('show');
        }
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="createPermission">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('permission.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Permission</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input name="name" id="name" required class="form-control mb-4 input-rounded" type="text" placeholder="New Permission">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPermission">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">edit Permission</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input name="name" id="name" required class="form-control mb-4 input-rounded" type="text" placeholder="Edit Permission">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
