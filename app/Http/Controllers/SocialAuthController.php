<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Tymon\JWTAuth\JWTAuth;

use App\Models\User;
use App\Models\Social;

class SocialAuthController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        $this->middleware('social');
    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callback($service)
    {
        try {
            $socialUser = Socialite::driver($service)->stateless()->user();
        } catch (\Exception $e) {
            return Redirect::to(env('CLIENT_BASE_URL') . '/social-callback?err=Unable to login using ' . $service . ', Please try again');
        }

        $email = $socialUser->getEmail();

        $user = $this->getExistingUser($socialUser, $email, $service);

        if (!$user) {
            $user = User::create([
                'first_name' => $socialUser->user['given_name'],
                'last_name' => $socialUser->user['family_name'],
                'email' => $email,
                'email_verified_at' => $this->isSocialUserEmailVerified($socialUser, $service) ? time() : null,
                'image' => $socialUser->user['image'],
                'password' => ''
            ]);
        } else if ($service == 'google' && !$user->email_verified_at) {
            $user->email_verified_at = time();
            $user->save();
        }

        if ($this->hasSocialLinked($user, $service)) {
            Social::create([
                'user_id' => $user->id,
                'social_id' => $socialUser->getId(),
                'service' => $service
            ]);
        }

        return Redirect::to(config('app.url').'/social-callback?token=' . $this->auth->fromUser($user) . '&origin=');
    }

    public function isSocialUserEmailVerified($user, $service)
    {
        return ($service == 'google' && $user->user['verified_email']);
    }

    public function hasSocialLinked(User $user, $service)
    {
        return !$user->hasSocialLinked($service);
    }

    public function getExistingUser($socialUser, $email, $service)
    {
        $user_id = $socialUser->getId();

        if ($service == 'google') {
            return User::where('email', $email)->orWhereHas('social', function ($q) use ($user_id, $service) {
                $q->where('user_id', $user_id)->where('service', $service);
            })->first();
        } else {
            $user = Social::where('social_id', $user_id)->first();
            return $user ? $user->$user : null;
        }
    }
}
