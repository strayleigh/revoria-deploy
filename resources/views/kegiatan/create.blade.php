<x-sidebar title="Tambah Kegiatan">
    <h2 class="mb-4 fw-bold">Tambah Kegiatan</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('kegiatan.store') }}" method="POST">
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="terjadwal"   {{ old('status') == 'terjadwal'   ? 'selected' : '' }}>Terjadwal</option>
                            <option value="berlangsung" {{ old('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai"     {{ old('status') == 'selesai'     ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Progres (%)</label>
                        <input type="number" name="progres" class="form-control" value="{{ old('progres', 0) }}" min="0" max="100">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tSelesai = document.querySelector('input[name="tanggal_selesai"]');
            const statusSelect = document.querySelector('select[name="status"]');

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

            tMulai.addEventListener('change', autoUpdateStatus);
            tSelesai.addEventListener('change', autoUpdateStatus);
        });
    </script>
        </div>
    </div>
</x-sidebar>
