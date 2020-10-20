<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification; 
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
    public function toMail($notifiable)
    {
        
        $url = url(config('app.client_url') . '/password/reset/' . $this->token) . '?email=' . urlencode(($notifiable->email));
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.'))
            ->action(Lang::get('Đặt lại mật khẩu'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
