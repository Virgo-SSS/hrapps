@extends('layouts.app')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="login-form-head">
            <h4>Sign In</h4>
            <p>PT ADI BINTAN PERMATA</p>
        </div>
        <div class="login-form-body">
            <div class="form-gp">
                <label for="uuid">UUID</label>
                <input type="number" id="uuid" name="uuid" required>
                <i class="ti-user"></i>
                @error('uuid')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-gp">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <i class="ti-lock"></i>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="row mb-4 rmber-area">
{{--                <div class="col-6">--}}
{{--                    <div class="custom-control custom-checkbox mr-sm-2">--}}
{{--                        <input type="checkbox" name="remember" id="customControlAutosizing" {{ old('remember') ? 'checked' : '' }} class="custom-control-input">--}}
{{--                        <label class="custom-control-label" for="customControlAutosizing">Remember Me</label>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-12 text-right">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>
            <div class="submit-btn-area">
                <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
            </div>
            <div class="form-footer text-center mt-5">
                <p class="text-muted">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
            </div>
        </div>
    </form>
@endsection
