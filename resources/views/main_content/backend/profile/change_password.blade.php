@extends('layouts.backend')

@section('pageTitle',_lang('app.profile'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="{{url('admin.profile')}}" class="text-muted">{{_lang('app.profile')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.change_password')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/password-change.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<!--begin::Profile Change Password-->
<div class="d-flex flex-row">
    <!--begin::Aside-->
    @include('main_content.backend.profile.profile_side')
    <!--end::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
        <!--begin::Card-->
        <div class="card card-custom">
            <!--begin::Header-->
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">{{_lang('app.Change_Password')}}</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">{{_lang('app.change_your_account_password')}}</span>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-success mr-2 submit-form">{{_lang('app.Save_Changes')}}</button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <form class="form" id="updatePasswordForm">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">{{_lang('app.Current_Password')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" name="current_password" id="current_password" class="form-control form-control-lg form-control-solid mb-2" 
                                placeholder="{{_lang('app.Current_Password')}}" />
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">{{_lang('app.New_Password')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" name="new_password" id="new_password" class="form-control form-control-lg form-control-solid"
                                placeholder="{{_lang('app.New_Password')}}" />
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">{{_lang('app.Verify_Password')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control form-control-lg form-control-solid"
                                placeholder="{{_lang('app.Verify_Password')}}" />
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!--end::Content-->
</div>
<!--end::Profile Change Password-->
@endsection