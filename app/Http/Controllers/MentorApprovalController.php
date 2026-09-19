<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MentorApprovalController extends Controller
{
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Pastikan akun yang diapprove adalah role peserta dan dari sektor yang sama
        if ($user->role !== 'peserta' || $user->sektor != auth()->user()->sektor) {
            abort(403, 'Anda tidak berhak menyetujui akun dari sektor lain.');
        }

        $user->is_approved = true;
        $user->save();

        return redirect()->back()->with('success', 'Pendaftaran akun ' . $user->name . ' berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Pastikan akun yang direject adalah role peserta dan dari sektor yang sama
        if ($user->role !== 'peserta' || $user->sektor != auth()->user()->sektor) {
            abort(403, 'Anda tidak berhak menolak akun dari sektor lain.');
        }

        // Hapus akun yang ditolak
        $user->delete();

        return redirect()->back()->with('success', 'Pendaftaran akun ' . $user->name . ' berhasil ditolak dan dihapus.');
    }
}
