<?php
namespace App\Livewire\Task;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskUpdate;
use Illuminate\Support\Facades\Auth;

class TaskUpdates extends Component
{
    public Task $task;
    public $description;
    public $hours_spent;

    protected $rules = [
        'description' => 'required|string|min:10',
        'hours_spent' => 'nullable|numeric|min:0',
    ];

    public function submitUpdate()
    {
        $this->validate();

        $this->task->updates()->create([
            'user_id' => Auth::id(),
            'description' => $this->description,
            'hours_spent' => $this->hours_spent,
        ]);

        // Kirim notifikasi ke PM
        $this->task->project->projectManager->notify(new \App\Notifications\TaskProgressUpdated($this->task));

        $this->reset(['description', 'hours_spent']);

        session()->flash('message', 'Progress berhasil dilaporkan.');
    }

    public function render()
    {
        $updates = $this->task->updates()->with('user')->latest()->get();
        return view('livewire.task.task-updates', compact('updates'));
    }
}