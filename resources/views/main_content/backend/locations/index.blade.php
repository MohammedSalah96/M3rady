@extends('layouts.backend')

@section('pageTitle', _lang('app.locations'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
@if ($parentId)
<li class="breadcrumb-item">
    <a href="{{route('locations.index')}}" class="text-muted">{{_lang('app.locations')}}</a>
</li>
{!! $path !!}
@else  
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.locations')}}</a>
</li>
@endif

@endsection

@section('js')
<script src="{{url('public/backend/scripts/locations.js')}}" type="text/javascript"></script>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-3 col-lg-5 col-md-5 col-sm-12">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <h3 class="card-title">{{_lang('app.create_category') }}</h3>
            </div>
            <form id="addEditLocationsForm" enctype="multipart/form-data">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="0">
                    @foreach ($languages as $key => $value)
                    <div class="form-group">
                        <label for="name">{{_lang('app.name')}} {{_lang('app.'.$value) }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-solid" id="name[{{ $key }}]" name="name[{{ $key }}]"
                            style="direction:{{$key == 'ar' ? 'rtl' : 'ltr' }}">
                        <span class="invalid-feedback"></span>
                    </div>
                    @endforeach
                    @if (!$parentId)
                        <div class="form-group">
                            <label>
                                {{ _lang('app.dial_code') }}<span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-solid" id="dial_code" name="dial_code"
                                placeholder="{{_lang('app.dial_code')}}">
                            <span class="invalid-feedback"></span>
                        </div>
                    @endif
                   
                    <div class="form-group">
                        <label>
                            {{ _lang('app.position') }}<span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control form-control-solid" id="position" name="position"
                            placeholder="{{_lang('app.position')}}">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="form-group">
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
                
                <div class="card-footer">
                    <div class="row">
                        <div class="ml-auto mr-auto text-center">
                            <button type="submit" class="btn btn-primary font-weight-bold submit-form">{{_lang('app.save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-xl-9 col-lg-7 col-md-7 col-sm-12">
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
                    <h3 class="card-label">{{_lang('app.manage_locations')}}</h3>
                </div>
            </div>
        
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th>{{ _lang('app.name')}}</th>
                            @if (!$parentId)
                                <th>{{ _lang('app.dial_code')}}</th>
                            @endif
                            <th>{{ _lang('app.status')}}</th>
                            <th>{{ _lang('app.position')}}</th>
                            <th>{{ _lang('app.options')}}</th>
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable-->
            </div>
        
        </div>
        <!--end::Card-->
    </div>
</div>

<script>
    var newConfig = {
        parentId: "{{$parentId}}"
    };
    var newLang = {
    
    };
</script>
@endsection
