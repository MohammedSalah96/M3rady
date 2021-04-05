@extends('layouts.backend')

@section('pageTitle',_lang('app.settings'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{url('admin')}}" class="text-muted">{{_lang('app.dashboard')}}</a>
</li>
<li class="breadcrumb-item">
    <a href="#">{{_lang('app.settings')}}</a>
</li>
@endsection

@section('js')
<script src="{{url('public/backend/scripts/settings.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<form role="form" class="form" id="editSettingsForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="card card-custom col-12 mb-3">
            <div class="card-header">
                <h3 class="card-title">{{_lang('app.contact_info') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>{{_lang('app.email') }}<span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-solid" id="email" name="setting[email]"
                            value="{{ isset($settings['email']) ? $settings['email']->value : '' }}"
                            placeholder="{{_lang('app.email') }}">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="form-group col-6">
                        <label>{{_lang('app.phone') }}<span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-solid" id="phone" name="setting[phone]"
                            value="{{ isset($settings['phone']) ? $settings['phone']->value : '' }}"
                            placeholder="{{_lang('app.phone') }}">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="form-group col-6">
                        <label>{{_lang('app.allowed_free_posts') }}<span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-solid" id="allowed_free_posts"
                            name="setting[allowed_free_posts]"
                            value="{{ isset($settings['allowed_free_posts']) ? $settings['allowed_free_posts']->value : '' }}"
                            placeholder="{{_lang('app.allowed_free_posts') }}">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom col-12 mb-3">
            <div class="card-header">
                <h3 class="card-title">{{_lang('app.policy') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($languages as $key => $value)
                    <div class="form-group col-6">
                        <label for="policy">{{_lang('app.policy') }} {{ _lang('app.'.$value) }}<span
                                class="text-danger">*</span></label>
                        <textarea class="form-control form-control-solid" id="policy[{{ $key }}]"
                            name="policy[{{ $key }}]" cols="30" rows="10"
                            placeholder="{{_lang('app.policy') }} {{ _lang('app.'.$value) }}">{{isset($settings_translations[$key])?$settings_translations[$key]->policy:''}}</textarea>
                        <span class="invalid-feedback"></span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card card-custom col-12">
            <div class="card-header">
                <h3 class="card-title">{{_lang('app.social_media') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="setting[social_media][facebook]">{{_lang('app.facebook') }}<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-solid" id="setting[social_media][facebook]"
                            name="setting[social_media][facebook]"
                            value="{{ isset($settings['social_media']->facebook) ? $settings['social_media']->facebook :'' }}"
                            placeholder="{{_lang('app.facebook') }}">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="form-group col-6">
                        <label for="setting[social_media][twitter]">{{_lang('app.twitter') }}<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-solid" id="setting[social_media][twitter]"
                            name="setting[social_media][twitter]"
                            value="{{ isset($settings['social_media']->twitter) ? $settings['social_media']->twitter :'' }}"
                            placeholder="{{_lang('app.twitter') }}">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                        <button class="btn btn-primary submit-form">{{_lang('app.save')}}</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</form>

@endsection