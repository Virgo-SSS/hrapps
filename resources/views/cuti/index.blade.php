@extends('layouts.app')

@section('title')
    <li><a href="{{ route('cuti.index') }}">Cuti</a></li>
    <li><span>Index</span></li>
@endsection

@section('content')
    <div class="col-12 mt-2">
        <div class="mb-3">
            <button class="btn btn-success">
                <a href="{{ route('cuti.create') }}" style="text-decoration: none; color:white">
                    Create Request Cuti  <i class="fa fa-plus" style="color: #fbff7c"></i>
                </a>
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Progress Table</h4>
                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table table-hover progress-table text-center">
                            <thead class="text-uppercase">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">UUID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Divisi</th>
                                <th scope="col">Posisi</th>
                                <th scope="col">Date</th>
                                <th scope="col">Return</th>
                                <th scope="col">Days</th>
                                <th scope="col">Sisa Cuti</th>
                                <th scope="col">Status</th>
                                <th scope="col">Edit Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($cutis as $cuti)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $cuti->user->uuid  }}</td>
                                        <td>{{ $cuti->user->name }}</td>\
                                        <td>{{ $cuti->user->profile->divisi->name }}</td>
                                        <td>{{ $cuti->user->profile->posisi->name }}</td>
                                        <td>{{ $cuti->from }}</td>
                                        <td>{{ $cuti->to }}</td>
                                        <td>{{ $cuti->duration }}</td>
                                        <td>{{ $cuti->user->profile->cuti }}</td>
                                        <td>
                                            <span class="status-p bg-primary">
                                                {{ $cuti->status_in_human }}
                                            </span>
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            <ul class="d-flex justify-content-center">
                                                <li class="mr-3"><a href="#" class="text-secondary"><i class="fa fa-edit"></i></a></li>
                                                <li><a href="#" class="text-danger"><i class="ti-trash"></i></a></li>
                                            </ul>
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
@endsection
