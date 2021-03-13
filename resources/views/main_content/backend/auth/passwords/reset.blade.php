@extends('layouts.backend_auth')

@section('content')
<!--begin::Login Reset Password form-->
<div class="login-signup">
    <div class="mb-20">
        <h3>Reset Password</h3>
        <div class="text-muted font-weight-bold">Enter your new password to your account</div>
    </div>
    <form class="form" id="kt_login_reset_password_form">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Email" name="email"
                autocomplete="off" id="email" />
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password"
                name="password" id="password" />
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                placeholder="Confirm Password" name="password_confirmation" />
        </div>
        
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            <button id="kt_login_reset_password_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">
                Reset Password
            </button>
        </div>
    </form>
</div>
<!--end::Login Reset password form-->
@endsection

@section('scripts')
<script src="{{url('public/backend/scripts/reset-password.js')}}"></script>
@endsection
