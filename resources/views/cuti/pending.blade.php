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
                                            <a href="#" onclick="actionLeave('approve-{{ $cuti->id }}')">
                                                <button class="btn btn-success" style="padding:1px 9px">
                                                        <i class="fa fa-check" style="font-size: 25px;color: greenyellow"></i>
                                                </button>
                                            </a>
                                            |
                                            <a href="#" onclick="actionLeave('reject-{{ $cuti->id }}')">
                                                <button class="btn btn-danger" style="padding:1px 9px">
                                                        <i class="fa fa-times" style="font-size: 25px;color: white"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <form action="#" id="actionForm" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="note" id="note-action">
                                            <input type="hidden" name="status" id="status-action">
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
        $(document).ready(function() {
            $('#cutiTable').DataTable();
        });

        async function actionLeave(parameter) {
            const { value: text } = await Swal.fire({
                input: 'textarea',
                inputLabel: 'Note (Optional)',
                inputPlaceholder: 'Type your message here...',
                inputAttributes: {
                    'aria-label': 'Type your message here'
                },
                showCancelButton: true
            })

            if (text !== undefined) {
                let status = parameter.split('-');
                let route = status[0] == 'approve' ? '{{ route('cuti.approve', ':id') }}' : '{{ route('cuti.reject', ':id') }}';
                route = route.replace(':id', status[1]);

                $('#note-action').val(text);
                $('#status-action').val(status[0]);
                $('#actionForm').attr('action', route).submit();
            }
        }
    </script>
@endsection
