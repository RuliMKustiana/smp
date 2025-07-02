<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Notifications\TaskUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskUpdateController extends Controller
{
    /**
     * Menampilkan form untuk membuat update tugas baru.
     */
    public function create(Task $task): View
    {
        // Otorisasi sederhana, karyawan hanya boleh update tugasnya sendiri.
        if ($task->assigned_to_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        return view('employee.task-updates.create', compact('task'));
    }

    /**
     * Menyimpan update tugas baru ke database.
     */
    public function store(Request $request, Task $task)
    {
        // Otorisasi
        if ($task->assigned_to_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'status' => 'nullable|in:in_progress,completed,on_hold',
            'hours_worked' => 'nullable|numeric|min:0|max:24',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            'link' => 'nullable|url|max:255',
        ]);

        $statusMap = [
            'in_progress' => 'In Progress',
            'completed' => 'Selesai',
            'on_hold' => 'Blocked'
        ];
        $dbStatus = isset($validated['status']) ? $statusMap[$validated['status']] : null;

        // Buat record update tugas
        $task->updates()->create([
            'user_id' => Auth::id(),
            'description' => $validated['description'],
            'hours_worked' => $validated['hours_worked'] ?? null,
            'status_change' => $dbStatus,
            'link' => $validated['link'] ?? null,
        ]);
        
        // Handle file uploads jika ada
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/tasks', 'public');
                
                // PERBAIKAN: Lampiran disimpan ke relasi milik $task, bukan $taskUpdate
                $task->attachments()->create([
                    'user_id' => Auth::id(),
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Update status tugas utama jika karyawan memilih status baru
        if ($dbStatus) {
            $task->update(['status' => $dbStatus]);
        }

        // Kirim notifikasi ke Project Manager
        $projectManager = $task->assignedBy;
        if($projectManager) {
            // $projectManager->notify(new TaskUpdated($taskUpdate));
        }

        return redirect()->route('employee.tasks.show', $task)->with('success', 'Update progres berhasil ditambahkan.');
    }

    public function edit(Task $task, TaskUpdate $update)
    {
        if ($update->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.task-updates.edit', compact('task', 'update'));
    }

    public function update(Request $request, Task $task, TaskUpdate $update)
    {
        if ($update->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'hours_worked' => 'nullable|numeric|min:0|max:24',
        ]);

        $update->update($validated);

        return redirect()->route('employee.tasks.show', $task)
            ->with('success', 'Update progress berhasil diperbarui.');
    }
}
