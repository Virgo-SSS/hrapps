@extends('layouts.app')

@section('title')
    <li><a href="{{ route('cuti.index') }}">Cuti</a></li>
    <li><span>Create</span></li>
@endsection

@section('content')
    <div class="col-12">
        <div class="card mt-5">
            <div class="card-body">
                <h4 class="header-title">Create Request Cuti</h4>
                <form action="{{ route('cuti.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="{{ Auth::user()->name }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date">Date</label>
                            <input type="text" name="date" id="date" value="{{ old('date') }}" class="form-control" required>
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="reason">Reason</label>
                            <textarea name="reason" name="reason" id="reason" class="form-control" rows="5" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="head_of_division">Head Of Division</label>
                                    <select name="head_of_division" id="head_of_division" class="demo-default" required placeholder="Select a person...">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('head_of_division')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="head_of_department">Head Of Departement</label>
                                    <select name="head_of_department" id="head_of_department" required class="demo-default" placeholder="Select a person...">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                                    <input type="text" id="leave_days" disabled class="form-control">
                                </div>
                            </div>
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
        $('input[name="date"]').daterangepicker({
            'locale': {
                'format': 'YYYY-MM-DD'
            },
        });

        $('select').selectize({
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });
    </script>
@endsection
