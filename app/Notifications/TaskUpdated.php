<?php

namespace App\Notifications;

use App\Models\TaskUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdated extends Notification
{
    use Queueable;

    protected $taskUpdate;

    /**
     * Create a new notification instance.
     */
    public function __construct(TaskUpdate $taskUpdate)
    {
        $this->taskUpdate = $taskUpdate;
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
                    ->line('Task telah diupdate oleh karyawan.')
                    ->line('Task: ' . $this->taskUpdate->task->title)
                    ->line('Update: ' . $this->taskUpdate->description)
                    ->action('Lihat Task', url('/pm/tasks/' . $this->taskUpdate->task->id))
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
            'title' => 'Task Diupdate',
            'message' => $this->taskUpdate->user->name . ' telah mengupdate task: ' . $this->taskUpdate->task->title,
            'task_id' => $this->taskUpdate->task->id,
            'task_update_id' => $this->taskUpdate->id,
            'type' => 'task_updated'
        ];
    }
}
