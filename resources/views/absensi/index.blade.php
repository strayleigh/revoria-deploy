<x-sidebar title="Absensi">
    <h2 class="mb-4 fw-bold">Absensi</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('absensi.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama anggota" value="{{ request('search') }}">
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
                    <div class="col-lg-auto">
                        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" style="border-radius:12px;">
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        <a href="{{ route('absensi.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle"></i> Input Absensi
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absensi)
                        @php
                            $badgeClass = match($absensi->status_hadir) {
                                'hadir'       => 'bg-success-subtle text-success',
                                'izin'        => 'bg-warning-subtle text-warning',
                                'sakit'       => 'bg-info-subtle text-info',
                                default       => 'bg-danger-subtle text-danger',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($absensis->currentPage() - 1) * $absensis->perPage() }}</td>
                            <td class="text-start">{{ $absensi->anggota?->nama ?? '-' }}</td>
                            <td class="text-start">{{ $absensi->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>{{ $absensi->tanggal_absensi?->format('d M Y') }}</td>
                            <td>{{ $absensi->waktu_absen ?? '-' }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ ucfirst($absensi->status_hadir) }}</span></td>
                            <td>
                                <a href="{{ route('absensi.edit', $absensi) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('absensi.destroy', $absensi) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-5 text-muted">
                                <i class="bi bi-check2-square fs-1 d-block mb-2"></i>
                                Belum ada data absensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($absensis->hasPages())
            <div class="card-footer bg-white">
                <nav><ul class="pagination justify-content-end mb-0">
                    <li class="page-item {{ $absensis->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $absensis->previousPageUrl() }}">&laquo;</a>
                    </li>
                    @foreach($absensis->getUrlRange(1, $absensis->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $absensis->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ !$absensis->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $absensis->nextPageUrl() }}">&raquo;</a>
                    </li>
                </ul></nav>
            </div>
        @endif
    </div>
</x-sidebar>
