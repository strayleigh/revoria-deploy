<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $anggotas = Anggota::query()
            ->when($request->search, fn($q, $s) => $q->where('nama', 'like', "%$s%")->orWhere('jabatan', 'like', "%$s%"))
            ->when($request->jabatan, fn($q, $j) => $q->where('jabatan', $j))
            ->when($request->status, fn($q, $s) => $q->where('status_anggota', $s))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('members.index', compact('anggotas'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'no_hp'             => 'required|string|max:20',
            'jabatan'           => 'required|string|max:100',
            'tanggal_bergabung' => 'required|date',
            'status_anggota'    => 'required|in:aktif,tidak aktif',
            'alamat'            => 'required|string',
            'nik'               => 'nullable|string|max:20',
        ]);

        Anggota::create($request->all());

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Anggota $member)
    {
        $users = User::whereIn('role', ['anggota', 'pembina'])
            ->where(function ($q) use ($member) {
                $q->whereNull('anggota_id')->orWhere('anggota_id', $member->id_anggota);
            })
            ->orderBy('name')->get();

        return view('members.show', ['anggota' => $member, 'users' => $users]);
    }

    public function assignUser(Request $request, Anggota $member)
    {
        $request->validate(['user_id' => 'nullable|exists:users,id']);

        // Lepas user lama yang terhubung ke anggota ini
        User::where('anggota_id', $member->id_anggota)->update(['anggota_id' => null]);

        if ($request->user_id) {
            User::where('id', $request->user_id)->update(['anggota_id' => $member->id_anggota]);
        }

        return back()->with('success', 'Akun berhasil dihubungkan ke anggota.');
    }

    public function edit(Anggota $member)
    {
        return view('members.edit', ['anggota' => $member]);
    }

    public function update(Request $request, Anggota $member)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'no_hp'             => 'required|string|max:20',
            'jabatan'           => 'required|string|max:100',
            'tanggal_bergabung' => 'required|date',
            'status_anggota'    => 'required|in:aktif,tidak aktif',
            'alamat'            => 'required|string',
            'nik'               => 'nullable|string|max:20',
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
