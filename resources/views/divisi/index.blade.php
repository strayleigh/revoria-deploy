<x-sidebar title="Kelola Divisi">
    @php
        $user = auth()->user();
        $currentUserJabatan = strtolower($user->anggota?->jabatan ?? '');
        $canManage = (in_array($currentUserJabatan, ['ketua'], true) || $user->name === 'admin');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Kelola Divisi</h2>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Anggota
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- List Divisi -->
        <div class="{{ $canManage ? 'col-lg-8' : 'col-12' }}">
            <div class="card border-0 shadow rounded-4 p-4">
                <h5 class="fw-bold mb-3">Daftar Divisi</h5>
                <div class="table-responsive">
                    <table class="table align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th class="text-start">Nama Divisi</th>
                                @if($canManage)
                                    <th style="width: 150px;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($divisis as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start fw-semibold">{{ $d->nama_divisi }}</td>
                                    @if($canManage)
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Edit Button (Modal) -->
                                                <button class="btn btn-outline-warning btn-sm d-inline-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; padding: 0 !important;"
                                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $d->id_divisi }}">
                                                    <i class="bi bi-pencil" style="margin: 0 !important;"></i>
                                                </button>
                                                
                                                <!-- Delete Form -->
                                                <form action="{{ route('divisi.destroy', $d) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center"
                                                            style="width: 32px; height: 32px; padding: 0 !important;"
                                                            onclick="return confirm('Hapus divisi ini? Semua anggota di divisi ini akan diset tanpa divisi.')">
                                                        <i class="bi bi-trash" style="margin: 0 !important;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>

                                @if($canManage)
                                    <!-- Modal Edit Divisi -->
                                    <div class="modal fade" id="editModal{{ $d->id_divisi }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <form action="{{ route('divisi.update', $d) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Ubah Nama Divisi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Divisi</label>
                                                            <input type="text" name="nama_divisi" class="form-control"
                                                                   value="{{ $d->nama_divisi }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="{{ $canManage ? 3 : 2 }}" class="py-4 text-muted">Belum ada divisi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($canManage)
            <!-- Tambah Divisi Form -->
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Tambah Divisi Baru</h5>
                    <form action="{{ route('divisi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Divisi</label>
                            <input type="text" name="nama_divisi" class="form-control @error('nama_divisi') is-invalid @enderror"
                                   placeholder="Contoh: Divisi Kehumasan" required>
                            @error('nama_divisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-plus-circle"></i> Tambah Divisi
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-sidebar>
