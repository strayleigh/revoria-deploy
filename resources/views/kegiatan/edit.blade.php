<x-sidebar title="Edit Kegiatan">
    <h2 class="mb-4 fw-bold">Edit Kegiatan</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('kegiatan.update', $kegiatan) }}" method="POST">
                @csrf @method('PATCH')
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $kegiatan->tanggal?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $kegiatan->lokasi) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="terjadwal"   {{ old('status', $kegiatan->status) == 'terjadwal'   ? 'selected' : '' }}>Terjadwal</option>
                            <option value="berlangsung" {{ old('status', $kegiatan->status) == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai"     {{ old('status', $kegiatan->status) == 'selesai'     ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Progres (%)</label>
                        <input type="number" name="progres" class="form-control" value="{{ old('progres', $kegiatan->progres) }}" min="0" max="100">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
