@extends('layouts.app')

@section('content')
    <div class="sales-report-area mt-5 mb-5">
        <h1 class="text-center mb-3">HELLO {{ strtoupper(Auth::user()->name) }}</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--14 mb-3">
                        <div class="icon"><i class="fa fa-calendar-plus-o"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Join Date</h4>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <h2>{{ Auth::user()->profile->join_date }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--14 mb-3">
                        <div class="icon"><i class="fa fa-hourglass-2"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Sisa Cuti</h4>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <h2>{{ Auth::user()->profile->cuti }} Days</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="single-report">
                    <div class="s-report-inner pr--20 pt--14 mb-3">
                        <div class="icon"><i class="fa fa-tag"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Divisi</h4>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <h2>{{ Auth::user()->profile->divisi->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
