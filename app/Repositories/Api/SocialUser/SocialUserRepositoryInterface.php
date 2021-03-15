<?php

namespace App\Repositories\Api\SocialUser;

interface SocialUserRepositoryInterface
{
    public function checkSocialUser(array $credentials);
    public function get($socialUserDetails);
    public function checkEmail($email);
    public function create($socialUserDetails, $provider);
    
}
