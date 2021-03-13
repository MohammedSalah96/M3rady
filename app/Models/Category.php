<?php

namespace App\Models;

class Category extends MyModel {
 
    protected $table = "categories";
   
    public function translations() {
        return $this->hasMany(CategoryTranslation::class, 'category_id');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    
    public function treeTransform(){
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->name;
        $transformer->childrens = $this->childrens ?: [];
        
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function(Category $category) {
            foreach ($category->translations as $translation) {
                $translation->delete();
            }
            foreach ($category->childs as $child) {
                $child->delete();
            }
            
        });
        
        static::deleted(function (Category $category) {
            $category->deleteUploaded('categories', $category->image);
        });
    }

}
