@extends('layouts.app')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="login-form-head">
            <h4>Sign In</h4>
            <p>Hello there, Sign in and start managing your Admin Template</p>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="login-form-body">
            <div class="form-gp">
                <label for="exampleInputEmail1">UUID</label>
                <input type="number" id="exampleInputEmail1" name="uuid">
                <i class="ti-email"></i>
                <div class="text-danger"></div>
            </div>
            <div class="form-gp">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" id="exampleInputPassword1" name="password">
                <i class="ti-lock"></i>
                <div class="text-danger"></div>
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
