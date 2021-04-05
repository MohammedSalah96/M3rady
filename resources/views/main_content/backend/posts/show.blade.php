@extends('layouts.backend')

@section('pageTitle', _lang('app.posts'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="{{route('posts.index')}}">{{_lang('app.posts')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.post')}}</a>
</li>

@endsection

@section('js')
@endsection

@section('content')
<div class="container">
    <div class="card card-custom gutter-b col-md-6 m-auto">
        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Top-->
            <div class="d-flex align-items-center">
                <!--begin::Symbol-->
                <div class="symbol symbol-40 symbol-light-success mr-5">
                    <span class="symbol-label">
                        <img src="{{url("public/uploads/users/$post->company_image")}}" class="h-75 align-self-end" alt="">
                    </span>
                </div>
                <!--end::Symbol-->
                <!--begin::Info-->
                <div class="d-flex flex-column flex-grow-1">
                    <a href="#"
                        class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">{{$post->company_id}}</a>
                    <span class="text-muted font-weight-bold">{{$post->created_at->format('Y-m-d h:i a')}}</span>
                </div>
                <!--end::Info-->
            </div>
            <!--end::Top-->
            <!--begin::Bottom-->
            <div class="pt-4">
                <!--begin::Image-->
                <div id="demo" class="carousel slide" data-ride="carousel">
    
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        @php
                            $images = json_decode($post->images,true);
                        @endphp
                        @foreach ($images as $key => $image)
                            <li data-target="#demo" data-slide-to="{{$key}}" class="{{$key == 0 ? 'active' : ''}}"></li>
                        @endforeach
                    </ul>
    
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        @foreach ($images as $key => $image)
                        <div class="min-h-500px carousel-item bgi-no-repeat bgi-size-cover rounded min-h-265px {{$key == 0 ? 'active' : ''}}"
                            style="background-image: url({{url("public/uploads/posts/$image")}}); background-repeat: no-repeat; background-size: 100% 100%;">
                        </div>
                        @endforeach
                    </div>
    
                    <!-- Left and right controls -->
                    @if ($lang_code == 'ar')
                   <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    @else
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                    @endif
                    
                </div>
    
                <!--end::Image-->
                <!--begin::Text-->
                <p class="text-dark-75 font-size-lg font-weight-normal pt-5 mb-2">{{$post->description}}</p>
                <!--end::Text-->
                <!--begin::Action-->
                <div class="d-flex align-items-center">
                    <a target="_blank" href="{{Permissions::check('comments', 'open') ? route('comments.index',['post' => $post->id]) : '#'}}"
                        class="btn btn-hover-text-primary btn-hover-icon-primary bg-hover-light-primary btn-sm btn-text-dark-50 rounded font-weight-bolder font-size-sm p-2 mr-2">
                        <span class="svg-icon svg-icon-md svg-icon-primary pr-2">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path
                                        d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z"
                                        fill="#000000"></path>
                                    <path
                                        d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z"
                                        fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>{{$post->no_of_comments}}</a>
                    <a target="_blank" href="{{Permissions::check('likes', 'open') ? route('likes.index',['post' => $post->id]) : '#'}}"
                        class="btn btn-icon-danger btn-sm btn-text-dark-50 bg-hover-light-danger btn-hover-text-danger rounded font-weight-bolder font-size-sm p-2">
                        <span class="svg-icon svg-icon-md svg-icon-danger pr-1">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/General/Heart.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path
                                        d="M16.5,4.5 C14.8905,4.5 13.00825,6.32463215 12,7.5 C10.99175,6.32463215 9.1095,4.5 7.5,4.5 C4.651,4.5 3,6.72217984 3,9.55040872 C3,12.6834696 6,16 12,19.5 C18,16 21,12.75 21,9.75 C21,6.92177112 19.349,4.5 16.5,4.5 Z"
                                        fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>{{$post->no_of_likes}}</a>
                </div>
                <!--end::Action-->
            </div>
            <!--end::Bottom-->
        </div>
        <!--end::Body-->
    </div>
</div>

    
<script>
    var newConfig = {
       
    };
    var newLang = {
      
    };
</script>
@endsection
