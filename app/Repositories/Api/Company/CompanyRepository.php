<?php

namespace App\Repositories\Api\Company;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class CompanyRepository extends BaseRepository implements BaseRepositoryInterface, CompanyRepositoryInterface
{

    private $company;
   
    public function __construct(User $company)
    {
        Parent::__construct();
        $this->company = $company;
    }

    public function list(Request $request){
        $user = $this->authUser();
        $companies =  $this->company->join('company_details','users.id','=', 'company_details.user_id')
                                ->join('package_subscriptions', function ($query) {
                                    $query->on('users.id', '=', 'package_subscriptions.user_id')
                                        ->where('package_subscriptions.id', \DB::raw('(select max(id) from package_subscriptions where user_id = users.id)'))
                                        ->whereDate('package_subscriptions.end_date', '>=', date('Y-m-d'));
                                })->join('location_translations as country_translations',function($query){
                                $query->on('users.country_id','=','country_translations.location_id')
                                ->where('country_translations.locale',$this->langCode);
                                })
                                ->join('location_translations as city_translations',function($query){
                                    $query->on('users.city_id','=','city_translations.location_id')
                                    ->where('city_translations.locale',$this->langCode);
                                });
                                
                                if ($user && $user->type == $user->types['company']) {
                                    $companies->where('users.id','<>',$user->id);
                                }
                                
                                if (!$request->input('feed')) {
                                     if ($request->input('country')) {
                                    $companies->where('users.country_id',$request->input('country'));
                                    }
                                    if ($request->input('city')) {
                                        $companies->where('users.city_id',$request->input('city'));
                                    }
                                    if ($request->input('category')) {
                                        $companies->where('company_details.main_category_id',$request->input('category'));
                                    }
                                }
                                $companies->select(
                                        'users.id',
                                        'users.image',
                                        'company_details.company_id',
                                        'country_translations.name as country',
                                        'city_translations.name as city',
                                        'package_subscriptions.id as is_featured');
        if ($request->input('feed')) {
             $companies = $companies->get();
            if ($companies->count() > 4) {
               return $companies->random(4);
            }else{
                return $companies;
            }
        }
        return $companies = $companies->paginate($this->limit);
    }

    public function find($id)
    {
        $user = $this->authUser();
        $columns =  [
            'company_details.*',
            'users.id',
            'users.image',
            'users.mobile',
            'users.email',
            'package_subscriptions.id as is_featured',
            'country_translations.name as country',
            'city_translations.name as city',
            \DB::raw("(select count(*) from rates where status = 1 and company_id = $id ) as company_rates_count")
        ];
        $company =  $this->company->join('company_details','users.id','=', 'company_details.user_id')
                                ->leftJoin('package_subscriptions', function ($query) {
                                    $query->on('users.id', '=', 'package_subscriptions.user_id')
                                        ->where('package_subscriptions.id', \DB::raw('(select max(id) from package_subscriptions where user_id = users.id)'))
                                        ->whereDate('package_subscriptions.end_date', '>=', date('Y-m-d'));
                                })
                                ->join('location_translations as country_translations',function($query){
                                $query->on('users.country_id','=','country_translations.location_id')
                                ->where('country_translations.locale',$this->langCode);
                                })
                                ->join('location_translations as city_translations',function($query){
                                    $query->on('users.city_id','=','city_translations.location_id')
                                    ->where('city_translations.locale',$this->langCode);
                                });
                                if ($user) {
                                    $company->leftJoin('rates', function ($query) use($user){
                                        $query->on('users.id', '=', 'rates.company_id')
                                            ->where('rates.user_id', $user->id);
                                    })
                                    ->leftJoin('followers', function ($query) use($user){
                                        $query->on('users.id', '=', 'followers.following_id')
                                            ->where('followers.follower_id', $user->id);
                                    });
                                    $columns = array_merge($columns,['rates.id as is_rated', 'followers.id as is_followed']);
                                }
        return $company = $company->select($columns)->where('users.id', $id)->first();

    }

    
}
