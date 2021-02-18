<?php

namespace App\Repositories\Eloquent;
use App\Repositories\Contracts\IUser;
use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;

// implements the interface and laravel expects a concrete definition of all the methods in the interface
class UserRepository extends BaseRepository implements IUser
{
  public function model()
  {
    return User::class; // returns the entire namespace: App\Models\User; this is to dynamically pass the model to BaseRepository
  }
}
