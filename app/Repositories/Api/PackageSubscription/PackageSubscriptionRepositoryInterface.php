<?php

namespace App\Repositories\Api\PackageSubscription;


interface PackageSubscriptionRepositoryInterface
{
    public function subscribe($package);
    public function authSubscription();
}
