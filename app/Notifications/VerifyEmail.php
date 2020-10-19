<?php

namespace App\Notifications;

use Carbon\Carbon;
// use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail as Notification;

// use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification
{
    
    public function verificationUrl($notifiable)
    {
        $appUrl = config('app.client_url', config('app.url'));

        $url = URL::temporarySignedRoute(
            'verification.verify', 
            Carbon::now()->addMinutes(60), 
            ['user' => $notifiable->id]
        );

        // http://designhouse.test/asasa
        return str_replace(url('/api'), $appUrl, $url);
    }

     
}
