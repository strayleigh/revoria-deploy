@php
    $user = auth()->user();
    $jabatan = strtolower($user?->anggota?->jabatan ?? '');
    $isBendaharaOrAdmin = $user?->name === 'admin' || $jabatan === 'bendahara';
@endphp
<x-sidebar title="Keuangan">
    <h2 class="mb-4 fw-bold">Keuangan</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-4 col-lg-4">
            <a href="{{ route('keuangan.index', array_merge(request()->except(['page']), ['jenis' => ''])) }}" class="text-decoration-none text-dark d-block">
                <div class="summary-card h-100 {{ !request('jenis') ? 'border border-warning border-3 shadow-sm bg-warning-subtle bg-opacity-10' : '' }} text-center">
                    <div class="icon-clean icon-orange mb-3 mx-auto">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Total Keseluruhan</span>
                    <h3 class="fw-bold mb-2">Rp{{ number_format($saldo, 0, ',', '.') }}</h3>
                    <small class="text-muted" style="font-size: 11px;">Klik untuk melihat semua</small>
                </div>
            </a>
        </div>
        <div class="col-4 col-lg-4">
            <a href="{{ route('keuangan.index', array_merge(request()->except(['page']), ['jenis' => 'pemasukan'])) }}" class="text-decoration-none text-dark d-block">
                <div class="summary-card h-100 {{ request('jenis') == 'pemasukan' ? 'border border-success border-3 shadow-sm bg-success-subtle bg-opacity-10' : '' }} text-center">
                    <div class="icon-clean icon-green mb-3 mx-auto">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Total Pemasukan</span>
                    <h3 class="fw-bold text-success mb-2">Rp{{ number_format($pemasukan, 0, ',', '.') }}</h3>
                    <small class="text-muted" style="font-size: 11px;">Klik untuk memfilter pemasukan</small>
                </div>
            </a>
        </div>
        <div class="col-4 col-lg-4">
            <a href="{{ route('keuangan.index', array_merge(request()->except(['page']), ['jenis' => 'pengeluaran'])) }}" class="text-decoration-none text-dark d-block">
                <div class="summary-card h-100 {{ request('jenis') == 'pengeluaran' ? 'border border-danger border-3 shadow-sm bg-danger-subtle bg-opacity-10' : '' }} text-center">
                    <div class="icon-clean icon-red mb-3 mx-auto">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Total Pengeluaran</span>
                    <h3 class="fw-bold text-danger mb-2">Rp{{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                    <small class="text-muted" style="font-size: 11px;">Klik untuk memfilter pengeluaran</small>
                </div>
            </a>
        </div>
    </div>

    <!-- Ringkasan Kategori Keuangan -->
    <div class="row g-4 mb-4">
        <!-- Kas Card -->
        <div class="col-12 col-md-4 col-lg-4">
            @php
                $isKasActive = request('kategori') == 'Kas';
                $kasLink = route('keuangan.index', array_merge(request()->except(['page']), ['kategori' => $isKasActive ? '' : 'Kas']));
            @endphp
            <a href="{{ $kasLink }}" class="text-decoration-none text-dark d-block">
                <div class="card h-100 {{ $isKasActive ? 'border border-primary border-3 shadow bg-primary-subtle bg-opacity-10' : 'border-0 shadow-sm' }} rounded-4 p-3 bg-white dark:bg-slate-800" style="transition: all 0.25s;">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-cash-stack me-2"></i>Kategori KAS</h6>
                    <hr class="my-2 card-divider">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Saldo Kas:</span>
                        <span class="fw-semibold text-dark dark:text-white">Rp{{ number_format($kasTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Pemasukan:</span>
                        <span class="fw-semibold text-success">Rp{{ number_format($kasPemasukan, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Pengeluaran:</span>
                        <span class="fw-semibold text-danger">Rp{{ number_format($kasPengeluaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Iuran Card -->
        <div class="col-12 col-md-4 col-lg-4">
            @php
                $isIuranActive = request('kategori') == 'Iuran';
                $iuranLink = route('keuangan.index', array_merge(request()->except(['page']), ['kategori' => $isIuranActive ? '' : 'Iuran']));
            @endphp
            <a href="{{ $iuranLink }}" class="text-decoration-none text-dark d-block">
                <div class="card h-100 {{ $isIuranActive ? 'border border-success border-3 shadow bg-success-subtle bg-opacity-10' : 'border-0 shadow-sm' }} rounded-4 p-3 bg-white dark:bg-slate-800" style="transition: all 0.25s;">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-wallet2 me-2"></i>Kategori IURAN</h6>
                    <hr class="my-2 card-divider">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Saldo Iuran:</span>
                        <span class="fw-semibold text-dark dark:text-white">Rp{{ number_format($iuranTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Pemasukan:</span>
                        <span class="fw-semibold text-success">Rp{{ number_format($iuranPemasukan, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Pengeluaran:</span>
                        <span class="fw-semibold text-danger">Rp{{ number_format($iuranPengeluaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Donasi Card -->
        <div class="col-12 col-md-4 col-lg-4">
            @php
                $isDonasiActive = request('kategori') == 'Donasi';
                $donasiLink = route('keuangan.index', array_merge(request()->except(['page']), ['kategori' => $isDonasiActive ? '' : 'Donasi']));
            @endphp
            <a href="{{ $donasiLink }}" class="text-decoration-none text-dark d-block">
                <div class="card h-100 {{ $isDonasiActive ? 'border border-warning border-3 shadow bg-warning-subtle bg-opacity-10' : 'border-0 shadow-sm' }} rounded-4 p-3 bg-white dark:bg-slate-800" style="transition: all 0.25s;">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-heart me-2"></i>Kategori DONASI</h6>
                    <hr class="my-2 card-divider">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Saldo Donasi:</span>
                        <span class="fw-semibold text-dark dark:text-white">Rp{{ number_format($donasiTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Pemasukan:</span>
                        <span class="fw-semibold text-success">Rp{{ number_format($donasiPemasukan, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Pengeluaran:</span>
                        <span class="fw-semibold text-danger">Rp{{ number_format($donasiPengeluaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
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
                        <select name="kategori" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            <option value="Kas"   {{ request('kategori') == 'Kas'   ? 'selected' : '' }}>Kas</option>
                            <option value="Iuran" {{ request('kategori') == 'Iuran' ? 'selected' : '' }}>Iuran</option>
                            <option value="Donasi" {{ request('kategori') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                        </select>
                    </div>
                    <div class="col-lg-auto">
                        <select name="jenis" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan"   {{ request('jenis') == 'pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        @if($isBendaharaOrAdmin)
                        <a href="{{ route('keuangan.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle"></i> Catat Transaksi
                        </a>
                        @endif
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
                        @if($isBendaharaOrAdmin)
                        <th>Aksi</th>
                        @endif
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
                            @if($isBendaharaOrAdmin)
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
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isBendaharaOrAdmin ? 8 : 7 }}" class="py-5 text-muted">
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
