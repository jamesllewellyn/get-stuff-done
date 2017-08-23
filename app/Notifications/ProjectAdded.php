<?php

namespace App\Notifications;

use App\Team;
use App\Project;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectAdded extends Notification
{
    use Queueable;
    protected $team, $project, $user;
    /**
     * Create a new notification instance.
     * @param Team $team
     * @param Project $project
     * @param User $user
     * @return void
     */
    public function __construct(Team $team, Project $project, User $user)
    {
        $this->team = $team;
        $this->project = $project;
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
            ->subject($this->user->getFullName().' has added a new project to '.$this->team->name)
            ->line($this->user->getFullName().' ('.$this->user->email.') has added project '.$this->project->name.' to team '.$this->team->name)
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
            'user' => $this->user,
            'action' => 'Added project '.$this->project->name.' to team '.$this->team->name,
            'team_id' => $this->team->id,
            'project_id' => $this->project->id
        ];
    }
}
