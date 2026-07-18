<x-sidebar title="Kelola Kepanitiaan">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Kelola Kepanitiaan</h2>
            <p class="text-muted small mb-0">{{ $kegiatan->nama_kegiatan }} • {{ $kegiatan->tanggal?->format('d M Y') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Tambah Anggota Panitia -->
        <div class="col-lg-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person-plus text-primary me-2"></i> Tambah Panitia</h5>
                    <form action="{{ route('kegiatan.kepanitiaan.store', $kegiatan->kode_kegiatan) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Anggota <span class="text-danger">*</span></label>
                            <select name="id_anggota" class="form-select @error('id_anggota') is-invalid @enderror" required>
                                <option value="">Pilih Anggota</option>
                                @foreach($anggotas as $anggota)
                                    <option value="{{ $anggota->id_anggota }}" {{ old('id_anggota') == $anggota->id_anggota ? 'selected' : '' }}>
                                        {{ $anggota->nama }} ({{ $anggota->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_anggota')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Jabatan Kepanitiaan <span class="text-danger">*</span></label>
                            <select name="posisi" class="form-select @error('posisi') is-invalid @enderror" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="Ketua Pelaksana" {{ old('posisi') == 'Ketua Pelaksana' ? 'selected' : '' }}>Ketua Pelaksana</option>
                                <option value="Sekretaris" {{ old('posisi') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                <option value="Bendahara" {{ old('posisi') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="Anggota" {{ old('posisi', 'Anggota') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                            </select>
                            @error('posisi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i> Tambahkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Panitia Kegiatan -->
        <div class="col-lg-7">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-people text-primary me-2"></i> Struktur Kepanitiaan</h5>
                    
                    @if($kepanitiaans->isEmpty())
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/solid/team-meeting.svg" alt="No Panitia" style="width: 180px; max-width: 100%; opacity: 0.8;" class="mb-3">
                            <p class="text-muted mb-0">Belum ada panitia yang ditambahkan untuk kegiatan ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr class="text-secondary small uppercase">
                                        <th class="py-3 px-4 border-0">Nama Anggota</th>
                                        <th class="py-3 px-4 border-0">Jabatan Kepanitiaan</th>
                                        <th class="py-3 px-4 border-0 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kepanitiaans as $kepanitiaan)
                                        <tr>
                                            <td class="py-3 px-4 border-bottom">
                                                <div class="fw-semibold">{{ $kepanitiaan->anggota?->nama ?? 'Nama tidak ditemukan' }}</div>
                                                <small class="text-muted">Status: {{ ucfirst($kepanitiaan->anggota?->status_anggota ?? '-') }}</small>
                                            </td>
                                            <td class="py-3 px-4 border-bottom">
                                                @php
                                                    $badgeClass = 'bg-secondary';
                                                    if ($kepanitiaan->posisi === 'Ketua Pelaksana') $badgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                                    elseif ($kepanitiaan->posisi === 'Sekretaris') $badgeClass = 'bg-info-subtle text-info border border-info-subtle';
                                                    elseif ($kepanitiaan->posisi === 'Bendahara') $badgeClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                                    else $badgeClass = 'bg-light text-dark border';
                                                @endphp
                                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1.5 fs-7">{{ $kepanitiaan->posisi }}</span>
                                            </td>
                                            <td class="py-3 px-4 border-bottom text-end">
                                                <button class="btn btn-outline-danger btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#hapusPanitiaModal" 
                                                        data-id="{{ $kepanitiaan->id_kepanitiaan }}" 
                                                        data-nama="{{ $kepanitiaan->anggota?->nama ?? '-' }}"
                                                        data-posisi="{{ $kepanitiaan->posisi }}"
                                                        onclick="bukaHapusPanitia(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Anggota Panitia -->
    <div class="modal fade" id="hapusPanitiaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-dash text-danger" style="font-size:48px;"></i>
                    </div>
                    <h5 class="fw-bold">Keluarkan Anggota Panitia?</h5>
                    <p class="text-muted mb-0">
                        Apakah Anda yakin ingin mengeluarkan <strong id="hapusNamaPanitia">-</strong> dari posisi <strong id="hapusPosisiPanitia">-</strong> dalam kepanitiaan?
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <form id="formHapusPanitia" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 rounded-3">Ya, Keluarkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaHapusPanitia(btn) {
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            const posisi = btn.dataset.posisi;

            document.getElementById('hapusNamaPanitia').innerText = nama;
            document.getElementById('hapusPosisiPanitia').innerText = posisi;
            document.getElementById('formHapusPanitia').action = `/kepanitiaan/${id}`;
        }
    </script>
</x-sidebar>
