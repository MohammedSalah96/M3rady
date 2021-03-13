<?php

namespace App\Repositories\Backend\Package;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class PackageRepository extends BaseRepository implements BaseRepositoryInterface, PackageRepositoryInterface{

   private $package;

   public function __construct(Package $package)
   {
       parent::__construct();
       $this->package =  $package;
   }

   public function all(array $conditions = [])
   {
      $packages =  $this->package->join('package_translations', function ($query) {
         $query->on('packages.id', '=', 'package_translations.package_id')
            ->where('package_translations.locale', $this->langCode);
      });

      if (!empty($conditions)) {
         $packages->where($conditions);
      }     
      
      return $packages = $packages->select('packages.*', 'package_translations.name')->get();
   }

   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->package->where($conditions)->where('id',$id)->first();
      }
      return $this->package->find($id);
   }

   public function create(Request $request)
   {
      $package = new $this->package;
      $package->active = $request->input('active');
      $package->position = $request->input('position');
      $package->price = $request->input('price');
      $package->duration = $request->input('position');
      $package->save();
      return $package;
   }

   public function update(Request $request, $id, $package)
   {
      $package->active = $request->input('active');
      $package->position = $request->input('position');
      $package->price = $request->input('price');
      $package->duration = $request->input('position');
      $package->save(); 
      return $package;
   }

   public function delete(Request $request, $id, $package)
   {
      return $package->delete();
   }

   public function dataTable(Request $request)
   {
      return $this->package->join('package_translations', function ($query) {
                              $query->on('packages.id', '=', 'package_translations.package_id')
                                 ->where('package_translations.locale', $this->langCode);
                              })
                              ->select('packages.*', 'package_translations.name');
   }
   

}