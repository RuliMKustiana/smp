<?php

namespace App\Http\Controllers\TeamMember;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\User;
use App\Notifications\TaskStatusChanged;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class TaskUpdateController extends Controller
{
    use AuthorizesRequests;

    public function create(Task $task): View
    {
        // dd([
        //     'user_sedang_login_id'    => Auth::user()->id, // Perbaikan di sini
        //     'tugas_ini_milik_user_id' => $task->assigned_to_id,
        //     'status_tugas_saat_ini'   => $task->status,
        //     'daftar_status_diizinkan' => ['To-Do', 'In Progress', 'Revisi', 'In Review'],
        //     'apakah_user_diizinkan?'  => ($task->assigned_to_id === Auth::user()->id) && in_array($task->status, ['To-Do', 'In Progress', 'Revisi', 'In Review']) // Perbaikan di sini
        // ]);

        $this->authorize('update', $task);
        return view('teammember.task-updates.create', compact('task'));
    }

    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $newStatus = null;

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'hours_worked' => 'nullable|numeric|min:0|max:24',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            'link' => 'nullable|url|max:255',
        ]);

        if ($user->hasRole('Developer')) {
            // dd([
            //     'role' => 'Developer',
            //     'task_status_before_update' => $task->status,
            //     'is_allowed_status_for_dev' => in_array($task->status, ['To-Do', 'In Progress', 'Revisi', 'In Review']),
            //     'request_description_length' => strlen($validated['description']),
            //     'validation_passed' => true 
            // ]);

            if (!in_array($task->status, ['To-Do', 'In Progress', 'Revisi', 'In Review'])) {
                return back()->with('error', 'Anda hanya bisa mengirim update untuk tugas yang sedang dikerjakan, perlu revisi, atau sedang dalam review.');
            }

            if (in_array($task->status, ['To-Do', 'In Progress', 'Revisi'])) {
                $newStatus = 'In Review';
            } else {
                $newStatus = $task->status;
            }
        } elseif ($user->hasRole('QA')) {
            // AKTIFKAN BARIS dd() INI UNTUK DEBUGGING
            // dd([
            //     'role' => 'QA',
            //     'task_status_before_update' => $task->status,
            //     'is_allowed_status_for_qa' => ($task->status === 'In Review'),
            //     'request_status' => $request->status ?? 'N/A',
            //     'request_description_length' => strlen($validated['description']),
            //     'validation_passed' => true // Asumsi validasi awal lolos
            // ]);

            if ($task->status !== 'In Review') {
                return back()->with('error', 'Anda hanya bisa mereview tugas yang statusnya In Review.');
            }
            $request->validate([
                'status' => ['required', Rule::in(['Completed', 'Revisi'])],
            ]);
            $newStatus = $request->status;
        }

        $taskUpdate = $task->updates()->create([
            'user_id' => $user->id,
            'description' => $validated['description'],
            'hours_worked' => $validated['hours_worked'] ?? null,
            'status_change' => $newStatus,
            'link' => $validated['link'] ?? null,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/tasks', 'public');
                $task->attachments()->create([
                    'user_id' => $user->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        if ($newStatus) {
            $task->update(['status' => $newStatus]);

            $projectManager = $task->project->projectManager;
            $assignedDeveloper = $task->assignedTo;

            if ($newStatus === 'In Review') {
                $message = "Tugas '{$task->title}' telah dikirim untuk direview.";
                if ($projectManager) {
                    $projectManager->notify(new TaskStatusChanged($task, $message, $user));
                }
            } elseif ($newStatus === 'Completed') {
                $message = "Tugas '{$task->title}' telah diselesaikan oleh QA.";
                if ($projectManager) {
                    $projectManager->notify(new TaskStatusChanged($task, $message, $user));
                }
            } elseif ($newStatus === 'Revisi') {
                $message = "Tugas '{$task->title}' memerlukan revisi.";
                if ($projectManager) {
                    $projectManager->notify(new TaskStatusChanged($task, $message, $user));
                }
                if ($assignedDeveloper) {
                    $assignedDeveloper->notify(new TaskStatusChanged($task, $message, $user));
                }
            }
        }

        return redirect()->route('teammember.tasks.show', $task)->with('success', 'Update progres berhasil ditambahkan.');
    }

    public function edit(Task $task, TaskUpdate $update)
    {
        if ($update->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan untuk mengedit update ini.');
        }
        return view('teammember.task-updates.edit', compact('task', 'update'));
    }

    public function update(Request $request, Task $task, TaskUpdate $update)
    {
        if ($update->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan untuk mengupdate update ini.');
        }

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'hours_worked' => 'nullable|numeric|min:0|max:24',
        ]);

        $update->update($validated);

        return redirect()->route('teammember.tasks.show', $task)
            ->with('success', 'Update progress berhasil diperbarui.');
    }
}
