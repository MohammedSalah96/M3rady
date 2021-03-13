<?php

namespace App\Repositories\Backend\CategoryTranslation;

use Illuminate\Http\Request;
use App\Models\CategoryTranslation;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseTranslationRepositoryInterface;

class CategoryTranslationRepository extends BaseRepository implements BaseTranslationRepositoryInterface, CategoryTranslationRepositoryInterface{

   private $categoryTranslation;

   public function __construct(CategoryTranslation $categoryTranslation)
   {
      parent::__construct();
      $this->categoryTranslation =  $categoryTranslation;
   }

   public function getTranslations($category)
   {
      return $this->categoryTranslation->where('category_id', $category->id)->get()->keyBy('locale');
   }

  public function getTranslation($id)
   {
     return $this->categoryTranslation->where('category_id',$id)->where('locale',$this->langCode)->first();
   }

   public function create(Request $request, $category)
   {
      $categoryTranslations = array();
      $name = $request->input('name');
      foreach ($this->languages as $key => $value) {
         $categoryTranslations[] = array(
            'locale' => $key,
            'name' => $name[$key],
            'category_id' => $category->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
         );
      }
      $this->categoryTranslation->insert($categoryTranslations);
   }

   public function update(Request $request, $category)
   {
      $this->delete($category);
      $this->create($request,$category);
   }

   public function delete($category)
   {
      return $this->categoryTranslation->where('category_id',$category->id)->delete();
   }

   
   

}