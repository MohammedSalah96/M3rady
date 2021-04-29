@extends('layouts.backend')

@section('pageTitle', _lang('app.posts'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.posts')}}</a>
</li>

@endsection

@section('js')
<script src="{{url('public/backend/scripts/posts.js')}}" type="text/javascript"></script>
@endsection

@section('content')
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
                        @foreach ($companies as $companies)
                        <option value="{{$companies->id}}">{{$companies->name}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
                <div class="form-group col-lg-4">
                    
                    <div class="checkbox-list">
                        <label class="checkbox">
                            <input type="checkbox" name="has_abuses">
                            <span></span>{{ _lang('app.has_abuses') }}
                        </label>
                    </div>
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
            <h3 class="card-label">{{_lang('app.manage_posts')}}</h3>
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>{{ _lang('app.post')}}</th>
                    <th>{{ _lang('app.description')}}</th>
                    <th>{{ _lang('app.company')}}</th>
                    <th>{{ _lang('app.no_of_likes')}}</th>
                    <th>{{ _lang('app.no_of_comments')}}</th>
                    <th>{{ _lang('app.no_of_abuses')}}</th>
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
