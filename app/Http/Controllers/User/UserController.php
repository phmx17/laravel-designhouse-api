<?php

namespace App\Http\Controllers\User;

use App\Repositories\Contracts\IUser; // User Interface = contract; for use with Repository Pattern
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

  protected $users;
  public function __construct(IUser $users) // injecting contract class into controller; pull in as many here as you like
  {
    $this->users = $users;
  }

  public function index()
  { 
    // implementing Repository pattern
    // $users = User::all(); // old version
    $users = $this->users->all();
    return UserResource::collection($users);  // returning entire resource and not just an instance
  }
}
