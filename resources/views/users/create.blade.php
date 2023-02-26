@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
    <li><a href="{{ route('users.index') }}">Employee</a></li>
    <li><span>Create</span></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-body">
                    <h4 class="header-title">Create Employee</h4>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="uuid">UUID</label>
                                <input type="number" name="uuid" id="uuid" value="{{ old('uuid') }}" class="form-control" placeholder="xxxxxxx" required>
                                @error('uuid')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Full Name" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="name@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bank">Bank</label>
                                <select name="bank" id="bank" class="form-control" required>
                                    <option selected disabled>Select</option>
                                    @foreach(config('bank') as $key => $value)
                                        <option value="{{ $value }}" @selected(old('bank') == $value)>{{ $key }}</option>
                                    @endforeach
                                </select>
                                @error('bank')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bank_account_number">Bank Account Number</label>
                                <input type="number" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number') }}" class="form-control" placeholder="xxxxxxxxx" required>
                                @error('bank_account_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="role_id">Role</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <option selected disabled>Select</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="divisi_id">Divisi</label>
                                <select name="divisi_id" id="divisi_id" class="form-control" required>
                                    <option selected disabled>Select</option>
                                    @foreach($divisis as $divisi)
                                        <option value="{{ $divisi->id }}" @selected(old('divisi_id') == $divisi->id)>{{ ucfirst($divisi->name) }}</option>
                                    @endforeach
                                </select>
                                @error('divisi_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="posisi_id">Posisi</label>
                                <!-- Hidden Input Field: posisi Name this is just for validation error -->
                                <input type="hidden" id="posisi_name" name="posisi_name" value="{{ old('posisi_name') }}">

                                <select name="posisi_id" id="posisi_id" class="form-control" disabled required>
                                    <option selected disabled>Select</option>
                                    @if (old('posisi_id'))
                                        <!-- Option for the selected city -->
                                        <option value="{{ old('posisi_id') }}" selected>{{ old('posisi_name') }}</option>
                                    @endif
                                </select>
                                @error('posisi_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="join_date">Join Date</label>
                                <input type="date" name="join_date" id="join_date" value="{{ old('join_date') }}" class="form-control" placeholder="Join Date" required>
                                @error('join_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cuti">Cuti</label>
                                <input type="number" name="cuti" id="cuti" value="{{ old('cuti') }}" class="form-control" placeholder="Cuti" required>
                                @error('cuti')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="salary">Salary</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input type="text" name="salary" id="salary" value="{{ old('salary') }}"  class="form-control" placeholder="Salary" required>
                                    @error('salary')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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

@section('scripts')
    <script>
        $('#divisi_id').on('change', function () {
            var divisi = $(this).val();
            let link = "{{ route('posisi.by-divisi', ':id') }}";
            link = link.replace(':id', divisi);
            $.ajax({
                url: link,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#posisi_id').prop('disabled', false);
                    $('#posisi_id').empty();
                    $('#posisi_id').append('<option selected disabled>Select</option>');
                    $.each(data, function (key, posisi) {
                        $('#posisi_id').append($('<option>', {
                            value: posisi.id,
                            text: posisi.name
                        }));
                    });
                }
            });
        });

        $('#posisi_id').change(function() {
            let posisiName = $(this).find(':selected').text();
            $('#posisi_name').val(posisiName);
        });

        $('#salary').mask('#.##0', {reverse: true});
    </script>
@endsection
