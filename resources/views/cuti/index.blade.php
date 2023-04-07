@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('title')
    <li><a href="{{ route('cuti.index') }}">Cuti</a></li>
    <li><span>Index</span></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-2">
            <div class="mb-3">
                <a href="{{ route('cuti.create') }}" style="text-decoration: none; color:white">
                    <button class="btn btn-success">
                        Create Request Cuti  <i class="fa fa-plus" style="color: #fbff7c"></i>
                    </button>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Cuti Table</h4>
                    <div class="single-table">
                        <div class="table-responsive">
                            <table class="table table-hover progress-table" id="cuti-table">
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
                                        @can('edit cuti')
                                        <th scope="col">Edit</th>
                                        @endcan
                                        @can('delete cuti')
                                        <th scope="col">Delete</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cutis as $cuti)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $cuti->user->uuid  }}</td>
                                            <td>{{ $cuti->user->name }}</td>
                                            <td>{{ $cuti->user->profile->divisi->name }}</td>
                                            <td>{{ $cuti->user->profile->posisi->name }}</td>
                                            <td>{{ $cuti->from }}</td>
                                            <td>{{ $cuti->to }}</td>
                                            <td>{{ $cuti->duration }}</td>
                                            <td>{{ $cuti->user->profile->cuti }}</td>
                                            <td>
                                                <span class="status-p bg-{{ $cuti->color_status }}">
                                                    <a href="{{ route('cuti.show', $cuti->id) }}" style="text-decoration: none; color: white">
                                                        {{ $cuti->status_in_human }}
                                                    </a>
                                                </span>
                                            </td>
                                            @can('edit cuti')
                                                <td>
                                                    @if($cuti->cutiRequest->status_hod == config('cuti.status.pending') && $cuti->cutiRequest->status_hodp == config('cuti.status.pending'))
                                                        <a href="{{ route('cuti.edit', $cuti->id) }}" class="text-secondary" style="font-size: 20px"><i class="fa fa-edit"></i></a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endcan
                                            @can('delete cuti')
                                                <td>
                                                    <a href="#" class="text-danger" style="font-size: 20px" onclick="deleteItem('#delete-cuti-{{ $cuti->id }}', 'Cuti {{ $cuti->user->name }}')">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                    <form action="{{ route('cuti.destroy', $cuti->id) }}" method="POST" id="delete-cuti-{{ $cuti->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            @endcan
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
        $('#cuti-table').DataTable({
            ordering : false,
            columnDefs: [
                {
                    className: "dt-head-center dt-body-center",
                    targets: "_all"
                },
            ]
        });
    </script>
@endsection
