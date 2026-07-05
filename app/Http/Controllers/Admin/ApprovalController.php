<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        // Get all unapproved accounts for mentor, acara, keamanan, panitia
        $pendingUsers = User::where('is_approved', false)
            ->whereIn('role', ['mentor', 'acara', 'keamanan', 'panitia'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.approvals.index', compact('pendingUsers'));
    }

    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->is_approved = true;
        $user->save();

        return redirect()->back()->with('success', 'Akun ' . $user->name . ' berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Optionally, you might want to log this or send an email before deleting
        $user->delete();

        return redirect()->back()->with('success', 'Pendaftaran akun ' . $user->name . ' berhasil ditolak dan dihapus.');
    }
}
