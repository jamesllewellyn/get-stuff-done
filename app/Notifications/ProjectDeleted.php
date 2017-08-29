<?php

namespace App\Notifications;

use App\Team;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProjectDeleted extends Notification implements ShouldQueue
{
    use Queueable;
    protected $team, $projectName, $user;

    /**
     * Create a new notification instance.
     * @param Team $team
     * @param $projectName
     * @param User $user
     */
    public function __construct(Team $team, $projectName, User $user)
    {
        $this->team = $team;
        $this->projectName = $projectName;
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
        return ['mail', 'database', 'broadcast'];
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
            ->subject($this->user->getFullName().' has deleted a new project from '.$this->team->name)
            ->line($this->user->getFullName().' ('.$this->user->email.') has deleted project '.$this->projectName.' from team '.$this->team->name)
            ->action('Log in', url('/login'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->user,
            'action' => 'Deleted project '.$this->projectName.' from team '.$this->team->name,
            'team_id' => $this->team->id
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'team' => $this->team,
            'projectName' => $this->projectName,
            'user' => $this->user
        ]);
    }
}
