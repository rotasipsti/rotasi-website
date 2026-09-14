<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExitPermission;

class ExitPermissionController extends Controller
{
    public function scanner()
    {
        return view('keamanan.scanner');
    }

    public function history()
    {
        $user = auth()->user();
        
        if (in_array($user->role, ['keamanan', 'admin'])) {
            $exit_permissions = ExitPermission::with('user')
                                ->orderBy('exit_time', 'desc')
                                ->paginate(15);
            $title = "Riwayat Izin Keluar (Keseluruhan)";
        } else {
            $exit_permissions = ExitPermission::where('user_id', $user->id)
                                ->orderBy('exit_time', 'desc')
                                ->paginate(15);
            $title = "Riwayat Izin Keluar Anda";
        }
        
        return view('exit_permissions.index', compact('exit_permissions', 'title', 'user'));
    }

    public function getUserInfo($id)
    {
        // $id could be custom_id or database id
        $user = User::where('custom_id', $id)->orWhere('id', $id)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'custom_id' => $user->custom_id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
        ]);

        ExitPermission::create([
            'user_id' => $request->user_id,
            'reason' => $request->reason,
            'exit_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Izin keluar berhasil dicatat.');
    }

    public function bulkDestroy(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['keamanan', 'admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        ExitPermission::query()->delete();

        return redirect()->back()->with('success', 'Semua riwayat izin keluar berhasil dihapus.');
    }

    public function destroy($id)
    {
        $exit_permission = ExitPermission::findOrFail($id);
        $exit_permission->delete();

        return redirect()->back()->with('success', 'Riwayat izin keluar berhasil dihapus.');
    }
}
