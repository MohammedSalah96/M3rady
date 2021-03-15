<?php

namespace App\Repositories\Backend\LocationTranslation;

use Illuminate\Http\Request;
use App\Models\LocationTranslation;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseTranslationRepositoryInterface;

class LocationTranslationRepository extends BaseRepository implements BaseTranslationRepositoryInterface, LocationTranslationRepositoryInterface{

   private $locationTranslation;

   public function __construct(LocationTranslation $locationTranslation)
   {
      parent::__construct();
      $this->locationTranslation =  $locationTranslation;
   }

   public function getTranslations($location)
   {
      return $this->locationTranslation->where('location_id', $location->id)->get()->keyBy('locale');
   }

  public function getTranslation($id)
   {
     return $this->locationTranslation->where('location_id',$id)->where('locale',$this->langCode)->first();
   }

   public function create(Request $request, $location)
   {
      $locationTranslations = array();
      $name = $request->input('name');
      foreach ($this->languages as $lang) {
         $locationTranslations[] = array(
            'locale' => $lang,
            'name' => $name[$lang],
            'location_id' => $location->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
         );
      }
      $this->locationTranslation->insert($locationTranslations);
   }

   public function update(Request $request, $location)
   {
      $this->delete($location);
      $this->create($request,$location);
   }

   public function delete($location)
   {
      return $this->locationTranslation->where('location_id',$location->id)->delete();
   }

   
   

}