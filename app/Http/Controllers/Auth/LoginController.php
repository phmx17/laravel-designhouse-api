<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\AuthManager;

class LoginController extends Controller
{
  public function __construct()
  {
      Auth::setDefaultDriver('api');
  }
    /*
    |--------------------------------------------------------------------------
    | Login Controller - disigned originally for web auth; must reconfigure for API design
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers; // trait contains login(), attemptLogin() returns bool; 

    public function attemptLogin(Request $request)
    {
      // attempt to issue a token based on login credentials
      $token = $this->guard()->attempt($this->credentials($request)); // attempt to extract credentials
      if (! $token) {
        return false; // must return bool based on return type defined in trait
      }
      // get authenticated user
      $user = $this->guard()->user();
      // User model invokes a MustVerifyEmail contract, so it is an instance of that contract if not verified yet
      if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {  // method from trait
        return false; // if email must be verified and not yet verified
      }
      // set the user's token
      $this->guard()->setToken($token);
      return true;
    }

    public function sendLoginResponse(Request $request) 
    {
      $this->clearLoginAttempts($request);  // start account fresh
      // get the JWT from the authentication guard
      $token = (string)$this->guard()->getToken();
      // extract expiry date from the token; going to store token and expiration into cookie
      $expiration = $this->guard()->getPayload()->get('exp');
      return response()->json([
        'token' => $token,
        'token_type' => 'bearer',
        'expires_in' => $expiration
      ]);
    }

    /**
     * failed response
     */
    protected function sendFailedLoginResponse()
    {
      $user = $this->guard()->user();
      // user has not yet verified email
      if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {  // must import the MVE contract
        return response()->json(['errors' => [
          'verification' => 'You need to verify your email account'
        ]]);
      }

      throw ValidationException::withMessages([ // messages plural !
        $this->username() => 'Invalid Credentials'
      ]);      
    }

    /**
     * logout
     */
    public function logout()
    {
      $this->guard()->logout();
      return response()->json(['message' => 'log out success']);
    }

    /**
     * Where to redirect users after login. - we don't need this anymore
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance. - don't need with API
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }
}
