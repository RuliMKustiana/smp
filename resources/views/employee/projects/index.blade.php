@extends('layouts.app')

@section('title', 'Proyek Saya')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER HALAMAN --}}
    
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h5 class="text-white text-capitalize mb-0">Proyek Saya</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($projects as $project)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm project-card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('employee.projects.show', $project) }}" class="text-dark fw-bold text-decoration-none">
                                        {{ $project->name }}
                                    </a>
                                </h6>
                                <p class="text-sm text-muted mb-0">
                                    PM: {{ $project->projectManager?->name ?? 'N/A' }}
                                </p>
                            </div>
                            @if($project->status === 'planning')
                                <span class="badge bg-gradient-warning">Perencanaan</span>
                            @elseif($project->status === 'active')
                                <span class="badge bg-gradient-success">Aktif</span>
                            @elseif($project->status === 'completed')
                                <span class="badge bg-gradient-primary">Selesai</span>
                            @elseif($project->status === 'on_hold')
                                <span class="badge bg-gradient-secondary">Ditunda</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($project->description)
                            <p class="text-sm text-muted">{{ Str::limit($project->description, 120) }}</p>
                        @endif
                        
                        @php
                            $myTasks = $project->tasks; // Asumsi controller sudah memfilter tugas untuk user ini
                            $totalMyTasks = $myTasks->count();
                            $myCompletedTasks = $myTasks->where('status', 'Selesai')->count();
                            $progress = $totalMyTasks > 0 ? ($myCompletedTasks / $totalMyTasks) * 100 : 0;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-sm">Progress Tugas Saya</span>
                                <span class="text-sm fw-bold">{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-gradient-success" role="progressbar" 
                                     style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}"></div>
                            </div>
                            <small class="text-muted">{{ $myCompletedTasks }}/{{ $totalMyTasks }} tugas selesai</small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-top pt-3 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('employee.projects.show', $project) }}" 
                               class="btn btn-sm btn-outline-dark mb-0">
                                <i class="fas fa-eye me-1"></i> Detail Proyek
                            </a>
                            
                            <div class="d-flex align-items-center">
                                <div class="avatar-group">
                                    @foreach($project->members->take(4) as $member)
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $member->name }}">
                                            <img src="{{ $member->profile_photo_url ?? 'https://placehold.co/40x40/EFEFEF/333333?text='.substr($member->name, 0, 1) }}" alt="{{ $member->name }}">
                                        </a>
                                    @endforeach
                                    @if($project->members->count() > 4)
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="+{{ $project->members->count() - 4 }} lainnya">
                                            <div class="bg-light text-dark d-flex align-items-center justify-content-center h-100">
                                                <small>+{{ $project->members->count() - 4 }}</small>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Proyek</h5>
                        <p class="text-muted">Anda belum tergabung dalam proyek apapun.</p>
                    </div>
                </div>
            </div>
        @endforelse
    
    
    @if($projects->count() > 0)
        <div class="d-flex justify-content-center mt-4">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.project-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.project-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
.avatar-group .avatar {
    margin-left: -10px;
    border: 2px solid #fff;
    transition: transform 0.2s ease;
}
.avatar-group .avatar:hover {
    transform: scale(1.1) translateY(-2px);
    z-index: 10;
}
</style>
@endpush
