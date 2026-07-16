<x-sidebar title="Kegiatan">
    <h2 class="mb-4 fw-bold">Kegiatan</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('kegiatan.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari kegiatan" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="terjadwal"  {{ request('status') == 'terjadwal'  ? 'selected' : '' }}>Terjadwal</option>
                            <option value="berlangsung"{{ request('status') == 'berlangsung'? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai"    {{ request('status') == 'selesai'    ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle"></i> Tambah Kegiatan
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($kegiatans as $kegiatan)
            @php
                $badgeClass = match($kegiatan->status) {
                    'berlangsung' => 'bg-success',
                    'terjadwal'   => 'bg-warning text-dark',
                    default       => 'bg-secondary',
                };
            @endphp
            <div class="col-lg-4">
                <div class="card kegiatan-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold">{{ $kegiatan->nama_kegiatan }}</h5>
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($kegiatan->status) }}</span>
                            </div>
                        </div>
                        <hr>
                        <p><i class="bi bi-calendar3"></i> {{ $kegiatan->tanggal?->format('d M Y') ?: '-' }}</p>
                        <p><i class="bi bi-geo-alt"></i> {{ $kegiatan->lokasi ?: '-' }}</p>
                        @if($kegiatan->deskripsi)
                            <p class="text-muted small">{{ Str::limit($kegiatan->deskripsi, 80) }}</p>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <div class="d-flex gap-1">
                                <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('kegiatan.destroy', $kegiatan) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kegiatan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-calendar-event fs-1 d-block mb-2"></i>
                Belum ada kegiatan.
            </div>
        @endforelse
    </div>

    @if($kegiatans->hasPages())
        <div class="mt-4 d-flex justify-content-end">{{ $kegiatans->links() }}</div>
    @endif
</x-sidebar>
