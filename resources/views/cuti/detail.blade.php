@extends('layouts.app')

@section('title')
    <li><a href="{{ route('cuti.index') }}">Cuti</a></li>
    <li><span>Detail</span></li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <div class="invoice-area">
                    <div class="invoice-head">
                        <div class="row">
                            <div class="iv-left col-6">
                                <span>Detail Cuti</span>
                            </div>
                            <div class="iv-right col-6 text-md-right">
                                <span>{{ $cuti->user->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="invoice-address">
                                <h3>Reason</h3>
                                <p>{{ $cuti->reason }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <ul class="invoice-date">
                                <li>Start Date : {{ $cuti->from }}</li>
                                <li>End Date   : {{ $cuti->to }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="invoice-table table-responsive mt-5">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="text-uppercase bg-light">
                                <tr class="text-capitalize">
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Noted</th>
                                    <th >Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>{{ $cuti->cutiRequest->head_division->name }}</td>
                                    <td>{{ ($cuti->cutiRequest->note_hod) ?? '-' }}</td>
                                    <td>
                                        <span class="status-p bg-{{ $cuti->cutiRequest->color_status_hod }}">
                                             {{ $cuti->cutiRequest->status_hod_in_human }}
                                        </span>
                                    </td>
                                    <td>{{ ($cuti->cutiRequest->approved_hod_at) ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>{{ $cuti->cutiRequest->head_department->name }}</td>
                                    <td>{{ ($cuti->cutiRequest->note_hodp) ?? '-' }}</td>
                                    <td>
                                        <span class="status-p bg-{{ $cuti->cutiRequest->color_status_hodp }}">
                                            {{ $cuti->cutiRequest->status_hodp_in_human }}
                                        </span>
                                    </td>
                                    <td>{{ ($cuti->cutiRequest->approved_hodp_at) ?? '-' }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">Status : </td>
                                    <td>
                                       <span class="status-p bg-{{ $cuti->color_status }}">
                                             {{ $cuti->status_in_human }}
                                       </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
