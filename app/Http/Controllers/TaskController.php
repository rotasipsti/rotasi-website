<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sector' => 'nullable|integer',
            'task_type' => 'required|in:individu,per_sektor,angkatan',
            'due_date' => 'required|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'sector' => $request->sector ?? 0,
            'task_type' => $request->task_type,
            'due_date' => $request->due_date,
            'created_by' => auth()->id(),
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dibuat.');
    }
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sector' => 'nullable|integer',
            'task_type' => 'required|in:individu,per_sektor,angkatan',
            'due_date' => 'required|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'sector' => $request->sector ?? 0,
            'task_type' => $request->task_type,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus.');
    }
}
