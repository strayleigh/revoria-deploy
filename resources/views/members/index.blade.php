<x-sidebar title="Anggota">

    <h2 class="mb-4 fw-bold">Anggota</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SEARCH -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('members.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari anggota" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="jabatan" class="form-select">
                            <option value="">Semua Divisi</option>
                            <option value="Ketua"       {{ request('jabatan') == 'Ketua'       ? 'selected' : '' }}>Ketua</option>
                            <option value="Wakil Ketua" {{ request('jabatan') == 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua</option>
                            <option value="Sekretaris"  {{ request('jabatan') == 'Sekretaris'  ? 'selected' : '' }}>Sekretaris</option>
                            <option value="Bendahara"   {{ request('jabatan') == 'Bendahara'   ? 'selected' : '' }}>Bendahara</option>
                            <option value="Anggota"     {{ request('jabatan') == 'Anggota'     ? 'selected' : '' }}>Anggota</option>
                        </select>
                    </div>
                    <div class="col-lg-auto">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif"      {{ request('status') == 'aktif'      ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak aktif" {{ request('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary px-3">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        <a href="{{ route('members.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle"></i> Tambah Anggota
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card border-0 shadow rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>No. Hp</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggotas as $anggota)
                        <tr>
                            <td>{{ $loop->iteration + ($anggotas->currentPage() - 1) * $anggotas->perPage() }}</td>
                            <td>
                                @if($anggota->foto)
                                    <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama }}" class="foto-anggota">
                                @else
                                    <div class="foto-anggota bg-secondary d-flex align-items-center justify-content-center mx-auto">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $anggota->nama }}</td>
                            <td>{{ $anggota->jabatan ?: '-' }}</td>
                            <td>{{ $anggota->no_hp ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $anggota->status_anggota === 'aktif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ ucfirst($anggota->status_anggota) }}
                                </span>
                            </td>
                            <td>{{ $anggota->tanggal_bergabung ? \Carbon\Carbon::parse($anggota->tanggal_bergabung)->format('d M Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('members.show', $anggota) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $anggota) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('members.destroy', $anggota) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus anggota ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-muted">Tidak ada anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item {{ $anggotas->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $anggotas->previousPageUrl() }}">&laquo;</a>
                    </li>
                    @foreach($anggotas->getUrlRange(1, $anggotas->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $anggotas->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ !$anggotas->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $anggotas->nextPageUrl() }}">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</x-sidebar>
