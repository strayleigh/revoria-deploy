<x-sidebar title="Catat Transaksi">
    <h2 class="mb-4 fw-bold">Catat Transaksi</h2>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('keuangan.store') }}" method="POST">
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select name="jenis_transaksi" class="form-select" required>
                            <option value="pemasukan"   {{ old('jenis_transaksi') == 'pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ old('jenis_transaksi') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nominal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="nominal" class="form-control" value="{{ old('nominal') }}" min="0" required style="border-radius:0 12px 12px 0;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Kas"    {{ old('kategori') == 'Kas'    ? 'selected' : '' }}>Kas</option>
                            <option value="Iuran"  {{ old('kategori') == 'Iuran'  ? 'selected' : '' }}>Iuran</option>
                            <option value="Donasi" {{ old('kategori') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kegiatan Terkait</label>
                        <select name="kode_kegiatan" class="form-select">
                            <option value="">Tidak ada</option>
                            @foreach($kegiatans as $k)
                                <option value="{{ $k->kode_kegiatan }}" {{ old('kode_kegiatan') == $k->kode_kegiatan ? 'selected' : '' }}>
                                    {{ $k->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
