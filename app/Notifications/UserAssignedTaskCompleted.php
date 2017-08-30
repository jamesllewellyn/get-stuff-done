<?php

namespace App\Notifications;

use App\Team;
use App\User;
use App\Task;
use App\Project;
use App\Section;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserAssignedTaskCompleted extends Notification implements ShouldQueue
{
    use Queueable;
    protected $team,$project, $task, $user;

    /**
     * Create a new notification instance.
     * @param Team $team
     * @param Project $project
     * @param Task $task
     * @param User $user
     */
    public function __construct(Team $team, Project $project, Task $task, User $user)
    {
        $this->team = $team;
        $this->project = $project;
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
            ->subject($this->user->getFullName().' has completed task '.$this->task->name)
            ->line($this->user->getFullName().' ('.$this->user->email.') has completed task '.$this->task->name.' in team '.$this->team->name)
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
            'action' => 'Completed task '.$this->task->name.' in team '.$this->team->name,
            'team_id' => $this->team->id,
            'project_id' => $this->project->project_id,
            'section_id' => $this->task->section_id,
            'task_id' => $this->task->id
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
            'project' => $this->project,
            'task' => $this->task,
            'user' => $this->user
        ]);
    }
}
