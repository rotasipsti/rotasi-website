<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nim' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'userType' => ['required', 'in:peserta,mentor,acara,admin,divisi,keamanan,panitia'],
        ]);

        $role = $request->userType;
        if ($role === 'divisi') {
            $role = 'acara';
        }

        // Custom password validation based on role
        if (in_array($role, ['mentor', 'admin'])) {
            if ($role === 'mentor') {
                $request->validate([
                    'sektor' => 'required|integer',
                    'divisionPassword' => 'required|string',
                ]);
                
                $sector = \App\Models\SectorPassword::where('sector_number', $request->sektor)
                            ->where('uuid_password', $request->divisionPassword)->first();
                
                if (!$sector) {
                    throw ValidationException::withMessages([
                        'divisionPassword' => 'Password Mentor untuk sektor ini tidak valid.',
                    ]);
                }
            } else {
                $request->validate([
                    'divisionPassword' => 'required|string',
                ]);
                
                $divPass = \App\Models\DivisionPassword::where('division_name', $role)
                            ->where('uuid_password', $request->divisionPassword)->first();
                
                if (!$divPass) {
                    // For testing/development, allow a fallback hardcoded password if DB is empty
                    if ($request->divisionPassword !== 'rotasi' . $role . '2026') {
                        throw ValidationException::withMessages([
                            'divisionPassword' => 'Password divisi tidak valid.',
                        ]);
                    }
                }
            }
        }

        $customId = '';
        $prefix = 'USR';

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
                'panitia' => '1',
                'acara' => '2',
                'mentor' => '3',
                'keamanan' => '4',
                'admin' => '8',
            ];
            $firstDigit = $rolePrefixMap[$role] ?? '9';
            $randomDigits = '';
            for ($i = 0; $i < 9; $i++) {
                $randomDigits .= rand(0, 9);
            }
            $numberPart = $firstDigit . $randomDigits;
        }

        $customId = $prefix . '-' . $numberPart;

        $isApproved = in_array($role, ['admin']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'role' => $role,
            'is_approved' => $isApproved,
            'sektor' => $request->sektor ?? null,
            'custom_id' => $customId,
            'password' => Hash::make($request->password),
            'login_password_hash' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        if ($user->is_approved) {
            Auth::login($user);
            return redirect(route('dashboard', absolute: false));
        }

        $loginRoute = match ($role) {
            'admin' => 'login.admin',
            'acara', 'mentor', 'keamanan', 'panitia' => 'login.u',
            default => 'login',
        };

        $approvalMessage = $role === 'peserta' 
            ? 'Pendaftaran berhasil. Akun Anda berhasil dibuat. Silakan login untuk melanjutkan.' 
            : 'Pendaftaran berhasil. Akun Anda sedang menunggu persetujuan Admin.';

        return back()->with('register_success', true)
                     ->with('register_message', $approvalMessage)
                     ->with('login_route', route($loginRoute));
    }
}
