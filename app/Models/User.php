<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Interfaces\UserInterface;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements UserInterface {
    use Notifiable;
    use ModelTrait;

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'country_id' => 'integer',
        'city_id' => 'integer',
        'image' => 'string'
    ];

    public $types = [
        'client' => 1,
        'company' => 2
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id');
    }

    public function companyDetails()
    {
        return $this->hasOne(CompanyDetails::class, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(PackageSubscription::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    public function followings()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }


    public function transform()
    {
        $transformer = new \stdClass();
        if ($this->type == $this->types['client']) {
            $transformer->name = $this->name;
        }
        $transformer->email = $this->email;
        $transformer->mobile = $this->mobile;
        $transformer->country_id = $this->country_id;
        $transformer->city_id = $this->city_id;
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            $transformer->image = $this->image;
        } else {
            $transformer->image = url("public/uploads/users/$this->image");
        }
        if ($this->type == $this->types['company']) {
            $companyDetails = $this->companyDetails;
            if ($companyDetails->available_free_posts != 0) {
                $transformer->allowed_to_post = true;
            } else {
                $userSubscription = $this->subscriptions()->latest()->first();
                if ($userSubscription && $userSubscription->end_date >= date('Y-m-d')) {
                    $transformer->allowed_to_post = true;
                } else {
                    $transformer->allowed_to_post = false;
                }
            }
            $transformer->company_details = $companyDetails->transform();
            $transformer->posts_count = $this->posts()->count();
            $transformer->followers_count = $this->followers()->count();
        }
        $transformer->followings_count = $this->followings()->count();
        $transformer->likes_count = $this->likes()->count();
        return $transformer;
    }

   
    
    protected static function boot() {
        parent::boot();
        static::deleting(function ($user) {
            foreach ($user->devices as $device) {
                $device->delete();
            }
        });
        
        static::deleted(function(User $user) {
            if ($user->image != 'default.png') {
                $user->deleteUploaded('users', $user->image);
            }
        });
    }
   

}
