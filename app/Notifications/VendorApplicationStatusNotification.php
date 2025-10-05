<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VendorApplication;

class VendorApplicationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public VendorApplication $vendorApplication) {}

    public function via($notifiable)
    {
        // send email + database (if you want it visible in panel too)
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = $this->vendorApplication->is_approved ? 'approved' : 'rejected';

        return (new MailMessage)
            ->subject("Your Vendor Application has been {$status}")
            ->greeting("Hello {$this->vendorApplication->chef_name},")
            ->line("Your kitchen **{$this->vendorApplication->kitchen_name}** has been {$status} by our admin team.")
            ->action('View Your Dashboard', url('/dashboard'))
            ->line('Thank you for being part of our platform!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Vendor Application Update',
            'message' => "Your application '{$this->vendorApplication->kitchen_name}' has been " . ($this->vendorApplication->is_approved ? 'approved ✅' : 'rejected ❌'),
            'vendor_application_id' => $this->vendorApplication->id,
        ];
    }
}
