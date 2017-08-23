<?php

namespace App\Notifications;

use App\Team;
use App\User;
use App\Task;
use App\Section;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AssignedTask extends Notification
{
    use Queueable;
    protected $team, $task, $user;
    /**
     * Create a new notification instance.
     * @param Team $team
     * @param Task $task
     * @param User $user
     * @return void
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
            ->subject($this->user->getFullName().' has assigned you to a new task in team '.$this->team->name)
            ->line($this->user->getFullName().' ('.$this->user->email.') has assigned you to task '.$this->task->name.' in team '.$this->team->name)
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
        $section =  Section::find($this->task->section_id);
        return [
            'user' => $this->user,
            'action' => 'Assigned you to task '.$this->task->name.' in team '.$this->team->name,
            'team_id' => $this->team->id,
            'project_id' => $section->project_id,
            'section_id' => $this->task->section_id,
            'task_id' => $this->task->id
        ];
    }
}
