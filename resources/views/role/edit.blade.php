@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('role.index') }}">Role</a></li>
<li><span>Edit</span></li>
@endsection

@section('content')
    <form action="{{ route('role.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-6 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Roles</h4>
                        <input type="text" id="name" name="name" value="{{ $role->name }}" class="form-control" placeholder="New Roles" required>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Create</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-5" id="permissionTable">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Permission <span id="roles-name"></span></h4>
                        <div class="single-table">
                            <div class="table-responsive">
                                <table class="table table-striped text-center">
                                    <thead class="text-uppercase">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">No</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <th>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="permission[{{ $permission->id }}]" id="customCheck{{ $permission->id }}"
                                                            @checked($role->hasPermissionTo($permission->name))>
                                                        <label class="custom-control-label" for="customCheck{{ $permission->id }}"></label>
                                                    </div>
                                                </th>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->created_at }}</td>
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
    </form>
@endsection
