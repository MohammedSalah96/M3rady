<?php

namespace App\Repositories\Backend\CompanyCategory;

use Illuminate\Http\Request;
use App\Models\CompanyCategory;
use App\Repositories\Backend\BaseRepository;

class CompanyCategoryRepository extends BaseRepository implements CompanyCategoryRepositoryInterface
{

    private $companyCategory;

    public function __construct(CompanyCategory $companyCategory)
    {
        Parent::__construct();
        $this->companyCategory = $companyCategory;
    }

    public function getForCompany($company)
    {
        return $this->companyCategory->join('categories','categories.id','=','company_categories.category_id')
        ->where('company_id', $company->id)
        ->select('category_id','parent_id')
        ->get();
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
