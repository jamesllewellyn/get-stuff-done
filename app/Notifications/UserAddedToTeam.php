<?php

namespace App\Notifications;

use App\Team;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserAddedToTeam extends Notification
{
    use Queueable;
    protected $team, $user;

    /**
     * Create a new notification instance.
     * @param Team $team
     * @param User $user
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
        return ['mail', 'database'];
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
                    ->subject($this->user->getFullName().' add you to team '.$this->team->name)
                    ->line($this->user->getFullName().' ('.$this->user->email.') has added you to their team')
                    ->line('You\'re now a member of team '.$this->team->name.' on Get Stuff Done')
                    ->action('Login', url('/'));
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
            'user' => $this->user,
            'action' => 'Added you to team '.$this->team->name,
            'teamId' => $this->team->id
        ];
    }
}
