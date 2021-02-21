<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Repositories\Contracts\IUser;
use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\VerifiesEmails; not using this anymore

class VerificationController extends Controller
{
    // use VerifiesEmails; we do not need to use this trait; we do not use it since it targets SPA and we use API
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUser $users)
    {
        // $this->middleware('signed')->only('verify');  // expiration
        $this->middleware('throttle:6,1')->only('verify', 'resend');  // limit the amount of resend email requests
        $this->users = $users;
    }

    /**
     * overriding trait (VerifiesEmails) methods; they must be named exactly the same
     */
    public function verify(Request $request, User $user)
    {
      // create timestamp into 'email_verified_at' column in users table
      // check if $request url is a valid signed url
      if (! URL::hasValidSignature($request)) {
        return response()->json(["errors" => [
          "message" => "invalid verification link or signature"
        ]], 422);
      }
      // check if user has already verified account
      if ($user->hasVerifiedEmail()){    // calling method from trait class
        return response()->json(["errors" => [
          "message" => "Email address already verified"
        ]], 422);
      }
      $user->markEmailAsVerified(); // in trait: forceFill() timestamp into 'email_verified_at' col
      event(new Verified($user)); // notify laravel of event
      return response()->json(['message' => 'Email successfully verified'], 200);
    }

    /**
     * resend email if user did not verify within 1 hour
     */
    public function resend(Request $request) {
      $this->validate($request, [
        'email' => ['email', 'required']
      ]);
      $user = $this->users->findWhereFirst('email', $request->email); // comes from IBase interface; query on User model where email prop matches requst email and return the first record to match
      // if there is no user with that email in db return json err and 422
      if (! $user){
        return response()->json(['errors' => [
          'email' => 'No user could be found with this email address'
        ]], 422);
      }
      $user->sendEmailVerificationNotification();
      return response()->json(['status' => 'verification link resent']);
    }
}
