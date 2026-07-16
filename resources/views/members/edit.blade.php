<x-sidebar title="Edit Anggota">
    <h2 class="mb-4 fw-bold">Edit Anggota</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('members.update', $anggota) }}" method="POST">
                @csrf @method('PATCH')
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $anggota->nama) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik', $anggota->nik) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                        <select name="jabatan" class="form-select" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach(['Ketua','Wakil Ketua','Sekretaris','Bendahara','Anggota'] as $j)
                                <option value="{{ $j }}" {{ old('jabatan', $anggota->jabatan) == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Bergabung <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_bergabung" class="form-control" value="{{ old('tanggal_bergabung', $anggota->tanggal_bergabung?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status_anggota" class="form-select" required>
                            <option value="aktif" {{ old('status_anggota', $anggota->status_anggota) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak aktif" {{ old('status_anggota', $anggota->status_anggota) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
