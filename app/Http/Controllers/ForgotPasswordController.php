<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $credentials = request()->all();
        $email = $credentials['email'];
        $exists = User::where('email', $email)->count();

        if (!$exists) {
            return response()->json(['email' => ['No account associated with that email']], 400);
        }

        try {
            Password::sendResetLink($request->only('email'));
            return response()->json(['message' => 'email sent']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th]);
        }
    }
}
