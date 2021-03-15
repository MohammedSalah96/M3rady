<?php

namespace App\Repositories\Api\Location;

use App\Models\Location;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class LocationRepository extends BaseRepository implements BaseRepositoryInterface, LocationRepositoryInterface
{

    private $location;
   

    public function __construct(Location $location)
    {
        Parent::__construct();
        $this->location = $location;
    }

    public function getTree()
    {
        $locations  = $this->location->join('location_translations',function($query){
            $query->on('locations.id','=', 'location_translations.location_id')
            ->where('location_translations.locale', $this->langCode);
        })
        ->where('locations.active',true)
        ->orderBy('locations.position')
        ->select('locations.*', 'location_translations.name')
        ->get();

        return $this->buildTree($locations);
    }
}
