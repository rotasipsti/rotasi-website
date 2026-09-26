<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roles = collect(['semua', 'admin', 'panitia', 'keamanan', 'acara', 'mentor', 'peserta', 'stakeholder']);
        $activeRole = $request->input('role', 'semua');
        $search = $request->input('search');

        $query = User::query();

        if ($activeRole !== 'semua') {
            $query->where('role', $activeRole);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }
        
        if ($activeRole === 'peserta') {
            $query->orderBy('sektor', 'asc');
        }
        
        $users = $query->orderBy('name', 'asc')
                       ->paginate(20)
                       ->withQueryString();

        $roleCounts = User::select('role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                          ->groupBy('role')
                          ->pluck('total', 'role');
        
        $roleCounts['semua'] = User::count();

        return view('admin.users.index', compact('roles', 'activeRole', 'users', 'roleCounts', 'search'));
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,panitia,keamanan,acara,mentor,peserta,stakeholder',
            'sektor' => 'nullable|string',
            'nim' => 'nullable|string',
        ]);

        $prefix = 'USR';
        $role = $request->role;

        if ($role === 'peserta') {
            $prefix = 'PST';
            if ($request->sektor) {
                $sector = \App\Models\SectorPassword::where('sector_number', $request->sektor)->first();
                if ($sector) {
                    $words = explode(' ', $sector->sector_name);
                    if (count($words) > 1) {
                        $prefix = strtoupper(substr($words[0], 0, 2) . substr($words[1], 0, 1));
                    } else {
                        $prefix = strtoupper(substr($words[0], 0, 3));
                    }
                }
            }
        } elseif ($role === 'acara') {
            $prefix = 'ACR';
        } elseif ($role === 'mentor') {
            $prefix = 'MNT';
        } elseif ($role === 'admin') {
            $prefix = 'ADM';
        } elseif ($role === 'keamanan') {
            $prefix = 'KMN';
        } elseif ($role === 'panitia') {
            $prefix = 'PNT';
        } elseif ($role === 'stakeholder') {
            $prefix = 'STH';
        }

        $numberPart = '';
        if ($role === 'peserta') {
            $sektorNum = $request->sektor ?? 0;
            $prefixNum = '0' . $sektorNum;
            $randomLength = 10 - strlen($prefixNum);
            $randomDigits = '';
            for ($i = 0; $i < $randomLength; $i++) {
                $randomDigits .= rand(0, 9);
            }
            $numberPart = $prefixNum . $randomDigits;
        } else {
            $rolePrefixMap = [
                'panitia' => '5',
                'acara' => '2',
                'mentor' => '3',
                'keamanan' => '4',
                'admin' => '8',
                'stakeholder' => '6',
            ];
            $firstDigit = $rolePrefixMap[$role] ?? '9';
            $randomDigits = '';
            for ($i = 0; $i < 9; $i++) {
                $randomDigits .= rand(0, 9);
            }
            $numberPart = $firstDigit . $randomDigits;
        }

        $customId = $prefix . '-' . $numberPart;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'role' => $role,
            'is_approved' => true,
            'sektor' => $request->sektor ?? null,
            'custom_id' => $customId,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'login_password_hash' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Akun ' . $user->name . ' berhasil ditambahkan dan langsung disetujui.');
    }
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->login_password_hash = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Kata sandi untuk akun ' . $user->name . ' berhasil direset.');
    }
    public function stakeholderIndex(Request $request)
    {
        $roles = collect(['semua', 'panitia', 'keamanan', 'acara', 'mentor', 'peserta', 'stakeholder']);
        $activeRole = $request->input('role', 'semua');
        $search = $request->input('search');

        if ($activeRole === 'admin') {
            abort(403);
        }

        $query = User::query();

        if ($activeRole !== 'semua') {
            $query->where('role', $activeRole);
        } else {
            $query->where('role', '!=', 'admin');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }
        
        if ($activeRole === 'peserta') {
            $query->orderBy('sektor', 'asc');
        }
        
        $users = $query->orderBy('name', 'asc')
                       ->paginate(20)
                       ->withQueryString();

        $roleCounts = User::select('role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                          ->where('role', '!=', 'admin')
                          ->groupBy('role')
                          ->pluck('total', 'role');
                          
        $roleCounts['semua'] = User::where('role', '!=', 'admin')->count();

        return view('stakeholder.users.index', compact('roles', 'activeRole', 'users', 'roleCounts', 'search'));
    }
}
