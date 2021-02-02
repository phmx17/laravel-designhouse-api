<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail as Notification;

class VerifyEmail extends Notification
{
  protected function verificationUrl($notifiable)
  {
    // create new url to send with email verification
    // part one of url: client
    $appUrl = config('app.client_url', config('app.url'));  // app.client_url was created in \config\app.php
    
    // part two: verification path plus expiration and notification
    $url = URL::temporarySignedRoute(
      'verification.verify',  // name of the route is given in api.php
      Carbon::now()->addMinutes(60), // valid 60 mins
      ['user' => $notifiable->id] // User is notifiable, see model
    );
    // starts with http://localhost:8000/api/timestamp and user id
    // concat first by removing '/api'from $url and replace with $appUrl
    return str_replace(url('/api'), $appUrl, $url);

    /* This is the link in the verification email:    
    http://localhost:3000/verification/verify
    ?expires=1612210919
    &user=1
    &signature=c29003af02bd6f9ca911cf0059c4ae13a6ca29706557a07cf1674eaa1dc1f5e9
    */
  }

}
