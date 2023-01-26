@extends('layouts.app')

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="login-form-head">
            <h4>Sign up</h4>
            <p>Hello there, Sign up and Join with Us</p>
        </div>
        <div class="login-form-body">
            <div class="form-gp">
                <label for="exampleInputName1">Full Name</label>
                <input type="text" id="exampleInputName1" name="name">
                <i class="ti-user"></i>
                <div class="text-danger"></div>
            </div>
            <div class="form-gp">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" id="exampleInputEmail1" name="email">
                <i class="ti-email"></i>
                <div class="text-danger"></div>
            </div>
            <div class="form-gp">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" id="exampleInputPassword1" name="password">
                <i class="ti-lock"></i>
                <div class="text-danger"></div>
            </div>
            <div class="form-gp">
                <label for="exampleInputPassword2">Confirm Password</label>
                <input type="password" id="exampleInputPassword2" name="password_confirmation">
                <i class="ti-lock"></i>
                <div class="text-danger"></div>
            </div>
            <div class="submit-btn-area">
                <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
            </div>
            <div class="form-footer text-center mt-5">
                <p class="text-muted">Don't have an account? <a href="{{ route('login') }}">Sign in</a></p>
            </div>
        </div>
    </form>
@endsection
