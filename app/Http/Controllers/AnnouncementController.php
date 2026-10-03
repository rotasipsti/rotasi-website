<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:popup,running_text',
            'target_role' => 'required|string',
            'link' => 'nullable|url',
            'link_text' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:popup,running_text',
            'target_role' => 'required|string',
            'link' => 'nullable|url',
            'link_text' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
