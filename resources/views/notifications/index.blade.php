@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Notifikasi</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.mark-all-as-read') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-check-double me-1"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @forelse($notifications as $notification)
                <div class="card mb-3 {{ $notification->read_at ? '' : 'border-primary' }}">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                @switch($notification->data['type'] ?? '')
                                    @case('task_assigned')
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="fas fa-tasks text-primary"></i>
                                        </div>
                                        @break
                                    @case('task_updated')
                                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="fas fa-edit text-warning"></i>
                                        </div>
                                        @break
                                    @case('report_validated')
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                        @break
                                    @default
                                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="fas fa-bell text-info"></i>
                                        </div>
                                @endswitch
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary ms-2">Baru</span>
                                    @endif
                                </h6>
                                <p class="mb-2 text-muted">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    <div>
                                        @if(!$notification->read_at)
                                            <button class="btn btn-sm btn-outline-primary" onclick="markAsRead('{{ $notification->id }}')">
                                                <i class="fas fa-check me-1"></i>
                                                Tandai Dibaca
                                            </button>
                                        @endif
                                        
                                        @if(isset($notification->data['task_id']))
                                            @if(auth()->user()->isProjectManager())
                                                <a href="{{ route('pm.tasks.show', $notification->data['task_id']) }}" class="btn btn-sm btn-primary ms-2">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Lihat Task
                                                </a>
                                            @elseif(auth()->user()->isEmployee())
                                                <a href="{{ route('employee.tasks.show', $notification->data['task_id']) }}" class="btn btn-sm btn-primary ms-2">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Lihat Task
                                                </a>
                                            @endif
                                        @endif

                                        @if(isset($notification->data['report_id']) && auth()->user()->isProjectManager())
                                            <a href="{{ route('pm.reports.show', $notification->data['report_id']) }}" class="btn btn-sm btn-primary ms-2">
                                                <i class="fas fa-eye me-1"></i>
                                                Lihat Laporan
                                            </a>
                                        @endif

                                        @if(isset($notification->data['project_id']))
                                            @if(auth()->user()->isProjectManager())
                                                <a href="{{ route('pm.projects.show', $notification->data['project_id']) }}" class="btn btn-sm btn-outline-secondary ms-2">
                                                    <i class="fas fa-project-diagram me-1"></i>
                                                    Lihat Proyek
                                                </a>
                                            @elseif(auth()->user()->isEmployee())
                                                <a href="{{ route('employee.projects.show', $notification->data['project_id']) }}" class="btn btn-sm btn-outline-secondary ms-2">
                                                    <i class="fas fa-project-diagram me-1"></i>
                                                    Lihat Proyek
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada notifikasi</h4>
                    <p class="text-muted">Anda tidak memiliki notifikasi saat ini.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload halaman untuk update tampilan
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endpush
