@php
    $currentUserJabatan = strtolower(auth()->user()->anggota?->jabatan ?? '');
    $canManage = in_array($currentUserJabatan, ['ketua', 'wakil ketua', 'sekretaris'], true) || auth()->user()->name === 'admin';
@endphp
<x-sidebar title="Anggota">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Anggota</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ================= SEARCH ================= -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('members.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Cari anggota"
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="jabatan" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Jabatan</option>
                            <option value="Ketua"         {{ request('jabatan') == 'Ketua'         ? 'selected' : '' }}>Ketua</option>
                            <option value="Wakil Ketua"   {{ request('jabatan') == 'Wakil Ketua'   ? 'selected' : '' }}>Wakil Ketua</option>
                            <option value="Sekretaris"    {{ request('jabatan') == 'Sekretaris'    ? 'selected' : '' }}>Sekretaris</option>
                            <option value="Bendahara"     {{ request('jabatan') == 'Bendahara'     ? 'selected' : '' }}>Bendahara</option>
                            <option value="Kepala Divisi" {{ request('jabatan') == 'Kepala Divisi' ? 'selected' : '' }}>Kepala Divisi</option>
                            <option value="Anggota"       {{ request('jabatan') == 'Anggota'       ? 'selected' : '' }}>Anggota</option>
                        </select>
                    </div>
                    <div class="col-lg-auto">
                        <select name="divisi_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Divisi</option>
                            @foreach($divisis as $divisi)
                                <option value="{{ $divisi->id_divisi }}" {{ request('divisi_id') == $divisi->id_divisi ? 'selected' : '' }}>
                                    {{ $divisi->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-auto">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="aktif"        {{ request('status') == 'aktif'       ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak aktif"  {{ request('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary px-3">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        @if($canManage)
                            <a href="{{ route('divisi.index') }}" class="btn btn-outline-primary px-3">
                                <i class="bi bi-folder"></i> Kelola Divisi
                            </a>
                            <a href="{{ route('members.create') }}" class="btn btn-primary px-4">
                                <i class="bi bi-plus-circle"></i> Tambah Anggota
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Indikator filter aktif --}}
    @if(request('search') || request('jabatan') || request('divisi_id') || request('status'))
        <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
            <small class="text-muted">Filter aktif:</small>
            @if(request('search'))
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Cari: "{{ request('search') }}"
                </span>
            @endif
            @if(request('jabatan'))
                <span class="badge bg-info-subtle text-info border border-info-subtle">
                    Jabatan: {{ request('jabatan') }}
                </span>
            @endif
            @if(request('divisi_id'))
                @php
                    $filteredDivisi = $divisis->firstWhere('id_divisi', request('divisi_id'));
                @endphp
                <span class="badge bg-info-subtle text-info border border-info-subtle">
                    Divisi: {{ $filteredDivisi?->nama_divisi ?? 'Tanpa Divisi' }}
                </span>
            @endif
            @if(request('status'))
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    Status: {{ ucfirst(request('status')) }}
                </span>
            @endif
            <a href="{{ route('members.index') }}" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none">
                <i class="bi bi-x"></i> Reset
            </a>
        </div>
    @endif

    <!-- ================= TABEL ================= -->
    <div class="card border-0 shadow rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
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
                                    <img src="{{ asset('storage/' . $anggota->foto) }}"
                                         alt="{{ $anggota->nama }}" class="foto-anggota">
                                @else
                                    <div class="foto-anggota bg-secondary d-flex align-items-center justify-content-center mx-auto">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $anggota->nama }}</td>
                            <td>{{ $anggota->jabatan ?: 'Anggota' }}</td>
                            <td>{{ $anggota->divisi?->nama_divisi ?? '-' }}</td>
                            <td>{{ $anggota->no_hp ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $anggota->status_anggota === 'aktif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ ucfirst($anggota->status_anggota) }}
                                </span>
                            </td>
                            <td>{{ $anggota->tanggal_bergabung
                                    ? \Carbon\Carbon::parse($anggota->tanggal_bergabung)->format('d M Y')
                                    : '-' }}</td>
                            <td>
                                <!-- Tombol Detail (modal) -->
                                <button class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#detailModal"
                                    onclick="bukaDetail(this)"
                                    data-nik="{{ $anggota->nik }}"
                                    data-nama="{{ $anggota->nama }}"
                                    data-jabatan="{{ $anggota->jabatan }}"
                                    data-hp="{{ $anggota->no_hp }}"
                                    data-status="{{ $anggota->status_anggota }}"
                                    data-tanggal="{{ $anggota->tanggal_bergabung ? \Carbon\Carbon::parse($anggota->tanggal_bergabung)->format('d M Y') : '-' }}"
                                    data-alamat="{{ $anggota->alamat }}"
                                    data-foto="{{ $anggota->foto ? asset('storage/'.$anggota->foto) : '' }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($canManage)
                                    <!-- Edit -->
                                    <a href="{{ route('members.edit', $anggota) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                     <!-- Hapus -->
                                    @if(auth()->user()->anggota_id !== $anggota->id_anggota)
                                        <button class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#hapusModal"
                                            onclick="bukaHapus(this)"
                                            data-id="{{ $anggota->id_anggota }}"
                                            data-nama="{{ $anggota->nama }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                Tidak ada anggota.
                            </td>
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

    <!-- ================= MODAL DETAIL ANGGOTA ================= -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Detail Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Foto -->
                        <div class="col-lg-3">
                            <div class="foto-upload-box">
                                <img id="detailFoto" src="https://via.placeholder.com/150" alt="Foto">
                            </div>
                        </div>
                        <!-- Data -->
                        <div class="col-lg-9">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="detail-label">Nama Lengkap</div>
                                    <div class="detail-value" id="detailNama">-</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="detail-label">NIK</div>
                                    <div class="detail-value" id="detailNik">-</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="detail-label">Divisi</div>
                                    <div class="detail-value" id="detailJabatan">-</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="detail-label">Status</div>
                                    <div class="detail-value">
                                        <span class="badge bg-success-subtle text-success" id="detailStatus">-</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="detail-label">No. HP</div>
                                    <div class="detail-value" id="detailHp">-</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="detail-label">Tanggal Bergabung</div>
                                    <div class="detail-value" id="detailTanggal">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-lg-12">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value" id="detailAlamat">-</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL KONFIRMASI HAPUS ================= -->
    <div class="modal fade" id="hapusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:48px;"></i>
                    </div>
                    <h5 class="fw-bold">Hapus Data Anggota?</h5>
                    <p class="text-muted mb-0">
                        Apakah kamu yakin ingin menghapus data <strong id="hapusNama">-</strong>?
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="formHapus" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaDetail(btn) {
            document.getElementById('detailNama').innerText    = btn.dataset.nama    || '-';
            document.getElementById('detailNik').innerText     = btn.dataset.nik     || '-';
            document.getElementById('detailJabatan').innerText = btn.dataset.jabatan || '-';
            document.getElementById('detailStatus').innerText  = btn.dataset.status  || '-';
            document.getElementById('detailHp').innerText      = btn.dataset.hp      || '-';
            document.getElementById('detailTanggal').innerText = btn.dataset.tanggal || '-';
            document.getElementById('detailAlamat').innerText  = btn.dataset.alamat  || '-';

            const foto = btn.dataset.foto;
            document.getElementById('detailFoto').src = foto
                ? foto
                : 'https://via.placeholder.com/150';
        }

        let urlHapus = '';
        function bukaHapus(btn) {
            document.getElementById('hapusNama').innerText = btn.dataset.nama;
            const id = btn.dataset.id;
            document.getElementById('formHapus').action = `/members/${id}`;
        }
    </script>

</x-sidebar>
