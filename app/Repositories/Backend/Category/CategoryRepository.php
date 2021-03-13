<?php

namespace App\Repositories\Backend\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class CategoryRepository extends BaseRepository implements BaseRepositoryInterface, CategoryRepositoryInterface{

   private $category;

   public function __construct(Category $category)
   {
       parent::__construct();
       $this->category =  $category;
   }

   public function all(array $conditions = [])
   {
      $categories =  $this->category->join('category_translations', function ($query) {
         $query->on('categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $this->langCode);
      });

      if (!empty($conditions)) {
         $categories->where($conditions);
      }     
      
      return $categories = $categories->select('categories.*', 'category_translations.name')->get();
   }

   public function tree($id)
   {
      $tree = null;
      $category = $this->find($id);
      if ($category) {
         $parents_ids = explode(',', $category->parents_ids);
         $parents_ids[] = $id;
         $tree = $this->category->join('category_translations', function ($query) {
                                    $query->on('categories.id', '=', 'category_translations.category_id')
                                       ->where('category_translations.locale', $this->langCode);
                                 })
                                 ->whereIn('categories.id', $parents_ids)
                                 ->orderBy('categories.id', 'asc')
                                 ->select('categories.id', 'category_translations.name')
                                 ->get();
      }
      return $tree;

   }

   public function find($id, array $conditions = [])
   {
      if (!empty($conditions)) {
         return $this->category->where($conditions)->where('id',$id)->first();
      }
      return $this->category->find($id);
   }

   public function create(Request $request)
   {
      $category = new $this->category;
      $category->parent_id = $request->input('parent_id');
      $category->image = $this->category->upload($request->file('image'), 'categories');
      $category->active = $request->input('active');
      $category->position = $request->input('position');
      if ($request->input('parent_id')) {
         $parent = $this->find($request->input('parent_id'));
         $category->level = $parent->level + 1;
         if ($parent->parents_ids == null) {
            $category->parents_ids = $parent->id;
         } else {
            $parent_ids = explode(',', $parent->parents_ids);
            array_push($parent_ids,$parent->id);
            $category->parents_ids = implode(',', $parent_ids);
         }
      } else {
         $category->level = 1;
      }

      $category->save();
      return $category;
   }

   public function update(Request $request, $id, $category)
   {
      $category->active = $request->input('active');
      $category->position = $request->input('position');
      if ($request->file('image')) {
         $this->category->deleteUploaded('categories', $category->image);
         $category->image = $this->category->upload($request->file('image'), 'categories');
      }
      $category->save(); 
      return $category;
   }

   public function delete(Request $request, $id, $category)
   {
      return $category->delete();
   }

   public function dataTable(Request $request)
   {
      $parentId = $request->input('parent_id');
      return $this->category->join('category_translations', function ($query) {
                              $query->on('categories.id', '=', 'category_translations.category_id')
                                 ->where('category_translations.locale', $this->langCode);
                              })
                              ->where('categories.parent_id', $parentId)
                              ->select('categories.*', 'category_translations.name');
   }
   

}