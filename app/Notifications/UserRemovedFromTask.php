<?php

namespace App\Notifications;

use App\Team;
use App\Task;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRemovedFromTask extends Notification
{
    use Queueable;
    protected $team, $task, $user;

    /**
     * Create a new notification instance.
     * @param Team $team
     * @param Task $task
     * @param User $user
     */
    public function __construct(Team $team, Task $task, User $user)
    {
        $this->team = $team;
        $this->task = $task;
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
            ->subject($this->user->getFullName().' has removed you from a task in team '.$this->team->name)
            ->line($this->user->getFullName().' ('.$this->user->email.') has removed you from task '.$this->task->name.' in team '.$this->team->name)
            ->action('Log in', url('/login'));
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
            //
        ];
    }
}
