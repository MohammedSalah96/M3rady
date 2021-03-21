<?php

namespace App\Repositories\Api\WelcomeScreen;

use App\Models\WelcomeScreen;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class WelcomeScreenRepository extends BaseRepository implements BaseRepositoryInterface, WelcomeScreenRepositoryInterface
{

    private $welcomeScreen;
   
    public function __construct(WelcomeScreen $welcomeScreen)
    {
        Parent::__construct();
        $this->welcomeScreen = $welcomeScreen;
    }
    public function list(){
        return $this->welcomeScreen->join('welcome_screen_translations', function ($query) {
                                    $query->on('welcome_screens.id', '=', 'welcome_screen_translations.welcome_screen_id')
                                        ->where('welcome_screen_translations.locale', $this->langCode);
                                    })
                                    ->where('welcome_screens.active', true)
                                    ->orderBy('welcome_screens.position')
                                    ->select('welcome_screens.*', 'welcome_screen_translations.description')
                                    ->get();
    }
   
}
