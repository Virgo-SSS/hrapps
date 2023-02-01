@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('divisi.index') }}">Divisi</a></li>
<li><span>Index</span></li>
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Divisi</h3>
        </div>
        <div class="text-right p-3">
            <a href="#" class="p-3" data-toggle="modal" data-target="#createDivisi">
                <i class="fa fa-plus " style="font-size: 25px;color: cornflowerblue"></i>
            </a>
        </div>
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped" id="usersTable">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Edited By</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($divisis as $divisi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ strtoupper($divisi->name) }}</td>
                            <td>{{ ($divisi->is_active) ? 'Active' : 'Not Active' }}</td>
                            <td>{{ $divisi->createdBy->name }}</td>
                            <td>{{ $divisi->created_at }}</td>
                            <td>{{ (!is_null($divisi->editedBy)) ? $divisi->editedBy->name : '-' }}</td>
                            <td>
                                <a href="{{ route('divisi.edit', $divisi) }}" style="font-size: 17px"><i class="fa fa-pencil"></i></a> |
                                <a href="#"><i class="fa fa-trash" style="color: red;font-size: 17px" onclick="$('#delete-'+{{ $divisi->id }}).submit()"></i></a>
                                <form action="{{ route('divisi.destroy', $divisi) }}" method="POST" id="delete-{{ $divisi->id }}" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Data Kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@endsection

@section('modal')
    <div class="modal fade" id="createDivisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('divisi.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Divisi</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">

                            <input name="name" id="name" required class="form-control mb-4 input-rounded" type="text" placeholder="New Divisi">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
