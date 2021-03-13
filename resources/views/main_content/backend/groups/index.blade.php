@extends('layouts.backend')

@section('pageTitle', _lang('app.groups'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.groups')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/groups.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="modal fade" id="addEditGroups" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditGroupsLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button> 
            </div>
            <div class="modal-body">
                <form role="form" id="addEditGroupsForm" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="0">
                    <div class="form-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>
                                    {{ _lang('app.name') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-solid" placeholder="{{_lang('app.name')}}" name="name" id="name">
                                <span class="invalid-feedback"></span>
                            </div>
                            
                            <div class="form-group col-6">
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
                        

                        <div class="form-group row">
                            @php
                            $count = 0;
                            @endphp

                            @foreach ($modules as $module)
                            <label class="col-2 col-form-label mt-3 text-primary">{{ _lang('app.'.$module['name']) }}</label>
                            <div class="col-2 mt-4">
                                @foreach ($module['actions'] as $action)
                                @php
                                $id = $module['name'] . '_' . $action
                                @endphp
                                <span class="switch switch-icon">
                                    <label style="line-height: 30px">
                                        <input id="{{ $id }}" value="1" name="group_options[{{ $module['name'] }}][{{ $action }}]" type="checkbox" />
                                        <span></span>
                                        {{_lang('app.'.$action)}}
                                    </label>
                                </span>
                                @endforeach
                            </div>
                           
                            @php $count ++; @endphp

                            @if($count == 3)
                                @php $count = 0; @endphp
                                <div class="clearfix"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary font-weight-bold submit-form">{{_lang("app.save")}}</button>
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{ _lang("app.close") }}</button>
            </div>
        </div>
    </div>
</div>

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon-cogwheel text-primary"></i>
            </span>
            <h3 class="card-label">{{_lang('app.manage_groups')}}</h3>
        </div>
        <div class="card-toolbar">
            @if (\Permissions::check('groups','add'))
            <!--begin::Button-->
            <a href="#" onclick="Groups.add(); return false;" class="btn btn-primary font-weight-bolder">
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
                    <th>{{ _lang('app.group_name')}}</th>
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
    var new_lang = {
        add_group: "{{_lang('app.add_group')}}",
        edit_group: "{{_lang('app.edit_group')}}"
    };
</script>
@endsection
