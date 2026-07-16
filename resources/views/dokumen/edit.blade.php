<x-sidebar title="Edit Dokumen">
    <h2 class="mb-4 fw-bold">Edit Dokumen</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('dokumen.update', $dokumen) }}" method="POST">
                @csrf @method('PATCH')
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Folder <span class="text-danger">*</span></label>
                        <input type="text" name="nama_folder" class="form-control" value="{{ old('nama_folder', $dokumen->nama_folder) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Link Google Drive</label>
                        <input type="url" name="gdrive_folder" class="form-control" value="{{ old('gdrive_folder', $dokumen->gdrive_folder) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Dibuat</label>
                        <input type="date" name="tanggal_dibuat" class="form-control" value="{{ old('tanggal_dibuat', $dokumen->tanggal_dibuat?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Kegiatan Terkait</label>
                        <select name="kode_kegiatan" class="form-select">
                            <option value="">Tidak ada</option>
                            @foreach($kegiatans as $k)
                                <option value="{{ $k->kode_kegiatan }}" {{ old('kode_kegiatan', $dokumen->kode_kegiatan) == $k->kode_kegiatan ? 'selected' : '' }}>
                                    {{ $k->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
