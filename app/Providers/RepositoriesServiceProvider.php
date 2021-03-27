<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Backend\Admin\AdminRepositoryInterface',
            'App\Repositories\Backend\Admin\AdminRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Group\GroupRepositoryInterface',
            'App\Repositories\Backend\Group\GroupRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Setting\SettingRepositoryInterface',
            'App\Repositories\Backend\Setting\SettingRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\SettingTranslation\SettingTranslationRepositoryInterface',
            'App\Repositories\Backend\SettingTranslation\SettingTranslationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\ContactMessage\ContactMessageRepositoryInterface',
            'App\Repositories\Backend\ContactMessage\ContactMessageRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Location\LocationRepositoryInterface',
            'App\Repositories\Backend\Location\LocationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\LocationTranslation\LocationTranslationRepositoryInterface',
            'App\Repositories\Backend\LocationTranslation\LocationTranslationRepository'
        );

       
        $this->app->bind(
            'App\Repositories\Backend\Category\CategoryRepositoryInterface',
            'App\Repositories\Backend\Category\CategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\CategoryTranslation\CategoryTranslationRepositoryInterface',
            'App\Repositories\Backend\CategoryTranslation\CategoryTranslationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Package\PackageRepositoryInterface',
            'App\Repositories\Backend\Package\PackageRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\PackageTranslation\PackageTranslationRepositoryInterface',
            'App\Repositories\Backend\PackageTranslation\PackageTranslationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\Banner\BannerRepositoryInterface',
            'App\Repositories\Backend\Banner\BannerRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\WelcomeScreen\WelcomeScreenRepositoryInterface',
            'App\Repositories\Backend\WelcomeScreen\WelcomeScreenRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\WelcomeScreenTranslation\WelcomeScreenTranslationRepositoryInterface',
            'App\Repositories\Backend\WelcomeScreenTranslation\WelcomeScreenTranslationRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\User\UserRepositoryInterface',
            'App\Repositories\Backend\User\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Backend\CompanyDetails\CompanyDetailsRepositoryInterface',
            'App\Repositories\Backend\CompanyDetails\CompanyDetailsRepository'
        );

        /** api */
        $this->app->bind(
            'App\Repositories\Api\User\UserRepositoryInterface',
            'App\Repositories\Api\User\UserRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Device\DeviceRepositoryInterface',
            'App\Repositories\Api\Device\DeviceRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\SocialUser\SocialUserRepositoryInterface',
            'App\Repositories\Api\SocialUser\SocialUserRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\CompanyDetails\CompanyDetailsRepositoryInterface',
            'App\Repositories\Api\CompanyDetails\CompanyDetailsRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Category\CategoryRepositoryInterface',
            'App\Repositories\Api\Category\CategoryRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Location\LocationRepositoryInterface',
            'App\Repositories\Api\Location\LocationRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Post\PostRepositoryInterface',
            'App\Repositories\Api\Post\PostRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Like\LikeRepositoryInterface',
            'App\Repositories\Api\Like\LikeRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Comment\CommentRepositoryInterface',
            'App\Repositories\Api\Comment\CommentRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Abuse\AbuseRepositoryInterface',
            'App\Repositories\Api\Abuse\AbuseRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Follower\FollowerRepositoryInterface',
            'App\Repositories\Api\Follower\FollowerRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Rate\RateRepositoryInterface',
            'App\Repositories\Api\Rate\RateRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\ContactMessage\ContactMessageRepositoryInterface',
            'App\Repositories\Api\ContactMessage\ContactMessageRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Package\PackageRepositoryInterface',
            'App\Repositories\Api\Package\PackageRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\PackageSubscription\PackageSubscriptionRepositoryInterface',
            'App\Repositories\Api\PackageSubscription\PackageSubscriptionRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Company\CompanyRepositoryInterface',
            'App\Repositories\Api\Company\CompanyRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Banner\BannerRepositoryInterface',
            'App\Repositories\Api\Banner\BannerRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\PriceRequest\PriceRequestRepositoryInterface',
            'App\Repositories\Api\PriceRequest\PriceRequestRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\WelcomeScreen\WelcomeScreenRepositoryInterface',
            'App\Repositories\Api\WelcomeScreen\WelcomeScreenRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Notification\NotificationRepositoryInterface',
            'App\Repositories\Api\Notification\NotificationRepository'
        );
       
  
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
