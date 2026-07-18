<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
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
        $anggotas = !in_array($request->user()->role, ['pengurus'], true) || $request->user()->anggota_id
            ? Anggota::whereDoesntHave('user', fn($q) => $q->where('id', '!=', $request->user()->id))
                ->orWhere('id_anggota', $request->user()->anggota_id)
                ->orderBy('nama')->get()
            : collect();

        return view('profile.edit', ['user' => $request->user(), 'anggotas' => $anggotas]);
    }

    public function linkAnggota(Request $request): RedirectResponse
    {
        $request->validate(['anggota_id' => 'nullable|exists:anggota,id_anggota']);

        $updateData = ['anggota_id' => $request->anggota_id ?: null];

        if ($request->anggota_id) {
            $anggota = Anggota::find($request->anggota_id);
            if ($anggota && in_array(strtolower($anggota->jabatan), ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true)) {
                $updateData['role'] = 'pengurus';
            }
        }

        $request->user()->update($updateData);

        return Redirect::route('profile.edit')->with('status', 'anggota-linked');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Hanya admin yang dapat mengubah namanya
        if ($user->name !== 'admin') {
            unset($data['name']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();



        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

        // Set status anggota menjadi 'tidak aktif' jika terhubung
        if ($user->anggota_id) {
            $user->anggota->update([
                'status_anggota' => 'tidak aktif'
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
