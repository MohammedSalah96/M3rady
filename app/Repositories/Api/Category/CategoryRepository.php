<?php

namespace App\Repositories\Api\Category;

use App\Models\Category;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class CategoryRepository extends BaseRepository implements BaseRepositoryInterface, CategoryRepositoryInterface
{

    private $category;
   

    public function __construct(Category $category)
    {
        Parent::__construct();
        $this->category = $category;
    }

    public function getTree()
    {
        $categories  = $this->category->join('category_translations',function($query){
            $query->on('categories.id','=', 'category_translations.category_id')
            ->where('category_translations.locale', $this->langCode);
        })
        ->where('categories.active',true)
        ->orderBy('categories.position')
        ->select('categories.*', 'category_translations.name')
        ->get();

        return $this->buildTree($categories);
    }
}
