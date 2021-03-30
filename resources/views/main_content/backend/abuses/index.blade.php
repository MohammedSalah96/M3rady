@extends('layouts.backend')

@section('pageTitle', _lang('app.abuses'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="{{route('posts.index')}}" class="text-muted">{{_lang('app.posts')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="{{route('posts.show',$post)}}" class="text-muted">{{_lang('app.post')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.abuses')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/abuses.js')}}" type="text/javascript"></script>
@endsection

@section('content')
{{ csrf_field() }}
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon-cogwheel text-primary"></i>
            </span>
            <h3 class="card-label">{{_lang('app.manage_abuses')}}</h3>
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>{{ _lang('app.name')}}</th>
                    <th>{{ _lang('app.date')}}</th>
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
        post: "{{$post}}"
    };
    var newLang = {
    
    };
</script>
@endsection
