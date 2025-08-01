<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedToProjectNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('teammember.projects.show', $this->project);

        return (new MailMessage)
                    ->subject('Anda Ditambahkan ke Proyek Baru')
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Anda telah ditambahkan sebagai anggota tim untuk proyek baru:')
                    ->line('**' . $this->project->name . '**')
                    ->action('Lihat Proyek', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'message' => 'Anda telah ditambahkan ke proyek "' . $this->project->name . '".',
        ];
    }
}