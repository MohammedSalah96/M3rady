<?php

namespace App\Repositories\Backend\PackageSubscription;

use App\Models\PackageSubscription;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class PackageSubscriptionRepository extends BaseRepository implements  PackageSubscriptionRepositoryInterface{

   private $packageSubscription;

   public function __construct(PackageSubscription $packageSubscription)
   {
      parent::__construct();
      $this->packageSubscription =  $packageSubscription;
   }

   public function statistics()
   {
      return $this->packageSubscription->all()->sum('price');
   }

   public function dataTable(Request $request)
   {
      $packageSubscriptions =  $this->packageSubscription->join('company_details', 'package_subscriptions.user_id', '=', 'company_details.user_id')
                                                         ->join('package_translations', function ($query) {
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
                          
      return $packageSubscriptions = $packageSubscriptions->select('package_subscriptions.*','company_details.company_id', 'package_translations.name as package');
       
   }
   

}