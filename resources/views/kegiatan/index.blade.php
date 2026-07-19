<x-sidebar title="Kegiatan">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Kegiatan</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ================= SEARCH ================= -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('kegiatan.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari kegiatan" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="terjadwal"   {{ request('status') == 'terjadwal'   ? 'selected' : '' }}>Terjadwal</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai"     {{ request('status') == 'selesai'     ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        @if(auth()->user()?->isKetua())
                            <button type="button" class="btn btn-primary px-4"
                                    data-bs-toggle="modal" data-bs-target="#formKegiatanModal"
                                    onclick="bukaTambahKegiatan()">
                                <i class="bi bi-plus-circle"></i> Tambah Kegiatan
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= LIST KEGIATAN ================= -->
    <div class="row g-4 align-items-stretch">
        @forelse($kegiatans as $kegiatan)
            @php
                $borderColorClass = match($kegiatan->status) {
                    'berlangsung' => 'bg-success',
                    'terjadwal'   => 'bg-warning',
                    default       => 'bg-secondary',
                };
                
                $badgeStyle = match($kegiatan->status) {
                    'berlangsung' => 'bg-success-subtle text-success border border-success-subtle',
                    'terjadwal'   => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                    default       => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                };
            @endphp
            <div class="col-lg-4 d-flex">
                <div class="card kegiatan-card shadow border-0 w-100 h-100 position-relative overflow-hidden" style="border-radius: 20px;">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge rounded-pill px-3 py-1.5 fs-7 {{ $badgeStyle }}">{{ ucfirst($kegiatan->status) }}</span>
                            <span class="text-primary fw-bold small"><i class="bi bi-wallet2 me-1"></i> Rp{{ number_format($kegiatan->transaksi->where('jenis_transaksi', 'pemasukan')->sum('nominal'), 0, ',', '.') }}</span>
                        </div>
                        
                        <h5 class="fw-bold mb-3 text-dark dark:text-white" style="line-height: 1.4;">{{ $kegiatan->nama_kegiatan }}</h5>
                        
                        @if($kegiatan->deskripsi)
                            <p class="text-muted small mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                                {{ $kegiatan->deskripsi }}
                            </p>
                        @else
                            <div class="flex-grow-1 mb-4"></div>
                        @endif

                        <div class="d-flex flex-column gap-2.5 mb-4 p-3 rounded-4" style="background-color: rgba(0, 0, 0, 0.025);">
                            <div class="d-flex align-items-center text-secondary small">
                                <i class="bi bi-calendar3 text-primary me-2"></i>
                                <span>{{ $kegiatan->tanggal_mulai?->format('d M Y, H:i') ?: '-' }} s/d {{ $kegiatan->tanggal_selesai?->format('d M Y, H:i') ?: '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center text-secondary small">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <span class="text-truncate">{{ $kegiatan->lokasi ?: '-' }}</span>
                            </div>
                        </div>

                        <!-- Mini Progres Bar -->
                        <div class="mb-4 pt-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-secondary fw-semibold">Progres</span>
                                <span class="small text-primary fw-bold">{{ $kegiatan->progres ?? 0 }}%</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 10px;">
                                <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $kegiatan->progres ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <button class="btn btn-primary btn-sm px-3 rounded-pill d-inline-flex align-items-center gap-1.5"
                                    data-bs-toggle="modal" data-bs-target="#detailKegiatanModal"
                                    onclick="bukaDetailKegiatan(this)"
                                    data-id="{{ $kegiatan->kode_kegiatan }}"
                                    data-nama="{{ $kegiatan->nama_kegiatan }}"
                                    data-tanggal-mulai="{{ $kegiatan->tanggal_mulai?->format('d M Y, H:i') }}"
                                    data-tanggal-selesai="{{ $kegiatan->tanggal_selesai?->format('d M Y, H:i') }}"
                                    data-tanggal-mulai-raw="{{ $kegiatan->tanggal_mulai?->format('Y-m-d\TH:i') }}"
                                    data-tanggal-selesai-raw="{{ $kegiatan->tanggal_selesai?->format('Y-m-d\TH:i') }}"
                                    data-lokasi="{{ $kegiatan->lokasi }}"
                                    data-deskripsi="{{ $kegiatan->deskripsi }}"
                                    data-status="{{ $kegiatan->status }}"
                                    data-progres="{{ $kegiatan->progres ?? 0 }}"
                                    data-dana="Rp{{ number_format($kegiatan->transaksi->where('jenis_transaksi', 'pemasukan')->sum('nominal'), 0, ',', '.') }}"
                                    data-panitia="{{ json_encode($kegiatan->panitia->map(fn($p) => ['nama' => $p->anggota?->nama ?? '-', 'posisi' => $p->posisi])) }}"
                                    data-persiapan="{{ json_encode($kegiatan->persiapan ?? [
                                        ['name' => 'Proposal', 'checked' => false],
                                        ['name' => 'Surat Permohonan', 'checked' => false],
                                        ['name' => 'Surat Peminjaman', 'checked' => false],
                                        ['name' => 'Lokasi', 'checked' => false],
                                        ['name' => 'Keuangan', 'checked' => false],
                                        ['name' => 'Alat-alat', 'checked' => false],
                                        ['name' => 'Konsumsi', 'checked' => false],
                                        ['name' => 'LPJ', 'checked' => false],
                                    ]) }}"
                                    @php
                                        $user = auth()->user();
                                        $jabatanVal = strtolower($user?->anggota?->jabatan ?? '');
                                        $isAllowedKepanitiaan = $user?->name === 'admin' || ($user?->role === 'pengurus' && $jabatanVal !== 'bendahara');
                                    @endphp
                                    data-is-pengurus="{{ $isAllowedKepanitiaan ? 'true' : 'false' }}"
                                    data-kelola-url="{{ route('kegiatan.kepanitiaan.index', $kegiatan->kode_kegiatan) }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            
                            @php
                                $user = auth()->user();
                                $jabatan = strtolower($user?->anggota?->jabatan ?? '');
                                $isPanitiaEditAuthorized = $kegiatan->panitia
                                    ->where('id_anggota', $user?->anggota_id)
                                    ->whereIn('posisi', ['Ketua Pelaksana', 'Sekretaris'])
                                    ->isNotEmpty();
                                
                                $canEdit = $user?->name === 'admin' 
                                    || in_array($jabatan, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true) 
                                    || $isPanitiaEditAuthorized;
                                    
                                $canDelete = $user?->name === 'admin' 
                                    || in_array($jabatan, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true);
                            @endphp
                            @if($canEdit || $canDelete)
                                <div class="d-flex gap-2">
                                    @if($canEdit)
                                        <a href="{{ route('kegiatan.edit', $kegiatan->kode_kegiatan) }}" 
                                           class="btn btn-outline-warning rounded-circle d-flex align-items-center justify-content-center"
                                           style="width: 34px; height: 34px; padding: 0;">
                                            <i class="bi bi-pencil" style="font-size: 14px; line-height: 0; margin: 0; padding: 0; vertical-align: middle;"></i>
                                        </a>
                                    @endif
                                    @if($canDelete)
                                        <button class="btn btn-outline-danger rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 34px; height: 34px; padding: 0;"
                                                data-bs-toggle="modal" data-bs-target="#hapusKegiatanModal"
                                                onclick="bukaHapusKegiatan(this)"
                                                data-id="{{ $kegiatan->kode_kegiatan }}"
                                                data-nama="{{ $kegiatan->nama_kegiatan }}">
                                            <i class="bi bi-trash" style="font-size: 14px; line-height: 0; margin: 0; padding: 0; vertical-align: middle;"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif
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

    <!-- ================= MODAL TAMBAH / EDIT KEGIATAN ================= -->
    <div class="modal fade" id="formKegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form id="formKegiatan" method="POST">
                    @csrf
                    <span id="methodField"></span>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="formKegiatanTitle">Tambah Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control"
                                       id="inputNamaKegiatan" placeholder="Masukkan nama kegiatan" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_mulai" class="form-control"
                                       id="inputTanggalMulaiKegiatan" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_selesai" class="form-control"
                                       id="inputTanggalSelesaiKegiatan" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control"
                                       id="inputLokasiKegiatan" placeholder="Masukkan lokasi">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" id="inputStatusKegiatan" required>
                                    <option value="terjadwal">Terjadwal</option>
                                    <option value="berlangsung">Berlangsung</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control textarea-fixed" rows="3"
                                          id="inputDeskripsiKegiatan"
                                          placeholder="Masukkan deskripsi kegiatan"></textarea>
                            </div>

                            <input type="hidden" name="progres" value="0">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL KEGIATAN ================= -->
    <div class="modal fade" id="detailKegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Detail Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pb-0">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0" id="detailNamaKegiatan">-</h4>
                        <span class="badge bg-success fs-6" id="detailStatusKegiatan">-</span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-calendar3"></i> Tanggal Mulai</div>
                            <div class="detail-value" id="detailTanggalMulaiKegiatan">-</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-calendar3"></i> Tanggal Selesai</div>
                            <div class="detail-value" id="detailTanggalSelesaiKegiatan">-</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-geo-alt"></i> Lokasi</div>
                            <div class="detail-value" id="detailLokasiKegiatan">-</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-folder"></i> Dana Terkumpul</div>
                            <div class="detail-value" id="detailDanaKegiatan">Rp0</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-bar-chart"></i> Progres</div>
                            <div class="progress" style="height:22px; border-radius: 6px;">
                                <div class="progress-bar" id="detailProgresBar"
                                     role="progressbar" style="width:0%">0%</div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value" id="detailDeskripsiKegiatan">-</div>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <div class="detail-label">Checklist Persiapan</div>
                            <div class="row g-2 p-3 border rounded-3 bg-light bg-opacity-50 mt-1" id="detailChecklistList">
                                <!-- Will be populated dynamically -->
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Kepanitiaan</h5>
                        <a id="btnKelolaKepanitiaan" href="#" class="btn btn-outline-primary btn-sm px-3 rounded-pill" style="display: none;">
                            <i class="bi bi-people"></i> Kelola Kepanitiaan
                        </a>
                    </div>
                    
                    <div class="list-group list-group-flush mb-3" id="detailPanitiaList">
                        <!-- Kepanitiaan list will be populated dynamically -->
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-between">
                    <div class="d-flex gap-2">
                        <a id="btnDetailDokumen" href="#" class="btn btn-outline-primary px-3">
                            <i class="bi bi-folder2-open"></i> Dokumen
                        </a>
                        <a id="btnDetailAbsensi" href="#" class="btn btn-outline-success px-3">
                            <i class="bi bi-calendar2-check"></i> Absensi
                        </a>
                    </div>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL HAPUS KEGIATAN ================= -->
    <div class="modal fade" id="hapusKegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:48px;"></i>
                    </div>
                    <h5 class="fw-bold">Hapus Kegiatan?</h5>
                    <p class="text-muted mb-0">
                        Apakah kamu yakin ingin menghapus kegiatan
                        <strong id="hapusNamaKegiatan">-</strong>?
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="formHapusKegiatan" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tMulai = document.getElementById('inputTanggalMulaiKegiatan');
            const tSelesai = document.getElementById('inputTanggalSelesaiKegiatan');
            const statusSelect = document.getElementById('inputStatusKegiatan');

            function autoUpdateStatus() {
                if (!tMulai.value || !tSelesai.value) return;

                const now = new Date();
                const start = new Date(tMulai.value);
                const end = new Date(tSelesai.value);

                if (now < start) {
                    statusSelect.value = 'terjadwal';
                } else if (now >= start && now <= end) {
                    statusSelect.value = 'berlangsung';
                } else if (now > end) {
                    statusSelect.value = 'selesai';
                }
            }

            if (tMulai && tSelesai && statusSelect) {
                tMulai.addEventListener('change', autoUpdateStatus);
                tSelesai.addEventListener('change', autoUpdateStatus);
            }
        });

        /* ====== CHECKLIST PROGRES ====== */
        function hitungProgres() {
            const items    = document.querySelectorAll('.checklist-item');
            const total    = items.length;
            const dicentang = document.querySelectorAll('.checklist-item:checked').length;
            const persen   = total === 0 ? 0 : Math.round((dicentang / total) * 100);
            document.getElementById('progresValue').innerText        = persen + '%';
            document.getElementById('progresBarForm').style.width    = persen + '%';
            document.getElementById('inputProgres').value            = persen;
            return persen;
        }
        document.querySelectorAll('.checklist-item').forEach(function (item) {
            item.addEventListener('change', hitungProgres);
        });

        /* ====== TAMBAH ====== */
        function bukaTambahKegiatan() {
            document.getElementById('formKegiatanTitle').innerText = 'Tambah Kegiatan';
            document.getElementById('formKegiatan').action = '{{ route('kegiatan.store') }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('formKegiatan').reset();
            document.querySelectorAll('.checklist-item').forEach(i => i.checked = false);
            hitungProgres();
        }

        /* ====== EDIT ====== */
        function bukaEditKegiatan(btn) {
            document.getElementById('formKegiatanTitle').innerText = 'Edit Kegiatan';
            const id = btn.dataset.id;
            document.getElementById('formKegiatan').action = `/kegiatan/${id}`;
            document.getElementById('methodField').innerHTML =
                '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('inputNamaKegiatan').value     = btn.dataset.nama;
            document.getElementById('inputTanggalMulaiKegiatan').value   = btn.dataset.tanggalMulaiRaw;
            document.getElementById('inputTanggalSelesaiKegiatan').value = btn.dataset.tanggalSelesaiRaw;
            document.getElementById('inputLokasiKegiatan').value   = btn.dataset.lokasi;
            document.getElementById('inputDeskripsiKegiatan').value= btn.dataset.deskripsi;
            document.getElementById('inputStatusKegiatan').value   = btn.dataset.status;

            const persen = parseInt(btn.dataset.progres) || 0;
            document.getElementById('inputProgres').value            = persen;
            document.getElementById('progresValue').innerText        = persen + '%';
            document.getElementById('progresBarForm').style.width    = persen + '%';
        }

        /* ====== DETAIL ====== */
        function bukaDetailKegiatan(btn) {
            const id = btn.dataset.id;
            document.getElementById('detailNamaKegiatan').innerText    = btn.dataset.nama;
            document.getElementById('detailStatusKegiatan').innerText  = btn.dataset.status
                ? btn.dataset.status.charAt(0).toUpperCase() + btn.dataset.status.slice(1)
                : '-';
            document.getElementById('detailTanggalMulaiKegiatan').innerText = btn.dataset.tanggalMulai || '-';
            document.getElementById('detailTanggalSelesaiKegiatan').innerText = btn.dataset.tanggalSelesai || '-';
            document.getElementById('detailLokasiKegiatan').innerText  = btn.dataset.lokasi  || '-';
            document.getElementById('detailDanaKegiatan').innerText    = btn.dataset.dana || 'Rp0';
            document.getElementById('detailDeskripsiKegiatan').innerText = btn.dataset.deskripsi || '-';

            const progres = parseInt(btn.dataset.progres) || 0;
            const bar = document.getElementById('detailProgresBar');
            bar.style.width  = progres + '%';
            bar.innerText    = progres + '%';

            document.getElementById('btnDetailDokumen').href = `/dokumen/${id}/folder`;
            document.getElementById('btnDetailAbsensi').href = `/absensi?search=${encodeURIComponent(btn.dataset.nama)}`;

            // Kepanitiaan Kelola URL & Visibility
            const isPengurus = btn.dataset.isPengurus === 'true';
            const btnKelola = document.getElementById('btnKelolaKepanitiaan');
            if (isPengurus) {
                btnKelola.style.display = 'inline-block';
                btnKelola.href = btn.dataset.kelolaUrl;
            } else {
                btnKelola.style.display = 'none';
            }

            // Populate Panitia list
            const panitiaList = document.getElementById('detailPanitiaList');
            panitiaList.innerHTML = '';
            
            try {
                const panitia = JSON.parse(btn.dataset.panitia || '[]');
                if (panitia.length === 0) {
                    panitiaList.innerHTML = '<div class="text-muted small py-2 px-3">Belum ada panitia yang terdaftar.</div>';
                } else {
                    panitia.forEach(function(p) {
                        const item = document.createElement('div');
                        item.className = 'd-flex justify-content-between align-items-center py-2 px-3 border-bottom';
                        item.innerHTML = `
                            <span class="fw-medium">${p.nama}</span>
                            <span class="text-secondary small">${p.posisi}</span>
                        `;
                        panitiaList.appendChild(item);
                    });
                }
            } catch (e) {
                panitiaList.innerHTML = '<div class="text-danger small py-2 px-3">Gagal memuat kepanitiaan.</div>';
            }

            // Populate Checklist list
            const checklistList = document.getElementById('detailChecklistList');
            checklistList.innerHTML = '';
            
            try {
                const persiapan = JSON.parse(btn.dataset.persiapan || '[]');
                if (persiapan.length === 0) {
                    checklistList.innerHTML = '<div class="col-12 text-center text-muted small py-2">Tidak ada item persiapan.</div>';
                } else {
                    persiapan.forEach(function(item) {
                        const col = document.createElement('div');
                        col.className = 'col-md-6';
                        
                        const inner = document.createElement('div');
                        inner.className = 'd-flex align-items-center gap-2 py-1';
                        
                        if (item.checked) {
                            inner.innerHTML = `
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 16px;"></i>
                                <span class="text-decoration-line-through text-muted small">${item.name}</span>
                            `;
                        } else {
                            inner.innerHTML = `
                                <i class="bi bi-circle text-muted" style="font-size: 16px;"></i>
                                <span class="small fw-semibold text-dark">${item.name}</span>
                            `;
                        }
                        
                        col.appendChild(inner);
                        checklistList.appendChild(col);
                    });
                }
            } catch (e) {
                checklistList.innerHTML = '<div class="col-12 text-center text-danger small py-2">Gagal memuat checklist.</div>';
            }
        }

        /* ====== HAPUS ====== */
        function bukaHapusKegiatan(btn) {
            document.getElementById('hapusNamaKegiatan').innerText = btn.dataset.nama;
            document.getElementById('formHapusKegiatan').action   = `/kegiatan/${btn.dataset.id}`;
        }
    </script>

</x-sidebar>
