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

        // Simpan no_hp secara terpisah ke anggota (jika ada) dan hapus dari data user
        $noHp = null;
        if (array_key_exists('no_hp', $data)) {
            $noHp = $data['no_hp'];
            unset($data['no_hp']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($noHp !== null) {
            $anggota = $user->anggota;
            if ($anggota) {
                $anggota->update([
                    'no_hp' => $noHp,
                ]);
            }
        }

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

    public function promptPhoneNumber(Request $request): View
    {
        $anggota = $request->user()->anggota;
        return view('profile.phone-number-prompt', compact('anggota'));
    }

    public function updatePhoneNumber(Request $request): RedirectResponse
    {
        $request->validate([
            'no_hp' => 'required|string|max:20',
        ]);

        $user = $request->user();
        
        $anggota = $user->anggota;
        if (!$anggota) {
            $anggota = Anggota::create([
                'nama' => $user->name,
                'status_anggota' => 'aktif',
                'jabatan' => $user->role === 'pengurus' ? 'Ketua' : ($user->role === 'pembina' ? 'Pembina' : 'Anggota'),
                'tanggal_bergabung' => now(),
            ]);
            $user->update(['anggota_id' => $anggota->id_anggota]);
        }

        $anggota->update([
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('dashboard')->with('success', 'Nomor telepon berhasil dimasukkan.');
    }
}
