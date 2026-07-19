<x-sidebar title="Detail Kegiatan">
    <h2 class="mb-4 fw-bold">Detail Kegiatan</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            @php
                $badgeClass = match($kegiatan->status) {
                    'berlangsung' => 'bg-success',
                    'terjadwal'   => 'bg-warning text-dark',
                    default       => 'bg-secondary',
                };
            @endphp
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h4 class="fw-bold mb-0">{{ $kegiatan->nama_kegiatan }}</h4>
                <span class="badge {{ $badgeClass }}">{{ ucfirst($kegiatan->status) }}</span>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Tanggal</p>
                    <p class="fw-semibold">{{ $kegiatan->tanggal?->format('d M Y') ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Lokasi</p>
                    <p class="fw-semibold">{{ $kegiatan->lokasi ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Progres</p>
                    <div class="progress rounded-pill" style="height:10px;">
                        <div class="progress-bar bg-primary" style="width:{{ $kegiatan->progres ?? 0 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $kegiatan->progres ?? 0 }}%</small>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Deskripsi</p>
                    <p>{{ $kegiatan->deskripsi ?: '-' }}</p>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2 flex-wrap">
                <a href="{{ route('dokumen.folder', $kegiatan->kode_kegiatan) }}" class="btn btn-outline-primary px-4">
                    <i class="bi bi-folder2-open"></i> Dokumen
                </a>
                <a href="{{ route('absensi.index') }}?search={{ urlencode($kegiatan->nama_kegiatan) }}" class="btn btn-outline-success px-4">
                    <i class="bi bi-calendar2-check"></i> Absensi
                </a>
                @php
                    $user = auth()->user();
                    $jabatan = strtolower($user?->anggota?->jabatan ?? '');
                    $isPanitiaEditAuthorized = $kegiatan->panitia()
                        ->where('id_anggota', $user?->anggota_id)
                        ->whereIn('posisi', ['Ketua Pelaksana', 'Sekretaris'])
                        ->exists();
                    $canEdit = $user?->name === 'admin' 
                        || in_array($jabatan, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true) 
                        || $isPanitiaEditAuthorized;
                @endphp
                @if($canEdit)
                    <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn btn-warning px-4">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">Kembali</a>
            </div>
        </div>
    </div>
</x-sidebar>
