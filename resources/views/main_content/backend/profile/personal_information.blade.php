@extends('layouts.backend')

@section('pageTitle',_lang('app.profile'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.profile')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/profile.js')}}" type="text/javascript"></script>
<script src="{{url('public/backend/js/pages/custom/profile/profile.js')}}" type="text/javascript"></script>
@endsection

@section('content')

<!--begin::Profile Personal Information-->
<div class="d-flex flex-row">
    <!--begin::Aside-->
    @include('main_content.backend.profile.profile_side')
    <!--end::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
        <!--begin::Card-->
        <div class="card card-custom card-stretch">
            <!--begin::Header-->
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">{{_lang('app.Personal_Information')}}</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">{{_lang('app.update_your_personal_informaiton')}}</span>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-success mr-2 submit-form">{{_lang('app.Save_Changes')}}</button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <form class="form" id="updateProfileForm" enctype="multipart/form-data">
                {{ csrf_field() }}
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9 col-xl-6">
                            <h5 class="font-weight-bold mb-6">{{_lang('app.Your_Info')}}</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{_lang('app.avatar')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="image-input image-input-outline" id="kt_profile_avatar"
                                style="background-image: url({{url('public/backend/media/users/blank.png')}})">
                                <div class="image-input-wrapper"
                                    style="background-image: url({{url('public/uploads/admins/'.$user->image)}})">
                                </div>
                                <label
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change" data-toggle="tooltip" title=""
                                    data-original-title="{{_lang('app.change_avatar')}}">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="profile_avatar_remove" />
                                </label>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                            <span class="form-text text-muted">{{_lang('app.allowed_file_types')}}: png, jpg, jpeg.</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{_lang('app.name')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="text" class="form-control form-control-lg form-control-solid"
                             name="name" value="{{$user->name}}" placeholder="{{_lang('app.name')}}" />
                            <span class="invalid-feedback"></span>
                        </div>
                        
                    </div>
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9 col-xl-6">
                            <h5 class="font-weight-bold mt-10 mb-6">{{_lang('app.Contact_Info')}}</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{_lang('app.phone')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-phone"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control form-control-lg form-control-solid"
                                 name="phone" value="{{$user->phone}}" placeholder="{{_lang('app.phone')}}" />
                            </div>
                           <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{_lang('app.email')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control form-control-lg form-control-solid"
                                 name="email" value="{{$user->email}}" placeholder="{{_lang('app.email')}}" />
                            </div>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!--end::Content-->
</div>
<!--end::Profile Personal Information-->
@endsection