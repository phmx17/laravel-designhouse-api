<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;  // trait



class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails; // trait

    protected function sendResetLinkResponse(Request $request, $response)
    {
      return response()->json(['status' => trans($response)], 200); // trans() = localization; translate
    }

    protected function sendResetlinkFailedResponse(Request $request, $response)
    {
      return response()->json(['email' => trans($response), 422]);
    }

}
