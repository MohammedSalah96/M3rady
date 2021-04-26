<?php

namespace App\Repositories\Api\CompanyCategory;

use Illuminate\Http\Request;
use App\Models\CompanyCategory;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class CompanyCategoryRepository extends BaseRepository implements BaseRepositoryInterface, CompanyCategoryRepositoryInterface
{

    private $companyCategory;

    public function __construct(CompanyCategory $companyCategory)
    {
        Parent::__construct();
        $this->companyCategory = $companyCategory;
    }

    public function create(array $categories, $user)
    {
        $companyCategories = [];
        foreach ($categories as $category) {
            $companyCategories[] = array(
                'company_id' => $user->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
        }
        $this->companyCategory->insert($companyCategories);
    }

    public function update(array $categories, $user)
    {
        $companyCategories = $this->companyCategory->where('company_id', $user->id)->get();
        $companyCategoriesIds = $companyCategories->pluck('category_id')->toArray();

        $categoriesToInsert = array_diff($categories, $companyCategoriesIds);
        $categoriesToDelete = array_diff($companyCategoriesIds, $categories);
        if (!empty($categoriesToInsert)) {
            $this->create($categoriesToInsert, $user);
        }
        if (!empty($categoriesToDelete)) {
            $this->delete($categoriesToDelete, $user);
        }
        
    }

    private function delete(array $categories, $user)
    {
        $this->companyCategory->where('company_id',$user->id)
                              ->whereIn('category_id', $categories)
                              ->delete();
    }
}
