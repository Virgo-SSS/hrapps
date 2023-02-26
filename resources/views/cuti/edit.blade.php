@extends('layouts.app')

@section('title')
    <li><a href="{{ route('cuti.index') }}">Cuti</a></li>
    <li><span>Edit</span></li>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mt-5">
            <div class="card-body">
                <h4 class="header-title">Create Request Cuti</h4>
                <form action="{{ route('cuti.update', $cuti->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="{{ $cuti->user->name }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date">Date</label>
                            <input type="text" name="date" id="date" value="{{ $cuti->date_cuti }}" class="form-control" required autocomplete="off" placeholder="Select Date Range">
                            @error('date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="head_of_division">Head Of Division</label>
                                    <select name="head_of_division" id="head_of_division" class="demo-default" required placeholder="Select a person...">
                                        <option value="">Select a Head Of Division...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == $cuti->cutiRequest->head_of_division)>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('head_of_division')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="head_of_department">Head Of Department</label>
                                    <select name="head_of_department" id="head_of_department" required class="demo-default" placeholder="Select a person...">
                                        <option value="">Select a Head Of Department...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == $cuti->cutiRequest->head_of_department)>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('head_of_department')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="remaining_cuti">Remaining Cuti</label>
                                    <input type="text" id="remaining_cuti" disabled placeholder="{{ Auth::user()->profile->cuti }} Days" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="leave_days">Leave Days</label>
                                    <input type="text" id="leave_days" disabled class="form-control" value="{{ $cuti->total_leave_days }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="reason">Reason</label>
                            <textarea name="reason" name="reason" id="reason" class="form-control" rows="5" required>{{ $cuti->reason }}</textarea>
                            @error('reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit form</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#date').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: moment(),
                autoUpdateInput: false,
                showDropdowns: true,
                opens: 'center',
                autoApply: true,
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                var start = picker.startDate.format('YYYY-MM-DD');
                var end = picker.endDate.format('YYYY-MM-DD');
                var diff = moment(end).diff(moment(start), 'days');
                $('#leave_days').val(diff + 1);
            });

            $('#date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('select').selectize({
                sortField: {
                    field: 'text',
                    direction: 'asc'
                }
            });
        });
    </script>
@endsection
