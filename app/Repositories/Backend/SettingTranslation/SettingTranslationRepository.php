<?php

namespace App\Repositories\Backend\SettingTranslation;

use Illuminate\Http\Request;
use App\Models\SettingTranslation;
use App\Repositories\Backend\BaseRepository;

class SettingTranslationRepository extends BaseRepository implements SettingTranslationRepositoryInterface{

    private $settingTranslation;

   public function __construct(SettingTranslation $settingTranslation)
   {
       parent::__construct();
       $this->settingTranslation = $settingTranslation;
   }
   
   public function all()
   {
       return $this->settingTranslation->get()->keyBy('locale');
   }
   
   public function update(Request $request)
   {
        $policy = $request->input('policy');
        $aboutUs = $request->input('about_us');
        foreach ($this->languages as $language) {
            $this->settingTranslation->updateOrCreate(
                ['locale' => $language],
                [
                    'policy' => $policy[$language],
                    'about_us' => $aboutUs[$language],
                ]
            );
        }
   }

}
