@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('users.index') }}">Employee</a></li>
<li><span>Edit</span></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-body">
                    <h4 class="header-title">Update Employee</h4>
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="uuid">UUID</label>
                                <input type="number" name="uuid" id="uuid" value="{{ $user->uuid }}" class="form-control" placeholder="xxxxxxx" required>
                                @error('uuid')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" placeholder="Full Name" required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control" placeholder="name@example.com" required>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="bank">Bank</label>
                                <select name="bank" id="bank" class="form-control" required>
                                    <option selected disabled>Select</option>
                                    @foreach(config('bank') as $key => $value)
                                        <option value="{{ $value }}" @selected($user->profile->bank == $value) >{{ $key }}</option>
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
                                <input type="number" name="bank_account_number" id="bank_account_number" value="{{ $user->profile->bank_account_number }}" class="form-control" placeholder="xxxxxxxxx" required>
                                @error('bank_account_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="join_date">Join Date</label>
                                <input type="date" name="join_date" id="join_date" value="{{ $user->profile->join_date }}" class="form-control" placeholder="Join Date" required>
                                @error('join_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="divisi_id">Divisi</label>
                                <select name="divisi_id" id="divisi_id" class="form-control" required>
                                    <option selected disabled>Select</option>
                                    @foreach($divisis as $divisi)
                                        <option value="{{ $divisi->id }}" @selected($user->profile->divisi_id == $divisi->id)>{{ ucfirst($divisi->name) }}</option>
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
                                <input type="hidden" id="posisi_name" name="posisi_name" value="{{ $user->profile->posisi->name }}">
                                <select name="posisi_id" id="posisi_id" class="form-control" {{ ($user->profile->posisi_id) ? '' : 'disabled' }} required>
                                    <option selected disabled>Select</option>
                                    @if($user->profile->posisi_id)
                                        <!-- Option for the selected city -->
                                        <option value="{{ $user->profile->posisi_id }}" selected>{{ $user->profile->posisi->name }}</option>
                                    @endif
                                </select>
                                @error('posisi_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cuti">Cuti</label>
                                <input type="number" name="cuti" id="cuti" value="{{ $user->profile->cuti }}" class="form-control" placeholder="Cuti" required>
                                @error('cuti')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="salary">Salary</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input type="text" name="salary" id="salary" value="{{ $user->profile->salary }}"  class="form-control" placeholder="Salary" required>
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
