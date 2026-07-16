<x-sidebar title="Keuangan">
    <h2 class="mb-4 fw-bold">Keuangan</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="icon green"><i class="bi bi-arrow-down-circle"></i></div>
                <h5>Total Pemasukan</h5>
                <h2>Rp{{ number_format($pemasukan, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="icon" style="background:#c62828;"><i class="bi bi-arrow-up-circle"></i></div>
                <h5>Total Pengeluaran</h5>
                <h2>Rp{{ number_format($pengeluaran, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="icon orange"><i class="bi bi-wallet2"></i></div>
                <h5>Saldo Kas</h5>
                <h2>Rp{{ number_format($saldo, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('keuangan.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari keterangan" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="jenis" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan"   {{ request('jenis') == 'pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        <a href="{{ route('keuangan.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle"></i> Catat Transaksi
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
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th>Kegiatan</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                        <tr>
                            <td>{{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}</td>
                            <td>{{ $t->tanggal?->format('d M Y') }}</td>
                            <td class="text-start">{{ $t->keterangan ?: '-' }}</td>
                            <td>{{ $t->kategori ?: '-' }}</td>
                            <td class="text-start">{{ $t->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $t->jenis_transaksi == 'pemasukan' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ ucfirst($t->jenis_transaksi) }}
                                </span>
                            </td>
                            <td class="fw-semibold {{ $t->jenis_transaksi == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis_transaksi == 'pemasukan' ? '+' : '-' }}Rp{{ number_format($t->nominal, 0, ',', '.') }}
                            </td>
                            <td>
                                <a href="{{ route('keuangan.edit', $t) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('keuangan.destroy', $t) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus transaksi ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-5 text-muted">
                                <i class="bi bi-wallet2 fs-1 d-block mb-2"></i>
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksis->hasPages())
            <div class="card-footer bg-white">
                <nav><ul class="pagination justify-content-end mb-0">
                    <li class="page-item {{ $transaksis->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $transaksis->previousPageUrl() }}">&laquo;</a>
                    </li>
                    @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $transaksis->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ !$transaksis->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $transaksis->nextPageUrl() }}">&raquo;</a>
                    </li>
                </ul></nav>
            </div>
        @endif
    </div>
</x-sidebar>
