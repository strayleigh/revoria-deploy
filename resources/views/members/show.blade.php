@php
    $currentUserJabatan = strtolower(auth()->user()->anggota?->jabatan ?? '');
    $canManage = in_array($currentUserJabatan, ['ketua', 'wakil ketua', 'sekretaris'], true) || auth()->user()->name === 'admin';
@endphp
<x-sidebar title="Detail Anggota">
    <h2 class="mb-4 fw-bold">Detail Anggota</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Nama</p>
                    <p class="fw-semibold">{{ $anggota->nama }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">No. HP</p>
                    <p class="fw-semibold">{{ $anggota->no_hp ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Jabatan</p>
                    <p class="fw-semibold">{{ $anggota->jabatan ?: 'Anggota' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Divisi</p>
                    <p class="fw-semibold">{{ $anggota->divisi?->nama_divisi ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Tanggal Bergabung</p>
                    <p class="fw-semibold">{{ $anggota->tanggal_bergabung?->format('d M Y') ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Status</p>
                    <span class="badge {{ $anggota->status_anggota === 'aktif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} fs-6">
                        {{ ucfirst($anggota->status_anggota) }}
                    </span>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="fw-semibold">{{ $anggota->alamat ?: '-' }}</p>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                @if($canManage)
                    <a href="{{ route('members.edit', $anggota) }}" class="btn btn-warning px-4">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary px-4">Kembali</a>
            </div>
        </div>
    </div>

    @if($canManage)
        <!-- ================= HUBUNGKAN AKUN USER ================= -->
        <div class="card border-0 shadow rounded-4 mt-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1">Akun Pengguna Terhubung</h5>
                <p class="text-muted small mb-3">Tautkan anggota ini dengan akun pengguna untuk mengelola perannya.</p>

                @if(session('success'))
                    <div class="alert alert-success rounded-3 py-2 mb-3">{{ session('success') }}</div>
                @endif

                @php
                    $linkedUser = \App\Models\User::where('anggota_id', $anggota->id_anggota)->first();
                @endphp

                @if($linkedUser)
                    <div class="alert alert-info rounded-3 d-flex justify-content-between align-items-center mb-0 flex-wrap gap-2">
                        <div>
                            <i class="bi bi-person-check-fill me-2"></i>
                            Terhubung dengan: <strong>{{ $linkedUser->name }}</strong> ({{ $linkedUser->email }}) 
                            &bull; Peran: <span class="badge bg-primary">{{ ucfirst($linkedUser->role) }}</span>
                        </div>
                        <form method="POST" action="{{ route('members.assign', $anggota) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="user_id" value="">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-link-45deg"></i> Putuskan Hubungan
                            </button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('members.assign', $anggota) }}" class="row g-3 align-items-center">
                        @csrf @method('PATCH')
                        <div class="col-md-8">
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Pilih Akun User untuk Dihubungkan --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }}) [{{ ucfirst($user->role) }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-link-45deg"></i> Hubungkan Akun
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</x-sidebar>
