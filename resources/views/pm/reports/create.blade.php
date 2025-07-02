@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER HALAMAN --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h5 class="text-white text-capitalize mb-0">Buat Laporan Proyek</h5>
                    <a href="{{ route('pm.reports.index') }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pm.reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    {{-- KOLOM KIRI: KONTEN UTAMA LAPORAN --}}
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required
                                   placeholder="Contoh: Laporan Progress Mingguan - Proyek ABC">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Konten Laporan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="12" required
                                      placeholder="Tuliskan konten laporan secara detail atau gunakan template di bawah...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Quick Templates --}}
                        <div class="mt-4">
                            <h6 class="text-sm">Gunakan Template:</h6>
                            <div class="btn-group-sm mb-3" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="useTemplate('progress')">Progress</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="useTemplate('weekly')">Mingguan</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="useTemplate('issue')">Masalah</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="useTemplate('milestone')">Milestone</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- KOLOM KANAN: PENGATURAN & METADATA --}}
                    <div class="col-lg-4">
                        <div class="card bg-gray-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="project_id" class="form-label">Proyek <span class="text-danger">*</span></label>
                                    <select class="form-select @error('project_id') is-invalid @enderror" 
                                            id="project_id" name="project_id" required>
                                        <option value="">Pilih Proyek</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Laporan</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type">
                                        <option value="">Pilih Tipe</option>
                                        <option value="progress" {{ old('type') === 'progress' ? 'selected' : '' }}>Progress</option>
                                        <option value="weekly" {{ old('type') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                        <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Final</option>
                                        <option value="issue" {{ old('type') === 'issue' ? 'selected' : '' }}>Masalah</option>
                                        <option value="milestone" {{ old('type') === 'milestone' ? 'selected' : '' }}>Milestone</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Lampiran</label>
                                    <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-xs">Anda bisa memilih lebih dari satu file.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="horizontal dark mt-4">

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('pm.reports.index') }}" class="btn-gradient btn-gradient-gray">Batal</a>
                    <button type="submit" name="status" value="draft" class="btn-gradient btn-gradient-yellow">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                    <button type="submit" name="status" value="Menunggu Persetujuan" class="btn-gradient btn-gradient-blue">
                        <i class="fas fa-paper-plane me-1"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Template functions (Struktur tidak diubah)
function useTemplate(type) {
    const contentField = document.getElementById('content');
    const typeField = document.getElementById('type');
    
    let template = '';
    
    switch(type) {
        case 'progress':
            template = `## Ringkasan Progress\n\n### Pencapaian Minggu Ini\n- [Tuliskan pencapaian utama]\n\n### Tugas yang Diselesaikan\n- [List tugas yang sudah selesai]\n\n### Tugas dalam Progress\n- [List tugas yang sedang dikerjakan]\n\n### Kendala/Masalah\n- [Tuliskan kendala yang dihadapi]\n\n### Rencana Minggu Depan\n- [Tuliskan rencana untuk minggu depan]\n\n### Catatan Tambahan\n- [Catatan lainnya]`;
            break;
            
        case 'weekly':
            template = `## Laporan Mingguan\nPeriode: [Tanggal] - [Tanggal]\n\n### Pencapaian Utama\n- [Pencapaian minggu ini]\n\n### Statistik\n- Total tugas diselesaikan: [jumlah]\n- Tugas dalam progress: [jumlah]\n- Persentase completion: [%]\n\n### Tim Performance\n- [Evaluasi performa tim]\n\n### Issues & Risks\n- [Masalah dan risiko]\n\n### Action Items\n- [Item yang perlu ditindaklanjuti]`;
            break;
            
        case 'issue':
            template = `## Laporan Masalah\n\n### Deskripsi Masalah\n- [Jelaskan masalah secara detail]\n\n### Dampak\n- [Dampak terhadap proyek]\n\n### Root Cause\n- [Akar penyebab masalah]\n\n### Solusi yang Diusulkan\n- [Solusi yang diusulkan]\n\n### Timeline Penyelesaian\n- [Estimasi waktu penyelesaian]\n\n### Resources Needed\n- [Sumber daya yang dibutuhkan]`;
            break;
            
        case 'milestone':
            template = `## Milestone Report\n\n### Milestone Achieved\n- [Milestone yang dicapai]\n\n### Key Deliverables\n- [Deliverable utama]\n\n### Quality Metrics\n- [Metrik kualitas]\n\n### Lessons Learned\n- [Pelajaran yang dipetik]\n\n### Next Milestone\n- [Milestone selanjutnya]\n\n### Recommendations\n- [Rekomendasi untuk ke depan]`;
            break;
    }
    
    contentField.value = template;
    typeField.value = type;
}

// Auto-generate title based on project and type
document.getElementById('project_id').addEventListener('change', generateTitle);
document.getElementById('type').addEventListener('change', generateTitle);

function generateTitle() {
    const projectSelect = document.getElementById('project_id');
    const typeSelect = document.getElementById('type');
    const titleField = document.getElementById('title');
    
    // Hanya generate jika judul masih kosong
    if (projectSelect.value && typeSelect.value && !titleField.value.trim()) {
        const projectName = projectSelect.options[projectSelect.selectedIndex].text.trim();
        const typeText = typeSelect.options[typeSelect.selectedIndex].text;
        const date = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        
        titleField.value = `Laporan ${typeText} - ${projectName} - ${date}`;
    }
}
</script>
@endpush
