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
        'image' => 'string',
        'company_rates_count' => 'integer',
        'allowed_to_rate' => 'boolean',
        'type' => 'integer'
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

    public function rates()
    {
        return $this->hasMany(Rate::class, 'company_id');
    }

    public function rated()
    {
        return $this->hasMany(Rate::class, 'user_id');
    }


    public function transform()
    {
        $transformer = new \stdClass();
        if ($this->type == $this->types['client']) {
            $transformer->name = $this->name;
        }
        $transformer->email = $this->email;
        $transformer->mobile = $this->mobile ?: "";
        $transformer->country_id = $this->country_id;
        $transformer->city_id = $this->city_id;
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            $transformer->image = $this->image;
        } else {
            $transformer->image = url("public/uploads/users/$this->image");
        }
        $transformer->type = $this->type;
        if ($this->type == $this->types['company']) {
            $companyDetails = $this->companyDetails;
            $userSubscription = $this->subscriptions()->latest()->first();
            if ($companyDetails->available_free_posts != 0) {
                $transformer->allowed_to_post = true;
            } else {
                if ($userSubscription && $userSubscription->end_date >= date('Y-m-d')) {
                    $transformer->allowed_to_post = true;
                } else {
                    $transformer->allowed_to_post = false;
                }
            }
            $transformer->is_featured = $userSubscription && $userSubscription->end_date >= date('Y-m-d') ? true : false;
            $transformer->company_details = $companyDetails->transform();
            $transformer->posts_count = $this->posts()->count();
            $transformer->followers_count = $this->followers()->count();
        }
        $transformer->followings_count = $this->followings()->count();
        $transformer->likes_count = $this->likes()->count();
        return $transformer;
    }

    public function transformCompaniesList()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->company_id;
        $transformer->image = url("public/uploads/users/$this->image");
        $transformer->country = $this->country;
        $transformer->city = $this->city;
        $transformer->is_featured = $this->is_featured ? true : false;
        return $transformer;
    }

    public function transformCompanyDetails()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->company_id;
        $transformer->email = $this->email;
        $transformer->image = url("public/uploads/users/$this->image");
        $transformer->country = $this->country;
        $transformer->city = $this->city;
        $transformer->rates_count = $this->company_rates_count;
        $transformer->allowed_to_rate = $this->allowed_to_rate;
        $transformer->is_featured = $this->is_featured ? true : false;
        if ($this->auth_user()) {
            $transformer->is_rated = $this->is_rated ? true : false;
            $transformer->is_followed = $this->is_followed ? true : false;
        }
        $transformer->name_ar = $this->name_ar;
        $transformer->name_en = $this->name_en;
        $transformer->description = $this->description;
        $transformer->mobile = $this->mobile;
        $transformer->whatsapp = $this->whatsapp;
        $transformer->facebook = $this->facebook;
        $transformer->twitter = $this->twitter;
        $transformer->website = $this->website;
        $transformer->lat = $this->lat;
        $transformer->lng = $this->lng;

        return $transformer;
    }
    

   
    
    protected static function boot() {
        parent::boot();
        static::deleting(function (User $user) {
            foreach ($user->devices as $device) {
                $device->delete();
            }
            foreach ($user->subscriptions as $subscription) {
                $subscription->delete();
            }
            foreach ($user->posts as $post) {
                $post->delete();
            }
            foreach ($user->likes as $like) {
                $like->delete();
            }
            foreach ($user->followers as $follower) {
                $follower->delete();
            }
            foreach ($user->followings as $following) {
                $following->delete();
            }
            foreach ($user->rates as $rate) {
                $rate->delete();
            }
            foreach ($user->rated as $rate) {
                $rate->delete();
            }
            

            if ($user->type = $user->types['company']) {
                $user->companyDetails->delete();
            }
        });
        
        static::deleted(function(User $user) {
            if ($user->image != 'default.png') {
                $user->deleteUploaded('users', $user->image);
            }
        });
    }
   

}
