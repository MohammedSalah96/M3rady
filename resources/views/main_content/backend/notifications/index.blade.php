@extends('layouts.backend')

@section('pageTitle',_lang('app.notifications'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.notifications')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/notifications.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<form role="form" class="form" id="sendNotificationsForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="card card-custom col-12 mb-3">
            <div class="card-header">
                <h3 class="card-title">{{_lang('app.send_notification') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>
                            {{ _lang('app.to') }}
                        </label>
                        <select class="form-control form-control-solid" id="type" name="type">
                            <option value="">{{_lang('app.all')}}</option>
                            <option value="1">{{_lang('app.clients')}}</option>
                            <option value="2">{{_lang('app.companies')}}</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    @foreach ($languages as $key => $value)
                    <div class="form-group col-6">
                        <label for="body">{{_lang('app.message') }} {{ _lang('app.'.$value) }}<span
                                class="text-danger">*</span></label>
                        <textarea class="form-control form-control-solid" id="body[{{ $key }}]" name="body[{{ $key }}]" cols="30"
                            rows="6"
                            placeholder="{{_lang('app.message') }} {{ _lang('app.'.$value) }}"></textarea>
                        <span class="invalid-feedback"></span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                        <button class="btn btn-primary submit-form">{{_lang('app.send')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon-cogwheel text-primary"></i>
            </span>
            <h3 class="card-label">{{_lang('app.notifications')}}</h3>
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>{{ _lang('app.notification')}}</th>
                    <th>{{ _lang('app.created_at')}}</th>
                    <th>{{ _lang('app.options')}}</th>
                </tr>
            </thead>
        </table>
        <!--end: Datatable-->
    </div>

</div>
<!--end::Card-->

@endsection