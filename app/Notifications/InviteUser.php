<?php

namespace App\Notifications;

use App\Team;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InviteUser extends Notification
{
    use Queueable;
    protected $team, $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Team $team,User $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->user->getFullName().' has invited you to join a Get Stuff Done team')
            ->line('Join '.$this->team->name.' on Get Stuff Done')
            ->line($this->user->getFullName().' ('.$this->user->email.') has invited you to join their team')
            ->action('Join '.$this->team->name, url('/invite?token='.urlencode(base64_encode('email='.$notifiable->email.'&token='.$notifiable->token))));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }
}
