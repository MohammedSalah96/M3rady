<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Setting\SettingRepositoryInterface;
use App\Repositories\Backend\SettingTranslation\SettingTranslationRepositoryInterface;

class SettingsController extends BackendController {

    private $settingRepository;
    private $settingTranslationRepository;
    public function __construct(SettingRepositoryInterface $settingRepository, SettingTranslationRepositoryInterface $settingTranslationRepository)
    {
        parent::__construct();
        $this->middleware('CheckPermission:settings,open', ['only' => ['index']]);
        $this->data['tab'] = 'settings';
        $this->settingRepository = $settingRepository;
        $this->settingTranslationRepository = $settingTranslationRepository;
    }

    private $rules = array(
        'setting.email' => 'required|email',
        'setting.phone' => 'required',
        'setting.allowed_free_posts' => 'required',
    );

    public function index() {

        $this->data['settings'] = $this->settingRepository->all();
        $this->data['settings_translations'] = $this->settingTranslationRepository->all();
        if (isset($this->data['settings']['social_media'])) {
            $this->data['settings']['social_media']= json_decode($this->data['settings']['social_media']->value);
        }
        return $this->_view('settings.index');
    }

    public function store(Request $request) {
        try {
            $columns_arr = array(
                'policy' => 'required'
            );
            $this->rules = array_merge($this->rules, $this->lang_rules($columns_arr));
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }
            DB::beginTransaction();
            $this->settingRepository->update($request);
            $this->settingTranslationRepository->update($request);
            DB::commit();
            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }



}
