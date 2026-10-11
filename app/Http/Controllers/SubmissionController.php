<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $submission = TaskSubmission::where('task_id', $request->task_id)
                                    ->where('participant_id', auth()->id())
                                    ->first();

        // Wajib jika belum pernah mengumpulkan atau submission belum memiliki file
        $isFileRequired = !$submission || empty($submission->file_url);

        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'submission_text' => 'nullable|string',
            'file' => ($isFileRequired ? 'required' : 'nullable') . '|file|max:51200', // Max 50MB
        ], [
            'task_id.required' => 'ID tugas tidak valid.',
            'task_id.exists' => 'Tugas tidak ditemukan.',
            'file.required' => 'File tugas wajib dilampirkan.',
            'file.file' => 'File yang diunggah tidak valid.',
            'file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
        ]);

        $isNew = !$submission;

        if (!$submission) {
            $submission = new TaskSubmission();
            $submission->task_id = $request->task_id;
            $submission->participant_id = auth()->id();
        }

        $submission->submission_text = $request->submission_text;
        
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($submission->file_url) {
                // Parse storage path dari URL atau relative path
                $parsed = parse_url($submission->file_url, PHP_URL_PATH);
                if ($parsed && str_contains($parsed, '/storage/')) {
                    $oldPath = substr($parsed, strpos($parsed, '/storage/') + strlen('/storage/'));
                } else {
                    $oldPath = str_replace(asset('storage/'), '', $submission->file_url);
                }

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('task-submissions', $fileName, 'public');
            
            $submission->file_url = asset('storage/' . $filePath);
            $submission->file_name = $file->getClientOriginalName();
        }

        $submission->submitted_at = \Carbon\Carbon::now();
        // Pertahankan status evaluated jika sudah dinilai, selain itu submitted
        if ($submission->status !== 'evaluated') {
            $submission->status = 'submitted';
        }
        
        $submission->save();

        $message = $isNew ? 'Tugas berhasil diupload.' : 'Tugas berhasil diperbarui.';

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', $message);
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Request $request, TaskSubmission $submission)
    {
        // Pastikan hanya pemilik tugas atau admin yang dapat membatalkan
        if ($submission->participant_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan pengiriman tugas ini.');
        }

        // Tugas yang sudah dinilai tidak dapat dibatalkan
        if ($submission->status === 'evaluated') {
            $errorMsg = 'Tugas yang sudah dinilai tidak dapat dibatalkan.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        // Hapus file dari storage jika ada
        if ($submission->file_url) {
            $parsed = parse_url($submission->file_url, PHP_URL_PATH);
            if ($parsed && str_contains($parsed, '/storage/')) {
                $filePath = substr($parsed, strpos($parsed, '/storage/') + strlen('/storage/'));
            } else {
                $filePath = str_replace(asset('storage/'), '', $submission->file_url);
            }

            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $submission->delete();

        $message = 'Pengiriman tugas berhasil dibatalkan.';

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', $message);
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
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
