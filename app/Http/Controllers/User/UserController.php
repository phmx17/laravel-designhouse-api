<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Contracts\IUser; // User Interface = contract; for use with Repository Pattern

class UserController extends Controller
{

  protected $users;
  public function __construct(IUser $users) // injecting contract class into controller; pull in as many here as you like
  {
    $this->users = $users;
  }

  public function index()
  { 
    $users = $this->users->withCriteria([
      new EagerLoad(['designs'])  // which attributes to eager load
    ])->all();
    
    return UserResource::collection($users);  // returning entire resource and not just an instance
  }
}
