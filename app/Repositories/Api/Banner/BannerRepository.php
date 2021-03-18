<?php

namespace App\Repositories\Api\Banner;

use App\Models\Banner;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class BannerRepository extends BaseRepository implements BaseRepositoryInterface, BannerRepositoryInterface
{

    private $banner;

    public function __construct(Banner $banner)
    {
        Parent::__construct();
        $this->banner = $banner;
    }

    public function list()
    {
       $banners = $this->banner->where('active', true)->orderBy('position')->get();
        return preg_filter('/^/', url('public/uploads/banners') . '/', $banners->pluck('image')->toArray());
    }
}
