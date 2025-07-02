<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanDisetujuiMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Properti publik akan otomatis tersedia di dalam view email.
     */
    public $report;

    /**
     * Membuat instance pesan baru.
     *
     * @param \App\Models\Report $report Data laporan yang disetujui
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Mendapatkan amplop pesan (subjek, pengirim, dll.).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Proyek Anda Telah Disetujui',
        );
    }

    /**
     * Mendapatkan definisi konten pesan.
     */
    public function content(): Content
    {
        // Menentukan file Blade mana yang akan menjadi isi email
        return new Content(
            view: 'emails.reports.approved',
        );
    }

    /**
     * Mendapatkan lampiran untuk pesan.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
