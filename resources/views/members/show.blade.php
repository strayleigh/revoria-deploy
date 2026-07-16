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
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-semibold">{{ $anggota->nik ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">No. HP</p>
                    <p class="fw-semibold">{{ $anggota->no_hp ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Jabatan</p>
                    <p class="fw-semibold">{{ $anggota->jabatan ?: '-' }}</p>
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
                <a href="{{ route('members.edit', $anggota) }}" class="btn btn-warning px-4">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary px-4">Kembali</a>
            </div>
        </div>
    </div>
</x-sidebar>
