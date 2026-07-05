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
}
