<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Gate;

//Models
use App\Models\User;
use App\Models\Event;

// Policies
use App\Policies\EventPolicy;
use PhpParser\Node\Expr\FuncCall;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Event::class => EventPolicy::class,
    ];

    public function __construct()
    {
        // $this->verifyEmailTemplate =  function ($notifiable) {
        //     $id = $notifiable->getKey();
        //     $hash = sha1($notifiable->getEmailForVerification());
        //     $url = url(config('app.client_url') . '/email/verify/' . $id . '/' . $hash);
        //     return (new MailMessage)
        //         ->subject(Lang::get('Verify Email Address'))
        //         ->line(Lang::get('Please click the button below to verify your email address.'))
        //         ->action(Lang::get('Verify Email Address'), $url)
        //         ->line(Lang::get('If you did not create an account, no further action is required.'));
        // };
        // $this->resetPasswordUrl = function ($user, string $token) {
        //     return config('app.url') . '/reset-password/' . $token;
        // };
    }
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing([$this, 'verifyEmailTemplate']);
        ResetPassword::createUrlUsing([$this, 'resetPasswordUrl']);
    }

    public function verifyEmailTemplate ($notifiable) 
    {
        $id = $notifiable->getKey();
        $hash = sha1($notifiable->getEmailForVerification());
        $url = url(config('app.client_url') . '/email/verify/' . $id . '/' . $hash);
        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    public function resetPasswordUrl ($user, string $token) 
    {
        return config('app.url') . '/reset-password/' . $token;
    }
}
