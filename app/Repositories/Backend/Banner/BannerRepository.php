<?php

namespace App\Repositories\Backend\Banner;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class BannerRepository extends BaseRepository implements BaseRepositoryInterface, BannerRepositoryInterface{

   private $banner;

   public function __construct(Banner $banner)
   {
       parent::__construct();
       $this->banner =  $banner;
   }

   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->banner->where($conditions)->where('id',$id)->first();
      }
      return $this->banner->find($id);
   }

   public function create(Request $request)
   {
      $banner = new $this->banner;
      $banner->image = $this->banner->upload($request->file('image'), 'banners', false, false, false, true);
      $banner->active = $request->input('active');
      $banner->position = $request->input('position');

      $banner->save();
      return $banner;
   }

   public function update(Request $request, $id, $banner)
   {
      $banner->active = $request->input('active');
      $banner->position = $request->input('position');
      if ($request->file('image')) {
         $this->banner->deleteUploaded('banners', $banner->image);
         $banner->image = $this->banner->upload($request->file('image'), 'banners', false, false, false, true);
      }
      $banner->save(); 
      return $banner;
   }

   public function delete(Request $request, $id, $banner)
   {
      return $banner->delete();
   }

   public function dataTable(Request $request)
   {
      return $this->banner->select('banners.*');
   }
   

}