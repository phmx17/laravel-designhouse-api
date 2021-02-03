<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
  public function getMe()
  {
    if(auth()->check()){
      $user = auth()->user();
      return new UserResource($user); // using a custom resource which wraps return json obj in 'data' object
    }
    // return response()->json(['user' => auth()->user()], 200);      
    return response()->json(null, 401);
  }
}
// $user->created_at_human = $user->created_at->diffForHumans(); // created_at is a laravel prop that calls method to calc diff from current time
