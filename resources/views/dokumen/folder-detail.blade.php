<x-sidebar title="Detail Folder">

    <a href="{{ route('dokumen.folder', $kegiatan->kode_kegiatan) }}"
       class="btn btn-link text-decoration-none ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Folder Kegiatan
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4">
            {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        <!-- ================= MAIN: INFO FOLDER ================= -->
        <div class="{{ auth()->user()->role === 'pembina' ? 'col-12' : 'col-lg-8' }}">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <!-- Header folder -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div style="font-size:2.5rem; color:#6c63ff;">
                                <i class="bi bi-folder-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $folder->nama_folder }}</h5>
                                <small class="text-muted">
                                    Kegiatan: {{ $kegiatan->nama_kegiatan }}
                                    &bull; Dibuat {{ $folder->tanggal_dibuat?->format('d M Y') ?? '-' }}
                                </small>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'pengurus' || auth()->user()->name === 'admin')
                        <button class="btn btn-outline-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#editFolderModal">
                            <i class="bi bi-pencil"></i> Edit Folder
                        </button>
                        @endif
                    </div>

                    <!-- Info Google Drive -->
                    <div class="p-3 rounded-3 border mb-4 gdrive-box">
                        <div class="d-flex align-items-center gap-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/da/Google_Drive_logo.png"
                                 width="36" alt="Google Drive">
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">Link Google Drive</div>
                                @if($folder->gdrive_folder)
                                    <a href="{{ $folder->gdrive_folder }}" target="_blank"
                                       class="text-break small">
                                        {{ $folder->gdrive_folder }}
                                    </a>
                                @else
                                    <span class="text-muted small">Belum ada link Google Drive. Klik Edit Folder untuk menambahkan.</span>
                                @endif
                            </div>
                            @if($folder->gdrive_folder)
                                <a href="{{ $folder->gdrive_folder }}" target="_blank"
                                   class="btn btn-success btn-sm flex-shrink-0">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka Drive
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Detail info -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-label">Nama Folder</div>
                            <div class="detail-value">{{ $folder->nama_folder }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Kegiatan</div>
                            <div class="detail-value">{{ $kegiatan->nama_kegiatan }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Tanggal Dibuat</div>
                            <div class="detail-value">{{ $folder->tanggal_dibuat?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Status Drive</div>
                            <div class="detail-value">
                                @if($folder->gdrive_folder)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>Terhubung
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-dash-circle me-1"></i>Belum terhubung
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @if(auth()->user()->role !== 'pembina')
        <!-- ================= SIDEBAR: AKSI CEPAT ================= -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Aksi</h6>
                    <div class="d-grid gap-2">
                        @if($folder->gdrive_folder)
                            <a href="{{ $folder->gdrive_folder }}" target="_blank"
                               class="btn btn-primary">
                                <i class="bi bi-box-arrow-up-right"></i> Buka di Google Drive
                            </a>
                        @endif
                        <button class="btn btn-outline-warning"
                                data-bs-toggle="modal" data-bs-target="#editFolderModal">
                            <i class="bi bi-pencil"></i> Edit Nama & Link Drive
                        </button>
                        <button class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#hapusFolderModal">
                            <i class="bi bi-trash"></i> Hapus Folder
                        </button>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-2">Cara Pakai</h6>
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-1">Buat folder di Google Drive untuk kegiatan ini.</li>
                        <li class="mb-1">Salin link folder Google Drive.</li>
                        <li>Tempel link di sini lewat <strong>Edit Folder</strong>.</li>
                    </ol>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- ================= MODAL EDIT FOLDER ================= -->
    <div class="modal fade" id="editFolderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form action="{{ route('dokumen.folder.update', $folder->id_folder) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Folder</h5>
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
                            <label class="form-label fw-semibold">
                                Nama Folder <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_folder" class="form-control"
                                   value="{{ old('nama_folder', $folder->nama_folder) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link Google Drive</label>
                            <input type="url" name="gdrive_folder" class="form-control"
                                   value="{{ old('gdrive_folder', $folder->gdrive_folder) }}"
                                   placeholder="https://drive.google.com/drive/folders/...">
                            <div class="form-text">
                                Salin link folder dari Google Drive, lalu tempel di sini.
                            </div>
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
                    <i class="bi bi-exclamation-triangle-fill text-danger mb-3" style="font-size:48px;"></i>
                    <h5 class="fw-bold">Hapus Folder?</h5>
                    <p class="text-muted mb-0">
                        Apakah kamu yakin ingin menghapus folder
                        <strong>{{ $folder->nama_folder }}</strong>?
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('dokumen.folder.destroy', $folder->id_folder) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Buka modal edit otomatis jika ada error validasi --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new bootstrap.Modal(document.getElementById('editFolderModal')).show();
            });
        </script>
    @endif

</x-sidebar>
