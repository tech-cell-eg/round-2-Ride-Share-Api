<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class RideRequest extends Notification
{
    use Queueable;

    protected $ride;
    protected $title;
    protected $body;

    /**
     * Create a new notification instance.
     */
    public function __construct($ride)
    {
        $this->ride = $ride;
        $this->title = 'Ride Request';
        $this->body = "You have a new request from " . $this->ride['customer_name'] . ".\n" .
            "The pickup location is ". $this->ride['pickup_location'] . ".\n" .
            "The drop Location is " . $this->ride['drop_location'] . ".\n" .
            "The Distance is " . $this->ride['distance'] . ".\n" .
            "The fare Price is " . $this->ride['fare_price'] . ".\n";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FcmChannel::class, 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
        ];
    }
}
