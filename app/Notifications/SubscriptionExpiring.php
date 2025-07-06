<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpiring extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail']; 
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Peringatan: Langganan Anda Akan Habis')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Langganan Anda akan habis dalam 3 hari.')
            ->action('Perpanjang Langganan', url('/dashboard'))
            ->line('Terima kasih sudah menggunakan layanan kami!');
    }
}
