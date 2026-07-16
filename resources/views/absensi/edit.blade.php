<x-sidebar title="Edit Absensi">
    <h2 class="mb-4 fw-bold">Edit Absensi</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('absensi.update', $absensi) }}" method="POST">
                @csrf @method('PATCH')
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Anggota</label>
                        <input type="text" class="form-control" value="{{ $absensi->anggota?->nama ?? '-' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kegiatan <span class="text-danger">*</span></label>
                        <select name="kode_kegiatan" class="form-select" required>
                            <option value="">Pilih Kegiatan</option>
                            @foreach($kegiatans as $k)
                                <option value="{{ $k->kode_kegiatan }}" {{ old('kode_kegiatan', $absensi->kode_kegiatan) == $k->kode_kegiatan ? 'selected' : '' }}>
                                    {{ $k->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_absensi" class="form-control" value="{{ old('tanggal_absensi', $absensi->tanggal_absensi?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Absen</label>
                        <input type="time" name="waktu_absen" class="form-control" value="{{ old('waktu_absen', $absensi->waktu_absen) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status_hadir" class="form-select" required>
                            <option value="hadir"       {{ old('status_hadir', $absensi->status_hadir) == 'hadir'        ? 'selected' : '' }}>Hadir</option>
                            <option value="tidak hadir" {{ old('status_hadir', $absensi->status_hadir) == 'tidak hadir'  ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="izin"        {{ old('status_hadir', $absensi->status_hadir) == 'izin'         ? 'selected' : '' }}>Izin</option>
                            <option value="sakit"       {{ old('status_hadir', $absensi->status_hadir) == 'sakit'        ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
