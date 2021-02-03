<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Rules\CheckSamePassword;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class SettingsController extends Controller
{
  public function updateProfile(Request $request)
  {
    $user = auth()->user();
    $this->validate($request, [
      'tagline' => ['required'],
      'name' => ['required'] ,
      'about' => ['required', 'string', 'min:20'],
      'formatted_address' => ['required'] ,
      'location.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
      'location.longitude' => ['required', 'numeric', 'min:-180', 'max:180']
    ]);
    // create a geo location based on the long and lat
    $location = new Point($request->location['latitude'], $request->location['longitude']);

    $user->update([
      'name' => $request->name,
      'formatted_address' => $request->formatted_address,
      'location' => $location,
      'available_to_hire' => $request->available_to_hire,
      'about' => $request->about,
      'tagline' => $request->tagline,
    ]);
    return new UserResource($user);
  }

  public function updatePassword(Request $request)
  {
    // current password
    // new password
    // password confirmation
    $this->validate($request, [
      'current_password' => ['required', new MatchOldPassword], // MOP = custom rule 
      'password' => ['required', 'confirmed', 'min:6', new CheckSamePassword]
    ]);

    $request->user()->update([
      'password' => bcrypt($request->password)
    ]);

    return response()->json(['message' => 'Password was successfully updated'], 200);
  }
}
