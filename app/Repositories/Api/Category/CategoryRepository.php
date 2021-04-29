<?php

namespace App\Repositories\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
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

    public function list(Request $request)
    {
        $categories = $this->category->join('category_translations as locale_translations', function($query){
            $query->on('categories.id','=', 'locale_translations.category_id')
            ->where('locale_translations.locale',$this->langCode);
        });
        if ($request->input('category')) {
            $categories->where('parent_id', $request->input('category'));
        }else{
            $categories->where('parent_id', 0);
        }
        if ($request->input('search')) {
            $categories->join('category_translations', function ($query) {
                $query->on('categories.id', '=', 'category_translations.category_id');
            })
            ->where('category_translations.name','like',"%".$request->input('search')."%");
        }
        $categories = $categories->orderBy('categories.position')->select('categories.*', 'locale_translations.name')->distinct()->get();

        
        return $categories;
        
       
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
