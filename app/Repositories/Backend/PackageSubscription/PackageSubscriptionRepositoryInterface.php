<?php

namespace App\Repositories\Backend\PackageSubscription;

use Illuminate\Http\Request;

interface PackageSubscriptionRepositoryInterface{
   
    public function dataTable(Request $request);

}