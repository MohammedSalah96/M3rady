<meta charset="utf-8" />
<title>M3rady | @yield('pageTitle')</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<!--begin::Fonts-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta content="M3rady" name="description" />
<meta content="M3rady" name="author" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
    type="text/css" />
<!--end::Page Vendors Styles-->
<link href="{{url('public/backend/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@if ($lang_code == 'ar')
<!--begin::Global Theme Styles(used by all pages)-->
<link href="{{url('public/backend/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/plugins/custom/prismjs/prismjs.bundle.rtl.css')}}" rel="stylesheet"
    type="text/css" />
<link href="{{url('public/backend/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
<!--end::Global Theme Styles-->
<!--begin::Layout Themes(used by all pages)-->
<link href="{{url('public/backend/css/themes/layout/header/base/light.rtl.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/themes/layout/header/menu/light.rtl.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/themes/layout/brand/dark.rtl.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/themes/layout/aside/dark.rtl.css')}}" rel="stylesheet" type="text/css" />
@else
<!--begin::Global Theme Styles(used by all pages)-->
<link href="{{url('public/backend/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
<!--end::Global Theme Styles-->
<!--begin::Layout Themes(used by all pages)-->
<link href="{{url('public/backend/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('public/backend/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
<!--end::Layout Themes-->
@endif
<style>
    .aside-menu .menu-nav>.menu-item>.menu-heading .menu-text, .aside-menu .menu-nav>.menu-item>.menu-link .menu-text {
        font-size: 1.3rem;
    }
    .card.card-custom, .form-group label, .table.table-head-custom thead th, .table.table-head-custom thead tr{
        font-size: 1.3rem;
    }
    body{
        font-size: 1.3rem;
    }
</style>

@if ($lang_code == 'ar')
<style>
    .bootstrap-select .dropdown-toggle .filter-option-inner-inner{
        text-align: right;
    }
</style>
    
@endif
<link rel="shortcut icon" href="{{url('public/backend/media/logos/favicon.ico')}}" />


<script>
    var config = {
        url: "{{url('')}}",
        admin_url: " {{ url('admin')}}",
        public_path: " {{ url('public/')}}",
        lang_code: "{{$lang_code}}",
        languages: '{!!json_encode(array_keys($languages))!!}',
    }
    var lang = {
        filesize_can_not_be_more_than: "{{ _lang('app.filesize_can_not_be_more_than')}}",
        save: "{{ _lang('app.save')}}",
        choose: "{{ _lang('app.choose')}}",
        delete: "{{ _lang('app.delete')}}",
        message: "{{ _lang('app.message')}}",
        send: "{{ _lang('app.send')}}",
        active: "{{ _lang('app.active')}}",
        not_active: "{{ _lang('app.not_active')}}",
        close: "{{ _lang('app.close')}}",
        save: "{{ _lang('app.save')}}",
        save_changes: "{{ _lang('app.save_changes')}}",
        delete: "{{ _lang('app.delete')}}",
        required_rule: "{{ _lang('app.this_field_is_required')}}",
        email_rule: "{{ _lang('app.please_enter_a_valid_email')}}",
        number_rule: "{{ _lang('app.numbers_only_are_allowed_for_this_input')}}",
        yes: "{{ _lang('app.yes')}}",
        no: "{{ _lang('app.no')}}",
        error: "{{ _lang('app.error')}}",
        are_you_sure_you_want_to_delete_this: "{{ _lang('app.are_you_sure_you_want_to_delete_this') }}",
    };
</script>