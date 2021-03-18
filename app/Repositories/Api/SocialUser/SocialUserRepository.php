<?php

namespace App\Repositories\Api\SocialUser;

use Socialite;
use App\Models\User;
use App\Models\SocialUser;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class SocialUserRepository extends BaseRepository implements BaseRepositoryInterface, SocialUserRepositoryInterface
{

    private $socialUser;
    private $user;

    public function __construct(SocialUser $socialUser, User $user)
    {
        Parent::__construct();
        $this->socialUser = $socialUser;
        $this->user = $user;
    }

    public function checkSocialUser(array $credentials)
    {
        switch ($credentials['provider']) {
            case 'facebook':
                $socialUser = Socialite::driver('facebook')->fields([
                    'name',
                    'first_name',
                    'last_name',
                    'email'
                ]);
                break;
            case 'google':
                $socialUser = Socialite::driver('google')
                    ->scopes(['profile', 'email']);
                break;
            case 'apple':
                $socialUser = Socialite::driver('apple')->scopes(['name', 'email']);
                break;

            default:
                $socialUser = null;
        }
        return $socialUser ? $socialUser->userFromToken($credentials['access_token']) : false;
    }

    public function get($socialUserDetails)
    {

        return $this->user->join('social_users', 'users.id', '=', 'social_users.user_id')
            ->where('social_users.provider_user_id', $socialUserDetails->id)
            ->where('users.active', true)
            ->select('users.*')
            ->first();
    }

    public function checkEmail($email)
    {
        return $this->user->leftJoin('social_users', 'users.id', '=', 'social_users.user_id')
            ->where('users.email', $email)
            ->select('users.*', 'social_users.provider_user_id')
            ->first();
    }

    public function create($socialUserDetails, $provider)
    {

        $user = new $this->user;
        /*switch ($provider) {
            case 'facebook':
                //$user->first_name = $socialUserDetails->user['first_name'];
                //$user->last_name = $socialUserDetails->user['last_name'];
                break;
            case 'google':
                //$user->first_name = $socialUserDetails->user['given_name'];
                //$user->last_name = $socialUserDetails->user['family_name'];
                break;
            default:
        }*/

        $user->name = $socialUserDetails->name  ?: "";
        $user->email = $socialUserDetails->email;
        $user->image = $socialUserDetails->avatar ?: "default.png";
        $user->type = $this->user->types['client'];
        $user->save();

        $socialUser = new $this->socialUser;
        $socialUser->provider = $provider;
        $socialUser->provider_user_id = $socialUserDetails->id;
        $socialUser->user_id = $user->id;
        $socialUser->save();

        return $user;
    }

    
}
