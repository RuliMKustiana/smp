<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChanged extends Notification
{
    use Queueable;

    protected $task;
    protected $message;
    protected $sender;

    public function __construct(Task $task, string $message, User $sender)
    {
        $this->task = $task;
        $this->message = $message;
        $this->sender = $sender;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $notifiable->hasRole('Project Manager') 
                   ? route('pm.tasks.show', $this->task->id) 
                   : route('teammember.tasks.show', $this->task->id);

        return (new MailMessage)
                    ->subject('Update Status Tugas: ' . $this->task->title)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line($this->sender->name . ' telah memperbarui sebuah tugas:')
                    ->line('"' . $this->message . '"')
                    ->action('Lihat Detail Tugas', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'message' => $this->message,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
        ];
    }
}