<?php

namespace App\Providers;
// this service tells laravel to hook up the contracts / interfaces with the Eloquent (or any other ORM) Repository files
use Illuminate\Support\ServiceProvider;

// pull in the contracts
use App\Repositories\Contracts\{    // this {} thing only works after php 7
  IDesign,
  IUser,
  IComment
};
// pull in the Repos; hook them up below in Bootstrap services
use App\Repositories\Eloquent\{    
  DesignRepository,
  UserRepository,
  CommentRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      // here is where the magic binding happens
      $this->app->bind(IDesign::class, DesignRepository::class);
      $this->app->bind(IUser::class, UserRepository::class);
      $this->app->bind(IComment::class, CommentRepository::class);
      // no go register this service provider in config\app.php under Service Providers;
      //  App\Providers\RepositoryServiceProvider::class,
    }
}
