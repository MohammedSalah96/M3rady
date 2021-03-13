@extends('layouts.backend_auth')

@section('content')
<!--begin::Login Sign in form-->
<div class="login-signin">
    <div class="mb-20">
        <h3>Sign In To Admin</h3>
        <div class="text-muted font-weight-bold">Enter your details to login to your account:</div>
    </div>
    <form class="form" id="kt_login_signin_form">
        {{ csrf_field() }}
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Email"
                name="email" autocomplete="off" />
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password"
                name="password" />
        </div>
        <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
            <a href="{{ route('admin.password.request') }}" id="kt_login_forgot" class="text-muted text-hover-primary">Forget Password ?</a>
        </div>
        <button id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">
            Sign In
        </button>
    </form>
</div>
<!--end::Login Sign in form-->

<!--begin::Login forgot password form-->
<div class="login-forgot">
    <div class="mb-20">
        <h3>Forgotten Password ?</h3>
        <div class="text-muted font-weight-bold">Enter your email to reset your password</div>
    </div>
    <form class="form" id="kt_login_forgot_form">
        {{ csrf_field() }}
        <div class="form-group mb-10">
            <input class="form-control form-control-solid h-auto py-4 px-8" type="text" placeholder="Email" name="email"
                autocomplete="off" />
        </div>
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            <button id="kt_login_forgot_submit"
                class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Request</button>
            <button id="kt_login_forgot_cancel"
                class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Cancel</button>
        </div>
    </form>
</div>
<!--end::Login forgot password form-->
@endsection

@section('scripts')
    <script src="{{url('public/backend/scripts/login-general.js')}}"></script>
@endsection




