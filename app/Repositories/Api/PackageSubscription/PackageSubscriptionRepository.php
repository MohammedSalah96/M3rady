<?php

namespace App\Repositories\Api\PackageSubscription;

use App\Models\PackageSubscription;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class PackageSubscriptionRepository extends BaseRepository implements BaseRepositoryInterface, PackageSubscriptionRepositoryInterface
{

    private $packageSubscription;

    public function __construct(PackageSubscription $packageSubscription)
    {
        Parent::__construct();
        $this->packageSubscription = $packageSubscription;
    }

    public function subscribe($package)
    {
        $packageSubscription = new $this->packageSubscription;
        $packageSubscription->package_id = $package->id;
        $packageSubscription->user_id = $this->authUser()->id;
        $packageSubscription->price = $package->price;
        $packageSubscription->duration = $package->duration;
        $packageSubscription->start_date = date('Y-m-d');
        $packageSubscription->end_date = date('Y-m-d', strtotime("+$package->duration months"));
        $packageSubscription->save();
    }
}
