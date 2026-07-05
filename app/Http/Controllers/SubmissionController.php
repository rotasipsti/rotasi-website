<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|max:51200', // Max 50MB
        ]);

        $submission = TaskSubmission::where('task_id', $request->task_id)
                                    ->where('participant_id', auth()->id())
                                    ->first();

        if (!$submission) {
            $submission = new TaskSubmission();
            $submission->task_id = $request->task_id;
            $submission->participant_id = auth()->id();
        }

        $submission->submission_text = $request->submission_text;
        
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($submission->file_url) {
                // Parse storage path from URL
                $oldPath = str_replace(asset('storage/'), '', $submission->file_url);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('task-submissions', $fileName, 'public');
            
            $submission->file_url = asset('storage/' . $filePath);
            $submission->file_name = $file->getClientOriginalName();
        }

        $submission->submitted_at = \Carbon\Carbon::now();
        // keep status evaluated if already evaluated, else submitted
        if ($submission->status !== 'evaluated') {
            $submission->status = 'submitted';
        }
        
        $submission->save();

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'Tugas berhasil diupload.');
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil diupload.');
    }

    public function evaluate(Request $request, TaskSubmission $submission)
    {
        $request->validate([
            'evaluation_score' => 'required|integer|min:0|max:100',
            'evaluation_comment' => 'nullable|string',
        ]);

        $submission->evaluation_score = $request->evaluation_score;
        $submission->evaluation_comment = $request->evaluation_comment;
        $submission->status = 'evaluated';
        $submission->evaluated_by = auth()->id();
        $submission->evaluated_at = \Carbon\Carbon::now();
        $submission->save();

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
