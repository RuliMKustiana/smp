<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class KanbanBoard extends Component
{
    public $projectId;

    // `mount` sekarang hanya bertugas menyimpan ID proyek
    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    // Method ini akan dipanggil oleh Livewire saat status tugas diperbarui
    public function onStatusUpdate($taskId, $newStatus)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->status = $newStatus;
            $task->save();
            // Tidak perlu memuat ulang data. Livewire akan me-render ulang secara otomatis.
        }
    }

    // Method ini akan dipanggil oleh Livewire saat posisi tugas diubah
    public function onSortOrderUpdate($sortedIds)
    {
        foreach ($sortedIds as $index => $id) {
            Task::where('id', $id)->update(['position' => $index + 1]);
        }
        // Tidak perlu memuat ulang data.
    }

    // Method render sekarang bertanggung jawab penuh atas pengambilan data
    public function render()
    {
        // Ambil semua data yang dibutuhkan di sini, setiap kali komponen di-render
        $project = Project::with([
            'tasks' => function ($query) {
                // Eager load relasi yang diperlukan oleh kartu tugas
                $query->with('assignedTo')->orderBy('position', 'asc');
            }
        ])->findOrFail($this->projectId);

        // Kelompokkan tugas berdasarkan status untuk ditampilkan di papan Kanban
        $tasksByStatus = $project->tasks->groupBy('status');
        
        // Kirim data ke view Livewire
        return view('livewire.project.kanban-board', [
            'project' => $project,
            'tasksByStatus' => $tasksByStatus
        ]);
    }
}
