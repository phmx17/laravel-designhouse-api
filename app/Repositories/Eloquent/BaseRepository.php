<?php

namespace App\Repositories\Eloquent;
use App\Repositories\Contracts\IBase;

// to be extended in other repos in order to prevent duplication 
abstract class BaseRepository implements IBase; // be able to pass classes dynamically
{
  public function all()
  {
    return User::all();
  }
}