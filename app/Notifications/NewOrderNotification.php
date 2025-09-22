<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        //return ['mail', 'database'];
        return ['mail'];
    }

    /**
     * Store notification in the database.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer' => $this->order->contact_name,
            'total_price' => $this->order->total_price,
            'delivery_option' => $this->order->delivery_option,
        ];
    }

    /**
     * Send email notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order Received')
            ->greeting('Hello ' . ($notifiable->name ?? 'Vendor'))
            ->line('You have received a new order.')
            ->line('Order ID: #' . $this->order->id)
            ->line('Customer: ' . $this->order->contact_name)
            ->line('Total: ' . $this->order->total_price . ' CHF')
            ->action('View Order', url('/vendor/orders/' . $this->order->id))
            ->line('Thank you for using our service!');
    }
}
