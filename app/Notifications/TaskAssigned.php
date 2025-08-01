<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Anda telah ditugaskan untuk task baru.')
                    ->line('Task: ' . $this->task->title)
                    ->line('Proyek: ' . $this->task->project->name)
                    ->action('Lihat Task', url('/teammember/tasks/' . $this->task->id))
                    ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Task Baru Ditugaskan',
            'message' => 'Anda telah ditugaskan untuk task: ' . $this->task->title,
            'task_id' => $this->task->id,
            'project_id' => $this->task->project_id,
            'type' => 'task_assigned'
        ];
    }
}
