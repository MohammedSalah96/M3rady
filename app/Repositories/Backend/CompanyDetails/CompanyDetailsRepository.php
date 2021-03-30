<?php

namespace App\Repositories\Backend\CompanyDetails;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\CompanyDetails;
use App\Repositories\Backend\BaseRepository;

class CompanyDetailsRepository extends BaseRepository implements CompanyDetailsRepositoryInterface
{

    private $companyDetails;

    public function __construct(CompanyDetails $companyDetails, Setting $setting)
    {
        Parent::__construct();
        $this->companyDetails = $companyDetails;
        $this->setting = $setting;
    }

    public function find($userId)
    {
       return $this->companyDetails->where('user_id',$userId)->first();
    }

    public function create(Request $request, $user)
    {
        $companyDetails = new $this->companyDetails;
        $companyDetails->user_id = $user->id;
        $companyDetails->company_id = $request->input('company_id');
        $companyDetails->name_ar = $request->input('name_ar');
        $companyDetails->name_en = $request->input('name_en');
        $companyDetails->description = $request->input('description');
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
        $companyDetails = $this->companyDetails->where('user_id', $user->id)->first();
        $companyDetails->company_id = $request->input('company_id');
        $companyDetails->name_ar = $request->input('name_ar');
        $companyDetails->name_en = $request->input('name_en');
        $companyDetails->description = $request->input('description');
        $companyDetails->main_category_id = $request->input('main_category');
        $companyDetails->sub_category_id = $request->input('sub_category');
        $companyDetails->lat = $request->input('lat');
        $companyDetails->lng = $request->input('lng');
        $companyDetails->allowed_to_rate = $request->input('allowed_to_rate');
        $companyDetails->whatsapp = $request->input('whatsapp');
        $companyDetails->facebook = $request->input('facebook');
        $companyDetails->twitter = $request->input('twitter');
        $companyDetails->website = $request->input('website');
        
        $companyDetails->save();
    }
}

