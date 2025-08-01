<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProjectNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Kirim ke database dan email
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('pm.projects.show', $this->project);

        return (new MailMessage)
                    ->subject('Proyek Baru Telah Dibuat: ' . $this->project->name)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Sebuah proyek baru telah ditambahkan ke dalam sistem.')
                    ->line('Nama Proyek: **' . $this->project->name . '**')
                    ->action('Lihat Detail Proyek', $url)
                    ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'message' => 'Proyek baru "' . $this->project->name . '" telah dibuat.',
        ];
    }
}