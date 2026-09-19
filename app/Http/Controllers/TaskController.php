<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

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
            'attachment_type' => 'nullable|in:none,link,file',
            'attachment_link' => 'nullable|url|required_if:attachment_type,link',
            'attachment_file' => 'nullable|file|max:51200|required_if:attachment_type,file',
        ]);

        $attachmentUrl = null;
        $attachmentType = $request->attachment_type === 'none' ? null : $request->attachment_type;

        if ($attachmentType === 'link') {
            $attachmentUrl = $request->attachment_link;
        } elseif ($attachmentType === 'file' && $request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tasks_attachments', $filename, 'public');
            $attachmentUrl = $path;
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'sector' => $request->sector ?? 0,
            'task_type' => $request->task_type,
            'due_date' => $request->due_date,
            'attachment_type' => $attachmentType,
            'attachment_url' => $attachmentUrl,
            'created_by' => auth()->id(),
            'status' => 'active'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'Tugas berhasil dibuat.');
            return response()->json(['success' => true]);
        }

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
            'attachment_type' => 'nullable|in:none,link,file',
            'attachment_link' => 'nullable|url|required_if:attachment_type,link',
            'attachment_file' => 'nullable|file|max:51200', // Only required if new file is uploaded
        ]);

        $attachmentType = $request->attachment_type === 'none' ? null : $request->attachment_type;
        $attachmentUrl = $task->attachment_url;

        // If type changed or explicitly set to none/link, and old was file, delete old file
        if ($task->attachment_type === 'file' && ($attachmentType !== 'file' || $request->hasFile('attachment_file'))) {
            if ($task->attachment_url) {
                Storage::disk('public')->delete($task->attachment_url);
            }
            if ($attachmentType !== 'file') {
                $attachmentUrl = null;
            }
        }

        if ($attachmentType === 'link') {
            $attachmentUrl = $request->attachment_link;
        } elseif ($attachmentType === 'file' && $request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tasks_attachments', $filename, 'public');
            $attachmentUrl = $path;
        } elseif ($attachmentType === 'none') {
            $attachmentUrl = null;
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'sector' => $request->sector ?? 0,
            'task_type' => $request->task_type,
            'due_date' => $request->due_date,
            'attachment_type' => $attachmentType,
            'attachment_url' => $attachmentUrl,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'Tugas berhasil diperbarui.');
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }


    public function destroy(Task $task)
    {
        if ($task->attachment_type === 'file' && $task->attachment_url) {
            Storage::disk('public')->delete($task->attachment_url);
        }
        $task->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id',
        ]);

        $tasks = Task::whereIn('id', $request->task_ids)->get();
        foreach ($tasks as $task) {
            if ($task->attachment_type === 'file' && $task->attachment_url) {
                Storage::disk('public')->delete($task->attachment_url);
            }
            $task->delete();
        }

        return redirect()->back()->with('success', count($request->task_ids) . ' Tugas berhasil dihapus.');
    }
}
