<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class KanbanBoard extends Component
{
    public $projectId;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @param array
     * @return void
     */
    public function onUpdateTasks($groups)
    {
        foreach ($groups as $group) {
            $newStatus = $group['value'];

            foreach ($group['items'] as $index => $item) {

                $taskId = $item['value'];
                
                Task::find($taskId)->update([
                    'status' => $newStatus,
                    'position' => $index + 1, 
                ]);
            }
        }
    }

    public function render()
    {
        
        $project = Project::with([
            'tasks' => function ($query) {
                $query->with('assignedTo')->orderBy('position', 'asc');
            }
        ])->findOrFail($this->projectId);

        $tasksByStatus = $project->tasks->groupBy('status');
        
        return view('livewire.project.kanban-board', [
            'project' => $project,
            'tasksByStatus' => $tasksByStatus
        ]);
    }
}
