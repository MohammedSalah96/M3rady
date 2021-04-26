<?php

namespace App\Repositories\Backend\PackageSubscription;

use Illuminate\Http\Request;

interface PackageSubscriptionRepositoryInterface{
   
    public function dataTable(Request $request);
    public function create(Request $request);
    public function delete(Request $request, $id, $packageSubscription);
    public function find($id);
    public function statistics();

}