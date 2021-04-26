<?php

namespace App\Repositories\Backend\PackageSubscription;

use App\Models\PackageSubscription;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\Package\PackageRepositoryInterface;

class PackageSubscriptionRepository extends BaseRepository implements  PackageSubscriptionRepositoryInterface{

   private $packageSubscription;
   private $packageRepository;

   public function __construct(PackageSubscription $packageSubscription, PackageRepositoryInterface $packageRepository)
   {
      parent::__construct();
      $this->packageSubscription =  $packageSubscription;
      $this->packageRepository =  $packageRepository;
   }

   public function find($id)
   {
      return $this->packageSubscription->find($id);
   }

   public function create(Request $request){
      $packageSubscription = new $this->packageSubscription;
      $packageSubscription->user_id = $request->input('company');
      if ($request->input('type') == 'trial') {
         $packageSubscription->start_date = $request->input('start_date');
         $packageSubscription->end_date = $request->input('end_date');
      }
      else if ($request->input('type') == 'subscription') {
         $package = $this->packageRepository->find($request->input('package'));
         $packageSubscription->package_id = $package->id;
         $packageSubscription->price = $package->price;
         $packageSubscription->duration = $package->duration;
         $packageSubscription->start_date = date('Y-m-d');
         $packageSubscription->end_date = date('Y-m-d', strtotime("+$package->duration months"));
      }
      $packageSubscription->save();
   }

   public function delete(Request $request, $id, $packageSubscription)
   {
      return $packageSubscription->delete();
   }

   public function statistics()
   {
      return $this->packageSubscription->all()->sum('price');
   }

   public function dataTable(Request $request)
   {
      $packageSubscriptions =  $this->packageSubscription->join('company_details', 'package_subscriptions.user_id', '=', 'company_details.user_id')
                                                         ->leftJoin('package_translations', function ($query) {
                                                            $query->on('package_subscriptions.package_id', '=', 'package_translations.package_id')
                                                               ->where('package_translations.locale', $this->langCode);
                                                         });
      if ($request->input('package')) {
         $packageSubscriptions->where('package_subscriptions.package_id', $request->input('package'));
      }
      if ($request->input('company')) {
         $packageSubscriptions->where('package_subscriptions.user_id', $request->input('company'));
      }
      if ($request->input('from')) {
         $packageSubscriptions->whereDate('package_subscriptions.created_at', '>=', $request->input('from'));
      }
      if ($request->input('to')) {
         $packageSubscriptions->whereDate('package_subscriptions.created_at', '<=', $request->input('to'));
      }
      if ($request->input('type')) {
         if ($request->input('type') == 'trial') {
            $packageSubscriptions->whereNull('package_subscriptions.package_id');
         }else{
            $packageSubscriptions->whereNotNull('package_subscriptions.package_id');
         }
      }
                          
      return $packageSubscriptions = $packageSubscriptions->select('package_subscriptions.*','company_details.company_id', 'package_translations.name as package');
       
   }
   

}