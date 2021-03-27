<?php

namespace App\Repositories\Backend\Location;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class LocationRepository extends BaseRepository implements BaseRepositoryInterface, LocationRepositoryInterface{

   private $location;

   public function __construct(Location $location)
   {
       parent::__construct();
       $this->location =  $location;
   }

   public function all(array $conditions = [])
   {
      $locations =  $this->location->join('location_translations', function ($query) {
         $query->on('locations.id', '=', 'location_translations.location_id')
            ->where('location_translations.locale', $this->langCode);
      });

      if (!empty($conditions)) {
         $locations->where($conditions);
      }     
      
      return $locations = $locations->select('locations.*', 'location_translations.name')->get();
   }

   public function getByParent($parentId = 0){
      return $this->all([
         ['parent_id', '=', $parentId]
      ]);
   }

   public function tree($id)
   {
      $tree = null;
      $location = $this->find($id);
      if ($location) {
         $parents_ids = explode(',', $location->parents_ids);
         $parents_ids[] = $id;
         $tree = $this->location->join('location_translations', function ($query) {
                                    $query->on('locations.id', '=', 'location_translations.location_id')
                                       ->where('location_translations.locale', $this->langCode);
                                 })
                                 ->whereIn('locations.id', $parents_ids)
                                 ->orderBy('locations.id', 'asc')
                                 ->select('locations.id', 'location_translations.name')
                                 ->get();
      }
      return $tree;

   }

   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->location->where($conditions)->where('id',$id)->first();
      }
      return $this->location->find($id);
   }

   public function create(Request $request)
   {
      $location = new $this->location;
      $location->parent_id = $request->input('parent_id');
      $location->active = $request->input('active');
      $location->position = $request->input('position');
      if ($request->input('parent_id')) {
         $parent = $this->find($request->input('parent_id'));
         $location->level = $parent->level + 1;
         if ($parent->parents_ids == null) {
            $location->parents_ids = $parent->id;
         } else {
            $parent_ids = explode(',', $parent->parents_ids);
            array_push($parent_ids,$parent->id);
            $location->parents_ids = implode(',', $parent_ids);
         }
      } else {
         $location->level = 1;
      }
      $location->save();
      return $location;
   }

   public function update(Request $request, $id, $location)
   {
      $location->active = $request->input('active');
      $location->position = $request->input('position');
      $location->save(); 
      return $location;
   }

   public function delete(Request $request, $id, $location)
   {
      return $location->delete();
   }

   public function dataTable(Request $request)
   {
      $parentId = $request->input('parent_id');
      return $this->location->join('location_translations', function ($query) {
                              $query->on('locations.id', '=', 'location_translations.location_id')
                                 ->where('location_translations.locale', $this->langCode);
                              })
                              ->where('locations.parent_id', $parentId)
                              ->select('locations.*', 'location_translations.name');
   }
   

}