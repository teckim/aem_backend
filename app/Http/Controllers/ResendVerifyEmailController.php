<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResendVerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'email resent successfully!']);   
    }
}
