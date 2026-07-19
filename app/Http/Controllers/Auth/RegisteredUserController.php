<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $divisis = \App\Models\Divisi::all();
        return view('auth.register', compact('divisis'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role'     => ['required', 'in:anggota,pembina'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($request->role === 'anggota') {
            $validationRules['no_hp'] = ['required', 'string', 'max:20'];
            $validationRules['divisi_id'] = ['required', 'exists:divisi,id_divisi'];
        }

        $request->validate($validationRules);

        $anggotaId = null;
        if ($request->role === 'anggota') {
            $anggota = Anggota::create([
                'nama'              => $request->name,
                'no_hp'             => $request->no_hp,
                'divisi_id'         => $request->divisi_id,
                'tanggal_bergabung' => Carbon::now(),
                'status_anggota'    => 'aktif',
                'jabatan'           => 'Anggota',
            ]);
            $anggotaId = $anggota->id_anggota;
        }

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'anggota_id' => $anggotaId,
            'password'   => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
