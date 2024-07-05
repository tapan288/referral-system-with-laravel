<?php

namespace App\Notifications;

use App\Exports\ReferralPayoutExport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Maatwebsite\Excel\Facades\Excel;

class ReferralPayout extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Builder $payouts)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Referral Payout Notification')
            ->attach(Attachment::fromData(function () {
                return Excel::raw(new ReferralPayoutExport($this->payouts), 'Csv');
            }, now()->format('Y-m-d') . '-referral-payout.csv')
                ->withMime('text/csv'))
            // ->action('Notification Action', url('/'))
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
            //
        ];
    }
}
