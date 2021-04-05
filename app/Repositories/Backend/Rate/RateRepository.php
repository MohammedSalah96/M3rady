<?php

namespace App\Repositories\Backend\Rate;

use App\Models\Rate;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class RateRepository extends BaseRepository implements  RateRepositoryInterface{

   private $rate;

   public function __construct(Rate $rate)
   {
       parent::__construct();
       $this->rate =  $rate;
   }

   public function find($id)
   {
      return $this->rate->find($id);
   }

   public function update(Request $request, $id, $rate)
   {
      $rate->status = $request->input('status');
      $rate->save();
   }

   public function delete(Request $request, $id, $rate)
   {
      return $rate->delete();
   }

   public function dataTable(Request $request)
   {
      $status = $request->input('status');
      $rates =  $this->rate->join('users','rates.user_id','=','users.id')
                           ->leftJoin('company_details', 'users.id', '=', 'company_details.user_id')
                           ->join('company_details as companies','rates.company_id','=', 'companies.user_id');
                           if ($status) {
                             $rates->where('rates.status',$this->rate->statuses[$status]);
                           }
      if ($request->input('company')) {
         $rates->where('rates.company_id', $request->input('company'));
      }
      if ($request->input('from')) {
         $rates->whereDate('rates.created_at', '>=', $request->input('from'));
      }
      if ($request->input('to')) {
         $rates->whereDate('rates.created_at', '<=', $request->input('to'));
      }           
      return $rates = $rates->select('rates.*', 'users.name', 'company_details.company_id', 'companies.company_id as company');
       
   }
   

}