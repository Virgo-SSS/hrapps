@extends('layouts.app')

@section('title')
    <li><a href="{{ route('cuti.index') }}">Cuti</a></li>
    <li><span>Requests</span></li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Request Cuti Table</h4>
                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table" id="cutiTable">
                            <thead class="text-uppercase bg-primary">
                                <tr class="text-white">
                                    <th scope="col">No</th>
                                    <th scope="col">UUID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">From</th>
                                    <th scope="col">To</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Sisa Cuti</th>
                                    <th scope="col">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingCutis as $cuti)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $cuti->user->uuid }}</td>
                                        <td>{{ $cuti->user->name }}</td>
                                        <td>{{ $cuti->from }}</td>
                                        <td>{{ $cuti->to }}</td>
                                        <td>{{ $cuti->duration }} Days</td>
                                        <td>{{ $cuti->reason }}</td>
                                        <td>{{ $cuti->user->profile->cuti }}</td>
                                        <td>
                                            <a href="#">
                                                <button class="btn btn-success" style="padding:1px 9px">
                                                        <i class="fa fa-check" style="font-size: 25px;color: greenyellow"></i>
                                                </button>
                                            </a>
                                            |
                                            <a href="#" data-toggle="modal" data-target="#exampleModalCenter">
                                                <button class="btn btn-danger" style="padding:1px 9px">
                                                        <i class="fa fa-times" style="font-size: 25px;color: white"></i>
                                                </button>
                                            </a>
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
</div>
@endsection

@section('modal')
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure want to reject this leave ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <label for="">*Note (optional) :</label>
                        <textarea name="note" id="note_head" cols="10" rows="2" class="form-control"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#cutiTable').DataTable();
        });
    </script>
@endsection
