<?php

namespace App\Repositories\Backend\PackageTranslation;

use Illuminate\Http\Request;
use App\Models\PackageTranslation;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseTranslationRepositoryInterface;

class PackageTranslationRepository extends BaseRepository implements BaseTranslationRepositoryInterface, PackageTranslationRepositoryInterface{

   private $packageTranslation;

   public function __construct(PackageTranslation $packageTranslation)
   {
      parent::__construct();
      $this->packageTranslation =  $packageTranslation;
   }

   public function getTranslations($package)
   {
      return $this->packageTranslation->where('package_id', $package->id)->get()->keyBy('locale');
   }

  public function getTranslation($id)
   {
     return $this->packageTranslation->where('package_id',$id)->where('locale',$this->langCode)->first();
   }

   public function create(Request $request, $package)
   {
      $packageTranslations = array();
      $name = $request->input('name');
      $description = $request->input('description');
      foreach ($this->languages as $key => $value) {
         $packageTranslations[] = array(
            'locale' => $key,
            'name' => $name[$key],
            'description' => $description[$key],
            'package_id' => $package->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
         );
      }
      $this->packageTranslation->insert($packageTranslations);
   }

   public function update(Request $request, $package)
   {
      $this->delete($package);
      $this->create($request,$package);
   }

   public function delete($package)
   {
      return $this->packageTranslation->where('package_id',$package->id)->delete();
   }

   
   

}