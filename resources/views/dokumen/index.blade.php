<x-sidebar title="Dokumen">
    <h2 class="mb-4 fw-bold">Dokumen</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('dokumen.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari dokumen" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="kegiatan_id" class="form-select">
                            <option value="">Semua Kegiatan</option>
                            @foreach($kegiatans as $k)
                                <option value="{{ $k->kode_kegiatan }}" {{ request('kegiatan_id') == $k->kode_kegiatan ? 'selected' : '' }}>
                                    {{ $k->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        <a href="{{ route('dokumen.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle"></i> Tambah Dokumen
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($dokumens as $dok)
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon blue" style="width:50px;height:50px;font-size:22px;border-radius:14px;flex-shrink:0;">
                                <i class="bi bi-folder-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $dok->nama_folder }}</h6>
                                <small class="text-muted">{{ $dok->kegiatan?->nama_kegiatan ?? 'Umum' }}</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $dok->tanggal_dibuat?->format('d M Y') ?? '-' }}
                        </p>
                        <div class="d-flex gap-2">
                            @if($dok->gdrive_folder)
                                <a href="{{ $dok->gdrive_folder }}" target="_blank" class="btn btn-outline-primary btn-sm flex-grow-1">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka
                                </a>
                            @endif
                            <a href="{{ route('dokumen.edit', $dok) }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('dokumen.destroy', $dok) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus dokumen ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-folder fs-1 d-block mb-2"></i>
                Belum ada dokumen.
            </div>
        @endforelse
    </div>

    @if($dokumens->hasPages())
        <div class="mt-4 d-flex justify-content-end">{{ $dokumens->links() }}</div>
    @endif
</x-sidebar>
