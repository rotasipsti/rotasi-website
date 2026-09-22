<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($request->user()->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($request->user()->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $request->user()->profile_photo_path = $path;
        }

        $request->user()->save();

        $role = $request->user()->role;
        $profileRouteName = 'peserta.profile.edit';
        
        if ($role === 'admin') {
            $profileRouteName = 'admin.profile.edit';
        } elseif ($role === 'mentor') {
            $profileRouteName = 'mentor.profile.edit';
        } elseif ($role === 'acara') {
            $profileRouteName = 'acara.profile.edit';
        } elseif ($role === 'keamanan') {
            $profileRouteName = 'keamanan.profile.edit';
        } elseif ($role === 'panitia') {
            $profileRouteName = 'panitia.profile.edit';
        } elseif ($role === 'stakeholder') {
            $profileRouteName = 'stakeholder.profile.edit';
        }

        return Redirect::route($profileRouteName)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
