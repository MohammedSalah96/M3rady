@extends('layouts.backend')

@section('pageTitle', _lang('app.package_subscriptions'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.package_subscriptions')}}</a>
</li>

@endsection

@section('js')
<script src="{{url('public/backend/scripts/package_subscriptions.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="modal fade" id="addSubscription" role="dialog">
    <div class="modal-dialog modal-dialog-centered">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubscriptionLabel">{{_lang('app.add_subscription')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" id="addSubscriptionForm" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group">
                            <label>
                                {{ _lang('app.company') }}
                            </label>
                            <select class="form-control form-control-solid" id="company" name="company">
                                <option value="">{{_lang('app.choose')}}</option>
                                @foreach ($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="form-group">
                            <label>
                                {{ _lang('app.type') }}
                            </label>
                            <select class="form-control form-control-solid" id="type" name="type">
                                <option value="">{{_lang('app.choose')}}</option>
                                <option value="trial">{{_lang('app.trial')}}</option>
                                <option value="subscription">{{_lang('app.subscription')}}</option>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="form-group" style="display: none" id="subscription-package">
                            <label>
                                {{ _lang('app.package') }}
                            </label>
                            <select class="form-control form-control-solid" id="package" name="package">
                                <option value="">{{_lang('app.choose')}}</option>
                                @foreach ($packages as $package)
                                <option value="{{$package->id}}">{{$package->name}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div id="trial-duration" style="display: none">
                            <div class="form-group">
                                <label for="start_date">{{_lang('app.start_date')}}</label>
                                <input type="text" class="form-control form-control-solid" id="start_date" name="start_date"
                                    placeholder="{{_lang('app.start_date')}}" readonly>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for="end_date">{{_lang('app.end_date')}}</label>
                                <input type="text" class="form-control form-control-solid" id="end_date" name="end_date" placeholder="{{_lang('app.end_date')}}" readonly>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-primary font-weight-bold submit-form">{{_lang("app.save")}}</button>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{ _lang("app.close") }}</button>
            </div>
        </div>
    </div>
</div>



<div class="card card-custom gutter-b">
    <form id="filterForm">
        <div class="card-body">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="from">{{_lang('app.from')}}</label>
                    <input type="text" class="form-control form-control-solid" id="from" name="from"
                        placeholder="{{_lang('app.from')}}" readonly>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-lg-4">
                    <label for="to">{{_lang('app.to')}}</label>
                    <input type="text" class="form-control form-control-solid" id="to" name="to"
                        placeholder="{{_lang('app.to')}}" readonly>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-lg-4">
                    <label>
                        {{ _lang('app.company') }}
                    </label>
                    <select class="form-control form-control-solid" id="company" name="company">
                        <option value="">{{_lang('app.choose')}}</option>
                        @foreach ($companies as $company)
                        <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-lg-4">
                    <label>
                        {{ _lang('app.package') }}
                    </label>
                    <select class="form-control form-control-solid" id="package" name="package">
                        <option value="">{{_lang('app.choose')}}</option>
                        @foreach ($packages as $package)
                        <option value="{{$package->id}}">{{$package->name}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-lg-4">
                    <label>
                        {{ _lang('app.type') }}
                    </label>
                    <select class="form-control form-control-solid" id="type" name="type">
                        <option value="">{{_lang('app.choose')}}</option>
                        <option value="trial">{{_lang('app.trial')}}</option>
                        <option value="subscription">{{_lang('app.subscription')}}</option>
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="ml-auto mr-auto text-center">
                    <button type="submit" class="btn btn-primary font-weight-bold submit-form">
                        {{_lang('app.apply')}}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
{{ csrf_field() }}
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
            <h3 class="card-label">{{_lang('app.package_subscriptions')}}</h3>
        </div>
        <div class="card-toolbar">
            @if (\Permissions::check('package_subscriptions','add'))
            <!--begin::Button-->
            <a href="#" onclick="PackageSubscriptions.add(); return false;" class="btn btn-primary font-weight-bolder">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Flatten.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <circle fill="#000000" cx="9" cy="15" r="6" />
                            <path
                                d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>{{_lang('app.New_Record')}}</a>
            <!--end::Button-->
            @endif
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>{{ _lang('app.company')}}</th>
                    <th>{{ _lang('app.type')}}</th>
                    <th>{{ _lang('app.package')}}</th>
                    <th>{{ _lang('app.price')}}</th>
                    <th>{{ _lang('app.duration')}}</th>
                    <th>{{ _lang('app.start_date')}}</th>
                    <th>{{ _lang('app.end_date')}}</th>
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
       
    };
    var newLang = {
      apply:"{{_lang('app.apply')}}"
    };
</script>
@endsection
