<div class="dropdown">
    <button class="btn btn-link position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fs-5"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li>
            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                Notifikasi
                @if($unreadCount > 0)
                    <small class="text-muted">{{ $unreadCount }} baru</small>
                @endif
            </h6>
        </li>
        <li><hr class="dropdown-divider"></li>
        
        @forelse($notifications as $notification)
            <li>
                <a class="dropdown-item notification-item" href="#" onclick="markAsRead('{{ $notification->id }}')">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            @switch($notification->data['type'] ?? '')
                                @case('task_assigned')
                                    <i class="fas fa-tasks text-primary"></i>
                                    @break
                                @case('task_updated')
                                    <i class="fas fa-edit text-warning"></i>
                                    @break
                                @case('report_validated')
                                    <i class="fas fa-check-circle text-success"></i>
                                    @break
                                @default
                                    <i class="fas fa-bell text-info"></i>
                            @endswitch
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 fs-6">{{ $notification->data['title'] ?? 'Notifikasi' }}</h6>
                            <p class="mb-1 small text-muted">{{ $notification->data['message'] ?? '' }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
            </li>
            @if(!$loop->last)
                <li><hr class="dropdown-divider"></li>
            @endif
        @empty
            <li>
                <div class="dropdown-item-text text-center text-muted py-3">
                    <i class="fas fa-bell-slash mb-2"></i>
                    <br>
                    Tidak ada notifikasi baru
                </div>
            </li>
        @endforelse
        
        @if($unreadCount > 0)
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                    Lihat semua notifikasi
                </a>
            </li>
        @endif
    </ul>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(response => {
        if (response.ok) {
            // Reload notification dropdown atau update UI
            location.reload();
        }
    });
}
</script>

<style>
.notification-dropdown {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item h6 {
    font-weight: 600;
}
</style>
