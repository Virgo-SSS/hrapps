@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('role.index') }}">Role</a></li>
<li><span>Index</span></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Roles</h4>
                    <div class="single-table">
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                <a href="#" onclick="getPermission('{{ $role->id }}', '{{ $role->name }}')">
                                                    {{ $role->name }}
                                                </a>
                                            </td>
                                            <td>{{ $role->created_at }}</td>
                                            <td>
                                                @if($role->name != 'super admin')
                                                    <a href="{{ route('role.edit', $role->id) }}">
                                                        <i class="ti-pencil" style="font-size: 17px; color: mediumblue"></i>
                                                    </a>
                                                    <span class="mr-1 ml-1">|</span>
                                                    <a href="#" onclick="deleteItem('#deleteRole-{{ $role->id }}','{{ $role->name }}')">
                                                        <i class="ti-trash" style="font-size:17px; color: red"></i>
                                                    </a>
                                                    <form action="{{ route('role.destroy', $role->id) }}" method="POST" id="deleteRole-{{ $role->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @else
                                                    <p>Can't edit or delete</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mt-5 d-none" id="permissionTable">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Permission <span id="roles-name"></span></h4>
                    <div class="single-table">
                        <div class="table-responsive">
                            <table class="table table-striped text-center">
                                <thead class="text-uppercase">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                </tr>
                                </thead>
                                <tbody>
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
        function getPermission(id, name) {
            if(name == 'super admin') {
                let text = '' ;
                text += '<tr>';
                text += '<td colspan="2">' + 'Super admin has all permission' + '</td>';
                text += '</tr>';
                $('#permissionTable tbody').html(text);
            } else {
                $.ajax({
                    url: '/role/' + id + '/permission',
                    type: 'GET',
                    success: function (data) {
                        let html = '';
                        let i = 1;
                        $.each(data, function (index, value) {
                            $.each(value, function (key, val) {
                                html += '<tr>';
                                html += '<td>' + i + '</td>';
                                html += '<td>' + val.name + '</td>';
                                html += '</tr>';
                                i++;
                            });
                        });
                        $('#permissionTable tbody').html(html);
                    }
                });
            }

            $('#roles-name').text(name);
            $('#permissionTable').removeClass('d-none');
        }
    </script>
@endsection
