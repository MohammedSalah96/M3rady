<?php

namespace App\Repositories\Api\SettingTranslation;

use App\Models\SettingTranslation;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class SettingTranslationRepository extends BaseRepository implements BaseRepositoryInterface, SettingTranslationRepositoryInterface
{

    private $settingTranslation;

    public function __construct(SettingTranslation $settingTranslation)
    {
        Parent::__construct();
        $this->settingTranslation = $settingTranslation;
    }
    public function get(){
        return $this->settingTranslation->where('locale', $this->langCode)->first();
    }
    
}
