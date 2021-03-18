<?php

namespace App\Repositories\Api\PriceRequest;

use App\Models\PriceRequest;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class PriceRequestRepository extends BaseRepository implements BaseRepositoryInterface, PriceRequestRepositoryInterface
{

    private $priceRequest;

    public function __construct(PriceRequest $priceRequest)
    {
        Parent::__construct();
        $this->priceRequest = $priceRequest;
    }

    public function create(Request $request)
    {
        $priceRequest = new $this->priceRequest;
        $priceRequest->name = $request->input('name');
        $priceRequest->email = $request->input('email');
        $priceRequest->mobile = $request->input('mobile');
        $priceRequest->request = $request->input('request');
        $priceRequest->country_id = $request->input('country');
        $priceRequest->city_id = $request->input('city');
        $priceRequest->user_id = $this->authUser()->id;
        $priceRequest->company_id = $request->input('company');
        $priceRequest->save();

        return $priceRequest;
    }

    public function update(Request $request, $priceRequest)
    {
        $priceRequest->reply = $request->input('reply');
        $priceRequest->save();
        return $priceRequest;
    }

    public function list(Request $request){
       return $this->getPriceRequests($request)->paginate($this->limit);
    }

    public function find($id){
        return $this->getPriceRequests(null, $id)->first();
    }

    public function findForAuth($id){
        return $this->priceRequest->where('user_id',$this->authUser()->id)->where('id',$id)->first();
    }

    public function findForCompany($id)
    {
        return $this->priceRequest->where('company_id', $this->authUser()->id)->where('id', $id)->first();
    }

    

    public function delete($priceRequest)
    {
        return $priceRequest->delete();
    }

    public function getPriceRequests($request, $id = null)
    {
        $user = $this->authUser();
        $columns = [
            'price_requests.*',
            'country_translations.name as country',
            'city_translations.name as city',
            \DB::raw('(CASE WHEN price_requests.user_id = '.$user->id.' THEN 1 ELSE 0 END) as is_mine')
        ];
        $priceRequests = $this->priceRequest->join('location_translations as country_translations',function($query){
                                $query->on('price_requests.country_id','=','country_translations.location_id')
                                ->where('country_translations.locale',$this->langCode);
                            })
                            ->join('location_translations as city_translations',function($query){
                                $query->on('price_requests.city_id','=','city_translations.location_id')
                                ->where('city_translations.locale',$this->langCode);
                            });
                            if ($id) {
                                $priceRequests->where('price_requests.id',$id);
                            }else{
                                if ($request->input('type') == 1) {
                                //sent
                                    $priceRequests->join('users', function($query){
                                        $query->on('price_requests.company_id', '=', 'users.id')
                                         ->where('users.active',true);
                                    })
                                    ->join('company_details','users.id','=', 'company_details.user_id')
                                    ->join('location_translations as company_country_translations',function($query){
                                        $query->on('users.country_id','=','company_country_translations.location_id')
                                        ->where('company_country_translations.locale',$this->langCode);
                                    })
                                    ->join('location_translations as company_city_translations',function($query){
                                        $query->on('users.city_id','=','company_city_translations.location_id')
                                        ->where('company_city_translations.locale',$this->langCode);
                                    })
                                    ->where('price_requests.user_id',$user->id);  
                                    $columns = array_merge($columns, [
                                        'users.image',
                                        'company_details.company_id as company_name',
                                        'company_country_translations.name as company_country',
                                        'company_city_translations.name as company_city',
                                    ]);

                                }else if($request->input('type') == 2){
                                    //received
                                    $priceRequests->join('users', function($query){
                                        $query->on('price_requests.user_id', '=', 'users.id')
                                         ->where('users.active',true);
                                    })
                                    ->join('location_translations as user_country_translations',function($query){
                                        $query->on('users.country_id','=','user_country_translations.location_id')
                                        ->where('user_country_translations.locale',$this->langCode);
                                    })
                                    ->join('location_translations as user_city_translations',function($query){
                                        $query->on('users.city_id','=','user_city_translations.location_id')
                                        ->where('user_city_translations.locale',$this->langCode);
                                    })
                                    ->where('price_requests.company_id',$user->id);
                                     $columns = array_merge($columns, [
                                        'users.image',
                                        'users.name as user_name',
                                        'user_country_translations.name as user_country',
                                        'user_city_translations.name as user_city'
                                    ]);
                                }
                                $priceRequests->orderBy('price_requests.created_at','desc');
                               
                            }
        return $priceRequests = $priceRequests->select($columns);   
    }
}
