<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roles = User::select('role')->distinct()->pluck('role');
        $activeRole = $request->input('role', $roles->contains('savior') ? 'savior' : ($roles->first() ?? 'savior'));

        $query = User::where('role', $activeRole);
        
        if ($activeRole === 'peserta') {
            $query->orderBy('sektor', 'asc');
        }
        
        $users = $query->orderBy('name', 'asc')
                       ->paginate(20)
                       ->withQueryString();

        $roleCounts = User::select('role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                          ->groupBy('role')
                          ->pluck('total', 'role');

        return view('admin.users.index', compact('roles', 'activeRole', 'users', 'roleCounts'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting any admin accounts
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Akun admin tidak dapat dihapus melalui sistem.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus secara permanen.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Exclude admin from bulk delete
        $deleted = User::whereIn('id', $request->user_ids)
                       ->where('role', '!=', 'admin')
                       ->delete();

        return redirect()->back()->with('success', $deleted . ' Akun berhasil dihapus secara permanen.');
    }

    public function destroyByRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        if ($request->role === 'admin') {
            return redirect()->back()->with('error', 'Akun admin tidak dapat dihapus massal.');
        }

        $deleted = User::where('role', $request->role)->delete();

        return redirect()->back()->with('success', $deleted . ' Akun dengan role ' . $request->role . ' berhasil dihapus secara permanen.');
    }
}
