<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifyMail;
use App\Notifications\NewEvent;
use App\Mail\NewEventEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Event;
use Illuminate\Mail\Markdown;

class SendEmailController extends Controller
{
    public function sendEmail()
    {
        $user = User::find(5);
        $event = Event::find('7293CT5TDR');

        Notification::route('mail', "hakim.bhd@gmail.com") //Sending mail to subscriber
                ->notify(new NewEvent($event, $user));

        return response()->json(['message' => 'Great! Successfully send in your mail', 'event' => $event, 'user' => $user]);
    }
}
