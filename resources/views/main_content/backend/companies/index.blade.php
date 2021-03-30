@extends('layouts.backend')

@section('pageTitle', _lang('app.companies'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.companies')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/companies.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">{{_lang('app.create') }}/{{_lang('app.edit') }} {{_lang('app.companies') }} </h3>
    </div>
    <form id="addEditCompaniesForm" enctype="multipart/form-data">
        <div class="card-body">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="0">
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">{{_lang('app.image')}}</label>
                <div class="col-lg-9 col-xl-9">
                    <div class="image-input image-input-empty image-input-outline" id="kt_avatar"
                        style="background-image: url({{url('public/backend/media/users/blank.png')}})">
                        <div class="image-input-wrapper"></div>
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change" data-toggle="tooltip" title=""
                            data-original-title="{{_lang('app.change_avatar')}}">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="profile_avatar_remove" />
                        </label>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel" data-toggle="tooltip" title="{{_lang('app.cancel')}}">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove" data-toggle="tooltip" title="{{_lang('app.remove')}}">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                    <span class="form-text text-muted">{{_lang('app.allowed_file_types')}}: png, jpg, jpeg.</span>
                </div>
                <span class="invalid-feedback"></span>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="email">{{_lang('app.email')}}<span class="text-danger">*</span></label>
                    <input type="email" class="form-control form-control-solid" id="email" name="email">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="mobile">{{_lang('app.mobile')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="mobile" name="mobile">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="password">{{_lang('app.password')}}<span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-solid" id="password" name="password">
                    <span class="invalid-feedback"></span>
                </div>
            </div>
            <div class="row">
               <div class="form-group col-md-4 col-sm-12">
                    <label>
                        {{ _lang('app.country') }}<span class="text-danger">*</span>
                    </label>
                    <select class="form-control form-control-solid" id="country" name="country">
                        <option value="">{{ _lang('app.choose_country') }}</option>
                        @foreach ($countries as $country)
                            <option value="{{$country->id}}">{{ $country->name }}</option>
                        @endforeach
                       
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>
                        {{ _lang('app.city') }}<span class="text-danger">*</span>
                    </label>
                    <select class="form-control form-control-solid" id="city" name="city">
                        <option value="">{{ _lang('app.choose_city') }}</option>
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
               
                <div class="form-group col-md-4 col-sm-12">
                    <label>
                        {{ _lang('app.status') }}<span class="text-danger">*</span>
                    </label>
                    <select class="form-control form-control-solid" id="active" name="active">
                        <option value="1">{{ _lang('app.active') }}</option>
                        <option value="0">{{ _lang('app.not_active') }}</option>
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="company_id">{{_lang('app.company_id')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="company_id" name="company_id">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="description">{{_lang('app.description')}}<span class="text-danger">*</span></label>
                    <textarea rows="5" class="form-control form-control-solid" id="description"
                        name="description"></textarea>
                    <span class="invalid-feedback"></span>
                </div>

                <div class="form-group col-md-6 col-sm-12">
                    <label for="name_ar">{{_lang('app.name_ar')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="name_ar" name="name_ar">
                    <span class="invalid-feedback"></span>
                </div>

                <div class="form-group col-md-6 col-sm-12">
                    <label for="name_en">{{_lang('app.name_en')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="name_en" name="name_en">
                    <span class="invalid-feedback"></span>
                </div>

                

               <div class="form-group col-md-4 col-sm-12">
                    <label>
                        {{ _lang('app.main_category') }}<span class="text-danger">*</span>
                    </label>
                    <select class="form-control form-control-solid" id="main_category" name="main_category">
                        <option value="">{{ _lang('app.choose_category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{ $category->name }}</option>
                        @endforeach
                       
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>
                        {{ _lang('app.sub_category') }}<span class="text-danger">*</span>
                    </label>
                    <select class="form-control form-control-solid" id="sub_category" name="sub_category">
                        <option value="">{{ _lang('app.choose_category') }}</option>
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
               
                <div class="form-group col-md-2 col-sm-12">
                    <label for="lat">{{_lang('app.lat')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="lat" name="lat">
                    <span class="invalid-feedback"></span>
                </div>
                
                <div class="form-group col-md-2 col-sm-12">
                    <label for="lng">{{_lang('app.lng')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="lng" name="lng">
                    <span class="invalid-feedback"></span>
                </div>

                <div class="form-group col-md-6 col-sm-12">
                    <label for="facebook">{{_lang('app.facebook')}}</label>
                    <input type="text" class="form-control form-control-solid" id="facebook" name="facebook">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="twitter">{{_lang('app.twitter')}}</label>
                    <input type="text" class="form-control form-control-solid" id="twitter" name="twitter">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="whatsapp">{{_lang('app.whatsapp')}}</label>
                    <input type="text" class="form-control form-control-solid" id="whatsapp" name="whatsapp">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="website">{{_lang('app.website')}}</label>
                    <input type="text" class="form-control form-control-solid" id="website" name="website">
                    <span class="invalid-feedback"></span>
                </div>
            </div>
            
           
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="ml-auto mr-auto text-center">
                    <button type="submit"
                        class="btn btn-primary font-weight-bold submit-form">{{_lang('app.save')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@if (session()->has('error_message'))
<div class="alert alert-custom alert-danger fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">{{session()->get('error_message')}}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon-cogwheel text-primary"></i>
            </span>
            <h3 class="card-label">{{_lang('app.manage_companies')}}</h3>
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>{{ _lang('app.company')}}</th>
                    <th>{{ _lang('app.email')}}</th>
                    <th>{{ _lang('app.mobile')}}</th>
                    <th>{{ _lang('app.country')}}</th>
                    <th>{{ _lang('app.category')}}</th>
                    <th>{{ _lang('app.status')}}</th>
                    <th>{{ _lang('app.options')}}</th>
                </tr>
            </thead>
        </table>
        <!--end: Datatable-->
    </div>

</div>
<!--end::Card-->

<script>
    var newConfig = {
       type : "{{$type}}"
    };
    var newLang = {
        choose_city : "{{_lang('app.choose_city')}}",
        choose_category : "{{_lang('app.choose_category')}}",
    };
</script>
@endsection
