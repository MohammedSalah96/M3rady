<?php

namespace App\Repositories\Backend\WelcomeScreenTranslation;

use Illuminate\Http\Request;
use App\Models\WelcomeScreenTranslation;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseTranslationRepositoryInterface;

class WelcomeScreenTranslationRepository extends BaseRepository implements BaseTranslationRepositoryInterface, WelcomeScreenTranslationRepositoryInterface{

   private $welcomeScreenTranslation;

   public function __construct(WelcomeScreenTranslation $welcomeScreenTranslation)
   {
      parent::__construct();
      $this->welcomeScreenTranslation =  $welcomeScreenTranslation;
   }

   public function getTranslations($welcomeScreen)
   {
      return $this->welcomeScreenTranslation->where('welcome_screen_id', $welcomeScreen->id)->get()->keyBy('locale');
   }

  public function getTranslation($id)
   {
     return $this->welcomeScreenTranslation->where('welcome_screen_id',$id)->where('locale',$this->langCode)->first();
   }

   public function create(Request $request, $welcomeScreen)
   {
      $welcomeScreenTranslations = array();
      $description = $request->input('description');
      foreach ($this->languages as $lang) {
         $welcomeScreenTranslations[] = array(
            'locale' => $lang,
            'description' => $description[$lang],
            'welcome_screen_id' => $welcomeScreen->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
         );
      }
      $this->welcomeScreenTranslation->insert($welcomeScreenTranslations);
   }

   public function update(Request $request, $welcomeScreen)
   {
      $this->delete($welcomeScreen);
      $this->create($request,$welcomeScreen);
   }

   public function delete($welcomeScreen)
   {
      return $this->welcomeScreenTranslation->where('welcome_screen_id',$welcomeScreen->id)->delete();
   }

   
   

}