<?php

namespace App\Repositories\Backend\WelcomeScreen;

use App\Models\WelcomeScreen;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class WelcomeScreenRepository extends BaseRepository implements BaseRepositoryInterface, WelcomeScreenRepositoryInterface{

   private $welcomeScreen;

   public function __construct(WelcomeScreen $welcomeScreen)
   {
       parent::__construct();
       $this->welcomeScreen =  $welcomeScreen;
   }


   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->welcomeScreen->where($conditions)->where('id',$id)->first();
      }
      return $this->welcomeScreen->find($id);
   }

   public function create(Request $request)
   {
      $welcomeScreen = new $this->welcomeScreen;
      $welcomeScreen->active = $request->input('active');
      $welcomeScreen->position = $request->input('position');
      $welcomeScreen->image = $this->welcomeScreen->upload($request->file('image'), 'welcome_screens');
      $welcomeScreen->save();
      return $welcomeScreen;
   }

   public function update(Request $request, $id, $welcomeScreen)
   {
      $welcomeScreen->active = $request->input('active');
      $welcomeScreen->position = $request->input('position');
      if ($request->file('image')) {
         $this->welcomeScreen->deleteUploaded('welcome_screens', $welcomeScreen->image);
         $welcomeScreen->image = $this->welcomeScreen->upload($request->file('image'), 'welcome_screens');
      }
      $welcomeScreen->save(); 
      return $welcomeScreen;
   }

   public function delete(Request $request, $id, $welcomeScreen)
   {
      return $welcomeScreen->delete();
   }

   public function dataTable(Request $request)
   {
      return $this->welcomeScreen->join('welcome_screen_translations', function ($query) {
                              $query->on('welcome_screens.id', '=', 'welcome_screen_translations.welcome_screen_id')
                                 ->where('welcome_screen_translations.locale', $this->langCode);
                              })
                              ->select('welcome_screens.*', 'welcome_screen_translations.description');
   }
   

}