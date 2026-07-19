<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // Otorisasi membaca: Ketua, Wakil Ketua, Sekretaris, Bendahara, dan Admin
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                $currentUserJabatan = strtolower($user->anggota?->jabatan ?? '');
                $isReadAuthorized = in_array($currentUserJabatan, ['ketua', 'wakil ketua', 'sekretaris', 'bendahara'], true) || $user->name === 'admin' || $user->role === 'pembina';

                if (!$isReadAuthorized) {
                    abort(403, 'Anda tidak memiliki hak akses untuk melihat data anggota.');
                }

                return $next($request);
            }),

            // Otorisasi mengelola (write): Hanya Ketua, Wakil Ketua, Sekretaris, dan Admin
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                $currentUserJabatan = strtolower($user->anggota?->jabatan ?? '');
                $isWriteAuthorized = in_array($currentUserJabatan, ['ketua', 'wakil ketua', 'sekretaris'], true) || $user->name === 'admin';

                if (!$isWriteAuthorized) {
                    abort(403, 'Anda tidak memiliki hak akses untuk mengelola data anggota.');
                }

                return $next($request);
            }, except: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        if ($perPage === 'all') {
            $perPageNum = 100000;
        } else {
            $perPageNum = in_array((int)$perPage, [10, 25, 50], true) ? (int)$perPage : 10;
        }

        $anggotas = Anggota::with(['divisi', 'user'])
            ->when($request->search, fn($q, $s) => $q->where('nama', 'like', "%$s%")->orWhere('jabatan', 'like', "%$s%"))
            ->when($request->jabatan, fn($q, $j) => $q->where('jabatan', $j))
            ->when($request->divisi_id, fn($q, $d) => $q->where('divisi_id', $d))
            ->when($request->status, fn($q, $s) => $q->where('status_anggota', $s))
            ->orderBy('nama')
            ->paginate($perPageNum)
            ->withQueryString();

        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('members.index', compact('anggotas', 'divisis'));
    }

    public function create()
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();
        return view('members.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'jabatan'           => 'required|string|max:100',
            'tanggal_bergabung' => 'required|date',
            'status_anggota'    => 'required|in:aktif,tidak aktif',
            'alamat'            => 'nullable|string',
            'divisi_id'         => 'nullable|exists:divisi,id_divisi',
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
        $request->validate(['user_id' => 'nullable|exists:users,id_user']);

        // Lepas user lama yang terhubung ke anggota ini, reset rolenya kembali ke 'anggota'
        $oldUser = User::where('anggota_id', $member->id_anggota)->first();
        if ($oldUser) {
            $oldUser->update([
                'anggota_id' => null,
                'role' => 'anggota'
            ]);
        }

        if ($request->user_id) {
            $newUser = User::find($request->user_id);
            
            // Tentukan role berdasarkan jabatan anggota
            $role = 'anggota';
            $jabatanLower = strtolower($member->jabatan);
            if (in_array($jabatanLower, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true)) {
                $role = 'pengurus';
            } elseif ($jabatanLower === 'penanggung jawab') {
                $role = 'penanggung jawab';
            }

            $newUser->update([
                'anggota_id' => $member->id_anggota,
                'role' => $role
            ]);
        }

        return back()->with('success', 'Akun berhasil dihubungkan ke anggota.');
    }

    public function edit(Anggota $member)
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();
        return view('members.edit', ['anggota' => $member, 'divisis' => $divisis]);
    }

    public function update(Request $request, Anggota $member)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'jabatan'           => 'required|string|max:100',
            'tanggal_bergabung' => 'required|date',
            'status_anggota'    => 'required|in:aktif,tidak aktif',
            'alamat'            => 'nullable|string',
            'divisi_id'         => 'nullable|exists:divisi,id_divisi',
        ]);

        $data = $request->all();

        // Cek otorisasi untuk mengubah jabatan (Ketua, Wakil Ketua, Sekretaris, atau user Admin)
        $currentUserJabatan = strtolower(auth()->user()->anggota?->jabatan ?? '');
        $allowedToChangeJabatan = in_array($currentUserJabatan, ['ketua', 'wakil ketua', 'sekretaris'], true) || auth()->user()->name === 'admin';

        if (!$allowedToChangeJabatan) {
            // Paksa jabatan tetap menggunakan nilai yang lama di database
            $data['jabatan'] = $member->jabatan;
        }

        $member->update($data);

        // Jika anggota ini memiliki user terhubung, perbarui rolenya sesuai jabatan baru
        $user = User::where('anggota_id', $member->id_anggota)->first();
        if ($user) {
            $role = 'anggota';
            $jabatanLower = strtolower($member->jabatan);
            if (in_array($jabatanLower, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true)) {
                $role = 'pengurus';
            } elseif ($jabatanLower === 'penanggung jawab') {
                $role = 'penanggung jawab';
            }
            $user->update(['role' => $role]);
        }

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $member)
    {
        if (auth()->user()->anggota_id === $member->id_anggota) {
            return redirect()->route('members.index')->with('error', 'Anda tidak dapat menghapus diri Anda sendiri dari data anggota.');
        }

        // Hapus user yang terhubung dengan anggota ini
        User::where('anggota_id', $member->id_anggota)->delete();

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
