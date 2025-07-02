<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // <-- 1. Tambahkan ini
use App\Mail\LaporanDisetujuiMail;    // <-- 2. Tambahkan ini

class ReportValidationController extends Controller
{
    /**
     * Menampilkan daftar semua laporan yang menunggu persetujuan.
     */
    public function index(Request $request)
    {
        // Query dasar untuk laporan
        $query = Report::with(['project', 'submittedBy'])->latest();

        // Terapkan filter status jika ada
        if ($request->has('status') && $request->status != '') {
            $statusMapping = [
                'pending' => 'Menunggu Persetujuan',
                'approved' => 'Disetujui',
                'validated' => 'Disetujui', // Menangani kemungkinan variasi
                'rejected' => 'Ditolak',
            ];
            
            // Gunakan status yang sudah dimapping atau status asli jika tidak ada di map
            $dbStatus = $statusMapping[$request->status] ?? $request->status;
            $query->where('status', $dbStatus);
        } else {
            // Default: Hanya tampilkan yang menunggu persetujuan di halaman utama
            $query->where('status', 'Menunggu Persetujuan');
        }

        $reports = $query->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Menampilkan halaman detail laporan untuk divalidasi (form validasi).
     */
    public function show(Report $report)
    {
        // PERBAIKAN: Memuat relasi project dan tasks beserta attachments-nya
        $report->load(['project.tasks.attachments', 'submittedBy', 'validator']);
        
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Memproses aksi 'VALIDASI' (Setujui) laporan.
     */
    public function validateReport(Request $request, Report $report)
    {
        // Pastikan hanya laporan yang 'pending' yang bisa diproses
        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('admin.reports.show', $report)->with('error', 'Tindakan ini tidak dapat dilakukan pada laporan yang sudah diproses.');
        }

        $report->update([
            'status' => 'Disetujui', // Status diubah menjadi 'Disetujui'
            'validation_notes' => $request->validation_notes, // Catatan dari form
            'validator_id' => Auth::id(), // Menyimpan ID admin yang memvalidasi
            'validated_at' => now(), // Menyimpan waktu validasi
        ]);

        // --- PENYESUAIAN DI SINI: Logika Pengiriman Email ---
        // Cek apakah tipe laporan adalah 'final'
        if ($report->type === 'final') {
            
            // Update status proyek terkait menjadi "Selesai"
            if ($report->project) {
                $report->project->update(['status' => 'Selesai']);
            }
            
            // Kirim email notifikasi ke Project Manager
            try {
                if ($report->submittedBy && $report->submittedBy->email) {
                    $report->load('project', 'submittedBy', 'validator');
                    Mail::to($report->submittedBy->email)->send(new LaporanDisetujuiMail($report));
                }
            } catch (\Exception $e) {
                report($e);
            }
        }
        // --- AKHIR PENYESUAIAN ---

        return redirect()->route('admin.reports.show', $report) // Kembali ke halaman detail
            ->with('success', 'Laporan berhasil disetujui.');
    }

    /**
     * Memproses aksi 'TOLAK' laporan.
     */
    public function rejectReport(Request $request, Report $report)
    {
        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('admin.reports.show', $report)->with('error', 'Tindakan ini tidak dapat dilakukan pada laporan yang sudah diproses.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000'
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal harus 10 karakter.',
        ]);

        $report->update([
            'status' => 'Ditolak',
            'validation_notes' => $request->rejection_reason,
            'validator_id' => Auth::id(),
            'validated_at' => now(),
        ]);

        return redirect()->route('admin.reports.show', $report) // Kembali ke halaman detail
            ->with('success', 'Laporan berhasil ditolak.');
    }
}
