<?php

namespace App\Repositories\Backend\Setting;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface{

    private $setting;

   public function __construct(Setting $setting)
   {
       parent::__construct();
       $this->setting = $setting;
   }
   
   public function all()
   {
       return $this->setting->get()->keyBy('name');
   }
   
   public function update(Request $request)
   {
        $setting = $request->input('setting');
        foreach ($setting as $key => $value) {
            if ($key == 'social_media') {
                $this->setting->updateOrCreate(['name' => $key], ['value' => json_encode($value)]);
            } else {
                $this->setting->updateOrCreate(['name' => $key], ['value' => $value]);
            }
        }
   }

}
