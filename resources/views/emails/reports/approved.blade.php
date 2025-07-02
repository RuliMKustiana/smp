<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Disetujui</title>
    <style>
        /* Gaya dasar untuk email */
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #28a745; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px 0; }
        .footer { text-align: center; font-size: 0.9em; color: #888; margin-top: 20px; }
        .button { background-color: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .report-content { background-color: #f8f9fa; border: 1px solid #eee; padding: 15px; border-radius: 5px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Laporan Proyek Disetujui</h2>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $report->submittedBy->name }}</strong>,</p>
            <p>
                Kabar baik! Laporan final Anda untuk proyek <strong>"{{ $report->project->name }}"</strong> dengan judul 
                "{{ $report->title }}" telah divalidasi dan disetujui oleh admin.
            </p>
            <p>
                Dengan ini, proyek tersebut juga secara resmi ditandai sebagai **Selesai**.
            </p>
            <hr>

            <h4>Detail Validasi:</h4>
            <p><strong>Divalidasi Oleh:</strong> {{ $report->validator->name ?? 'N/A' }}</p>
            <p><strong>Tanggal Persetujuan:</strong> {{ \Carbon\Carbon::parse($report->validated_at)->isoFormat('D MMMM Y, HH:mm') }}</p>
            
            @if($report->validation_notes)
                <p><strong>Catatan dari Admin:</strong></p>
                <div class="report-content" style="background-color: #e9f7ef; border-left: 4px solid #28a745;">
                    {{ $report->validation_notes }}
                </div>
            @endif

            <p style="margin-top: 25px;">
                Anda dapat melihat detail laporan ini dengan mengklik tombol di bawah ini.
            </p>

            <a href="{{ route('pm.reports.show', $report->id) }}" class="button">Lihat Detail Laporan</a>

            <p>Terima kasih atas kerja keras Anda dalam menyelesaikan proyek ini.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
