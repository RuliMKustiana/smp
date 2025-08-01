<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportValidated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->report->status === 'Disetujui' ? 'disetujui' : 'ditolak';
        
        return (new MailMessage)
                    ->line('Laporan proyek Anda telah ' . $status . '.')
                    ->line('Proyek: ' . $this->report->project->name)
                    ->line('Status: ' . $this->report->status)
                    ->when($this->report->validation_notes, function($mail) {
                        return $mail->line('Catatan: ' . $this->report->validation_notes);
                    })
                    ->action('Lihat Laporan', url('/pm/reports/' . $this->report->id))
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
            'title' => 'Laporan ' . $this->report->status,
            'message' => 'Laporan proyek ' . $this->report->project->name . ' telah ' . strtolower($this->report->status),
            'report_id' => $this->report->id,
            'project_id' => $this->report->project_id,
            'type' => 'report_validated'
        ];
    }
}
