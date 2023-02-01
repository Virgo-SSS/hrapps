@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li><span>Create</span></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-body">
                    <h4 class="header-title">Create User</h4>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="uuid">UUID</label>
                                <input type="number" name="uuid" id="uuid" class="form-control" placeholder="xxxxxxx" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Full Name" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                                <div class="invalid-feedback">
                                    Please provide a valid city.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bank">Bank</label>
                                <select name="bank" id="bank" class="form-control">
                                    <option selected disabled>Select</option>
                                    @foreach(config('bank') as $key => $value)
                                        <option value="{{ $value }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="norek">Rekening Number</label>
                                <input type="text" name="norek" id="norek" class="form-control" placeholder="xxxx-xxx-xxx" required>
                                <div class="invalid-feedback">
                                    Please provide a valid city.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="divisi">Divisi</label>
                                <select name="divisi" id="divisi" class="form-control">
                                    <option selected disabled>Select</option>
                                    @foreach($divisis as $divisi)
                                        <option value="{{ $divisi->id }}">{{ ucfirst($divisi->name) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="posisi">Posisi</label>
                                <select name="posisi" id="posisi" class="form-control">
                                    <option selected disabled>Select</option>
                                    <option value="1">Front</option>
                                    <option value="0">Backend</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="join_date">Join Date</label>
                                <input type="date" name="join_date" id="join_date" class="form-control" placeholder="Join Date" required>
                                <div class="invalid-feedback">
                                    Please provide a valid state.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="cuti">Cuti</label>
                                <input type="number" name="cuti" id="cuti" class="form-control" placeholder="Cuti" required>
                                <div class="invalid-feedback">
                                    Please provide a valid zip.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="salary">Salary</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input type="text" name="salary" id="salary" class="form-control" placeholder="Salary" aria-describedby="inputGroupPrepend" required>
                                    <div class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="float:right;" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
