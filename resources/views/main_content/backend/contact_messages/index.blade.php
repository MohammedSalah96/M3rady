@extends('layouts.backend')

@section('pageTitle', _lang('app.contact_messages'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.contact_messages')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/contact_messages.js')}}" type="text/javascript"></script>
@endsection

@section('content')
{{ csrf_field() }}
<div class="modal fade" id="viewMessage" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{_lang('app.message_details')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="row" id="relpied" style="display: none;">
                    <div class="col-12">
                        <h4>{{ _lang('app.message') }}</h4>
                        <p id="message"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary font-weight-bold submit-form" style="display: none">{{_lang("app.send")}}</button>
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
            <h3 class="card-label">{{_lang('app.contact_messages')}}</h3>
        </div>
        <div class="card-toolbar">
            @if (\Permissions::check('contact_messages','delete'))
            <!--begin::Button-->
            <button disabled="disabled" style="line-height: 30px" onclick="ContactMessages.multipleDelete(this); return false;" class="btn btn-danger font-weight-bolder" id="btn-delete">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Flatten.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
                {{_lang('app.Delete_Records')}}
            </button>
            <!--end::Button-->
            @endif
        </div>
    </div>

    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-head-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>
                        <label class="checkbox checkbox-single checkbox-solid checkbox-primary mb-0">
                            <input type="checkbox" value="" class="group-checkable">
                            <span></span>
                        </label>
                    </th>
                    <th>{{ _lang('app.name')}}</th>
                    <th>{{ _lang('app.mobile')}}</th>
                    <th>{{ _lang('app.message')}}</th>
                    <th>{{ _lang('app.created_at')}}</th>
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
        no_item_selected : "{{_lang('app.no_item_selected')}}",
        delete_records : "{{_lang('app.Delete_Records')}}"
    };
</script>
@endsection