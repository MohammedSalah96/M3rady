<?php

namespace App\Repositories\Api\CompanyDetails;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\CompanyDetails;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class CompanyDetailsRepository extends BaseRepository implements BaseRepositoryInterface, CompanyDetailsRepositoryInterface
{

    private $companyDetails;
    private $setting;

    public function __construct(CompanyDetails $companyDetails, Setting $setting)
    {
        Parent::__construct();
        $this->companyDetails = $companyDetails;
        $this->setting = $setting;
    }

    public function create(Request $request, $user)
    {
        $companyDetails = new $this->companyDetails;
        $companyDetails->user_id = $user->id;
        $companyDetails->company_id = $request->input('company_id');
        $companyDetails->name_ar = $request->input('name_ar');
        $companyDetails->name_en = $request->input('name_en');
        $companyDetails->description = $request->input('company_description');
        $companyDetails->main_category_id = $request->input('main_category');
        $companyDetails->sub_category_id = $request->input('sub_category');
        $companyDetails->lat = $request->input('lat');
        $companyDetails->lng = $request->input('lng');

        $companyDetails->whatsapp = $request->input('whatsapp') ?: "";
        $companyDetails->facebook = $request->input('facebook') ?: "";
        $companyDetails->twitter = $request->input('twitter') ?: "";
        $companyDetails->website = $request->input('website') ?: "";

        $companyDetails->available_free_posts = $this->setting->where('name', 'allowed_free_posts')->first()->value;

        $companyDetails->save();
    }

    public function update(Request $request, $user)
    {
        $companyDetails = $this->companyDetails->where('user_id', $user->id);
        if ($request->input('name_ar')) {
            $companyDetails->name_ar = $request->input('name_ar');
        }
        if ($request->input('name_en')) {
            $companyDetails->name_en = $request->input('name_en');
        }
        if ($request->input('company_description')) {
            $companyDetails->description = $request->input('company_description');
        }
        if ($request->input('main_category')) {
            $companyDetails->main_category_id = $request->input('main_category');
        }
        if ($request->input('sub_category')) {
            $companyDetails->sub_category_id = $request->input('sub_category');
        }
        if ($request->input('lat')) {
            $companyDetails->lat = $request->input('lat');
        }
        if ($request->input('lng')) {
            $companyDetails->lng = $request->input('lng');
        }
        if ($request->input('allowed_to_rate')) {
            $companyDetails->allowed_to_rate = $request->input('allowed_to_rate');
        }
        if ($request->input('whatsapp')) {
            $companyDetails->whatsapp = $request->input('whatsapp');
        }
        if ($request->input('facebook')) {
            $companyDetails->facebook = $request->input('facebook');
        }
        if ($request->input('twitter')) {
            $companyDetails->twitter = $request->input('twitter');
        }
        if ($request->input('website')) {
            $companyDetails->website = $request->input('website');
        }
        $companyDetails->save();
    }

    public function decreaseFreePosts($userId = null)
    {
        $comapnyDetails = $this->companyDetails->where('available_free_posts', '<>', 0);
        if ($userId) {
            $comapnyDetails->where('user_id', $userId);
        }else{
            $comapnyDetails->where('user_id', $this->authUser()->id);
        }
        $comapnyDetails->update(['available_free_posts' => \DB::raw('(available_free_posts - 1)')]);
    }
}
