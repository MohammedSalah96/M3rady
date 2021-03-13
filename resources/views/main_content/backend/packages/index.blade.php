@extends('layouts.backend')

@section('pageTitle', _lang('app.packages'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.packages')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/packages.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">{{_lang('app.create') }}/{{_lang('app.edit') }} {{_lang('app.packages') }} </h3>
    </div>
    <form id="addEditPackagesForm" enctype="multipart/form-data">
        <div class="card-body">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="0">
            <div class="row">
                @foreach ($languages as $key => $value)
                <div class="form-group col-md-6 col-sm-12">
                    <label for="name">{{_lang('app.name')}} {{_lang('app.'.$value) }}<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-solid" id="name[{{ $key }}]" name="name[{{ $key }}]"
                        style="direction:{{$key == 'ar' ? 'rtl' : 'ltr' }}">
                    <span class="invalid-feedback"></span>
                </div>
                @endforeach
            </div>
            <div class="row">
                @foreach ($languages as $key => $value)
                <div class="form-group col-md-6 col-sm-12">
                    <label for="name">{{_lang('app.description')}} {{_lang('app.'.$value) }}<span class="text-danger">*</span></label>
                    <textarea class="form-control form-control-solid" id="description[{{ $key }}]" name="description[{{ $key }}]"
                        style="direction:{{$key == 'ar' ? 'rtl' : 'ltr' }}" rows="4"></textarea>
                    <span class="invalid-feedback"></span>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="form-group col-md-3 col-sm-12">
                    <label>
                        {{ _lang('app.duration') }}<span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control form-control-solid" id="duration" name="duration"
                        placeholder="{{_lang('app.duration')}}">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label>
                        {{ _lang('app.price') }}<span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control form-control-solid" id="price" name="price"
                        placeholder="{{_lang('app.price')}}">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label>
                        {{ _lang('app.position') }}<span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control form-control-solid" id="position" name="position"
                        placeholder="{{_lang('app.position')}}">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-md-3 col-sm-12">
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
            <h3 class="card-label">{{_lang('app.manage_packages')}}</h3>
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>{{ _lang('app.name')}}</th>
                    <th>{{ _lang('app.duration')}}</th>
                    <th>{{ _lang('app.price')}}</th>
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

<script>
    var newConfig = {
       
    };
    var newLang = {
    
    };
</script>
@endsection
