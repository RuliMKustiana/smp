<div class="row" wire:sortable-group="onUpdateTasks">
    @foreach(['To-Do', 'In Progress', 'In Review', 'Completed', 'Blocked'] as $status)
    @php
        $tasksInStatus = $tasksByStatus[$status] ?? collect();
        $taskCount = $tasksInStatus->count();
    @endphp
        <div class="col" wire:key="group-{{ $status }}">
            <div class="card bg-gray-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 text-uppercase text-sm font-weight-bold">{{ $status }}</h6>
                    <span class="badge rounded-pill bg-dark">{{ $taskCount }}</span>
                </div>
                <div class="card-body p-3" wire:sortable-group.item-group="{{ $status }}" style="min-height: 500px;">
                    @forelse($tasksInStatus as $task)
                        <div class="card mb-3 shadow-sm cursor-grab" wire:key="task-{{ $task->id }}" wire:sortable-group.item="{{ $task->id }}">
                            <div class="card-body p-3">
                                <a href="{{ route('pm.tasks.show', $task->id) }}" class="text-dark fw-bold text-decoration-none mb-2 d-block">
                                    {{ $task->title }}
                                </a>
                                @if($task->priority ?? null)
                                    @php
                                        $priority_color = 'bg-gradient-secondary';
                                        if ($task->priority == 'Tinggi') $priority_color = 'bg-gradient-danger';
                                        if ($task->priority == 'Sedang') $priority_color = 'bg-gradient-warning';
                                        if ($task->priority == 'Rendah') $priority_color = 'bg-gradient-info';
                                    @endphp
                                    <span class="badge {{ $priority_color }}">{{ $task->priority }}</span>
                                @endif

                                <hr class="horizontal dark my-3">

                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        @if($task->assignedTo)
                                            <img src="https://placehold.co/32x32/EFEFEF/333333?text={{ substr($task->assignedTo->name, 0, 1) }}" alt="avatar" class="avatar avatar-xs rounded-circle">
                                            <span class="ms-2 text-xs font-weight-bold">{{ $task->assignedTo->name }}</span>
                                        @else
                                            <span class="text-xs font-weight-bold">Belum Ditugaskan</span>
                                        @endif
                                    </div>
                                    @if($task->deadline)
                                    <div class="d-flex align-items-center text-xs text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <span>{{ \Carbon\Carbon::parse($task->deadline)->format('d M') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>
