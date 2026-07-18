<x-sidebar title="Folder Dokumen">

    <a href="{{ route('dokumen.index') }}" class="btn btn-link text-decoration-none ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kegiatan
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        <!-- ================= MAIN: FOLDER LIST ================= -->
        <div class="{{ auth()->user()->role === 'pembina' ? 'col-12' : 'col-lg-9' }}">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">Folder Dokumen</h4>
                            <small class="text-muted">
                                Kegiatan: <strong>{{ $kegiatan->nama_kegiatan }}</strong>
                            </small>
                        </div>
                        @if(auth()->user()->role === 'pengurus' || auth()->user()->name === 'admin')
                        <button class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#folderBaruModal">
                            <i class="bi bi-folder-plus"></i> Folder Baru
                        </button>
                        @endif
                    </div>

                    @if($folders->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-folder fs-1 d-block mb-2"></i>
                            Belum ada folder. Buat folder baru untuk mulai menyimpan dokumen.
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($folders as $folder)
                                <div class="col-lg-4 d-flex">
                                    <div class="folder-card shadow border-0 w-100 h-100 p-4 d-flex flex-column justify-content-between"
                                         style="border-radius: 20px; transition: all 0.3s;"
                                         role="button"
                                         onclick="window.location.href='{{ route('dokumen.folder-detail', $folder->id_folder) }}'">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="folder-icon-wrapper rounded-4 d-flex align-items-center justify-content-center bg-primary-subtle" style="width: 52px; height: 52px; background: rgba(108, 99, 255, 0.1);">
                                                    <i class="bi bi-folder-fill fs-2 text-primary" style="color: #6c63ff !important; filter: drop-shadow(0 4px 6px rgba(108, 99, 255, 0.25));"></i>
                                                </div>
                                                
                                                <div class="d-flex align-items-center gap-1.5">

                                                    
                                                    @if(auth()->user()->role === 'pengurus' || auth()->user()->name === 'admin')
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm folder-menu-btn rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                style="width: 32px; height: 32px; background: rgba(0,0,0,0.025);"
                                                                data-bs-toggle="dropdown"
                                                                onclick="event.stopPropagation()">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" onclick="event.stopPropagation()">
                                                            <li>
                                                                <a class="dropdown-item py-2"
                                                                   href="#"
                                                                   onclick="bukaUbahNama(event, {{ $folder->id_folder }}, '{{ $folder->nama_folder }}')">
                                                                    <i class="bi bi-pencil me-2 text-warning"></i> Ubah Nama
                                                                </a>
                                                            </li>
                                                            @if($folder->gdrive_folder)
                                                                <li>
                                                                    <a class="dropdown-item py-2"
                                                                       href="{{ $folder->gdrive_folder }}"
                                                                       target="_blank">
                                                                        <i class="bi bi-google me-2 text-success"></i> Buka di Drive
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item py-2 text-danger"
                                                                   href="#"
                                                                   onclick="bukaHapusFolder(event, {{ $folder->id_folder }}, '{{ $folder->nama_folder }}')">
                                                                    <i class="bi bi-trash me-2"></i> Hapus Folder
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <h5 class="fw-bold text-dark dark:text-white mb-2" style="font-size: 17px; line-height: 1.4;">{{ $folder->nama_folder }}</h5>
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                                            <span>Dibuat</span>
                                            <span>{{ $folder->tanggal_dibuat?->format('d M Y') ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->role !== 'pembina')
        <!-- ================= SIDEBAR: GOOGLE DRIVE INFO ================= -->
        <div class="col-lg-3">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body">
                    <div class="text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/da/Google_Drive_logo.png" width="60" alt="Google Drive">
                        <h5 class="mt-3">Google Drive</h5>
                        @if($kegiatan->folder()->whereNotNull('gdrive_folder')->exists())
                            <span class="badge bg-success">Terhubung</span>
                        @else
                            <span class="badge bg-secondary">Belum Terhubung</span>
                        @endif
                    </div>
                    <hr>
                    <p class="small text-muted">Seluruh folder dibuat dari sistem dan dapat disimpan pada Google Drive.</p>
                    <p class="small text-muted">Tambahkan link Google Drive saat membuat folder baru.</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- ================= MODAL FOLDER BARU ================= -->
    <div class="modal fade" id="folderBaruModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form action="{{ route('dokumen.folder.store', $kegiatan->kode_kegiatan) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Buat Folder Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Folder <span class="text-danger">*</span></label>
                            <input type="text" name="nama_folder" class="form-control"
                                   placeholder="Contoh: Proposal Kegiatan" required
                                   value="{{ old('nama_folder') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link Google Drive</label>
                            <input type="url" name="gdrive_folder" class="form-control"
                                   placeholder="https://drive.google.com/drive/folders/..."
                                   value="{{ old('gdrive_folder') }}">
                            <div class="form-text">Opsional. Bisa ditambahkan nanti.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Buat Folder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL UBAH NAMA FOLDER ================= -->
    <div class="modal fade" id="ubahNamaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form id="formUbahNama" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Ubah Nama Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Folder Baru</label>
                            <input type="text" name="nama_folder" id="inputNamaFolderBaru"
                                   class="form-control" required>
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

    <!-- ================= MODAL HAPUS FOLDER ================= -->
    <div class="modal fade" id="hapusFolderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:48px;"></i>
                    </div>
                    <h5 class="fw-bold">Hapus Folder?</h5>
                    <p class="text-muted mb-0">
                        Apakah kamu yakin ingin menghapus folder <strong id="hapusFolderNama">-</strong>?
                        Semua file di dalamnya juga akan terhapus.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="formHapusFolder" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaUbahNama(e, id, namaLama) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('inputNamaFolderBaru').value = namaLama;
            document.getElementById('formUbahNama').action = `/dokumen/folder/${id}`;
            new bootstrap.Modal(document.getElementById('ubahNamaModal')).show();
        }

        function bukaHapusFolder(e, id, nama) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('hapusFolderNama').innerText = nama;
            document.getElementById('formHapusFolder').action = `/dokumen/folder/${id}`;
            new bootstrap.Modal(document.getElementById('hapusFolderModal')).show();
        }
    </script>

</x-sidebar>
