<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>M3rady</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta content="M3rady" name="description" />
        <meta content="M3rady" name="author" />
        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Page Custom Styles-->
       <link href="{{url('public/backend/css/pages/login/classic/login-4.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
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
		<link rel="shortcut icon" href="{{url('public/backend/media/logos/favicon.ico')}}" />
        <script>
            var config = {
                admin_url: " {{ url('admin') }}",
                asset_url: " {{ url('public//') }}",
            };
            var lang = {
                required: "{{ _lang('app.this_field_is_required')}}",
                email_not_valid: "{{ _lang('app.email_is_not_valid')}}",
                send_reset_link: "{{ _lang('app.send_password_reset_link') }}",
                close: "{{ _lang('app.close') }}",
                reset_password: "{{ _lang('app.reset_password') }}",
                login: "{{ _lang('app.login') }}",
                request: "{{ _lang('app.request') }}"
                
            };
        </script>
       
    </head>
    <!-- END HEAD -->

    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
				<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('{{url('public/backend/media/bg/bg-3.jpg')}}');">
					<div class="login-form text-center p-7 position-relative overflow-hidden">
						<!--begin::Login Header-->
						<div class="d-flex flex-center mb-15">
							<a href="#">
								<img src="{{url('public/backend/media/logos/logo-letter-13.png')}}" class="max-h-75px" alt="" />
							</a>
                        </div>
                        @yield('content')
                    </div>
                </div>
			</div>
            
		</div>
        
        <script>
            var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };
        </script>
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{url('public/backend/plugins/global/plugins.bundle.js')}}"></script>
        <script src="{{url('public/backend/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
        <script src="{{url('public/backend/js/scripts.bundle.js')}}"></script>
        <!--end::Global Theme Bundle-->
        <!--begin::Page Scripts(used by this page)-->
       
        <!--end::Page Scripts(used by this page)-->
        
        <script src="{{url('public/backend/plugins/global/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/backend/plugins/global/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/backend/plugins/global/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/backend/plugins/global/bootbox/bootbox.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/backend/scripts/my.js')}}" type="text/javascript"></script>
        @yield('scripts')
        
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>