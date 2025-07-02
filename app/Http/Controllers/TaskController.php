// TaskController.php
public function show(Task $task)
{
    // Otorisasi: hanya karyawan yang ditugaskan atau PM proyeknya yang bisa lihat
    $this->authorize('view', $task);
    return view('employee.tasks.show', compact('task'));
}