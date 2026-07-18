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
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                    </div>
                    
                    <!-- ========== CHECKLIST PERSIAPAN ========== -->
                    <div class="col-12 mt-3">
                        <label class="form-label fw-semibold d-flex justify-content-between">
                            <span>Checklist Persiapan</span>
                            <span class="fw-bold text-primary" id="progresValue">{{ $kegiatan->progres ?? 0 }}%</span>
                        </label>
                        <input type="hidden" name="progres" id="inputProgres" value="{{ old('progres', $kegiatan->progres ?? 0) }}">
                        <div class="progress mb-3" style="height:10px; border-radius: 5px;">
                            <div class="progress-bar" id="progresBarForm" role="progressbar" style="width: {{ $kegiatan->progres ?? 0 }}%"></div>
                        </div>
                        <div class="checklist-box p-3 border rounded-3 bg-light bg-opacity-50">
                            <div class="row g-3">
                                @php
                                    $items = ['Proposal','Surat Permohonan','Surat Peminjaman','Lokasi','Keuangan','Alat-alat','Konsumsi','LPJ'];
                                    $progres = old('progres', $kegiatan->progres ?? 0);
                                @endphp
                                @foreach($items as $index => $item)
                                    @php
                                        $threshold = ($index + 1) * 12.5;
                                        $isChecked = $progres >= ($threshold - 6);
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input checklist-item" type="checkbox"
                                                   id="cek{{ Str::slug($item) }}" value="{{ $item }}"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium" for="cek{{ Str::slug($item) }}">{{ $item }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function hitungProgres() {
                const items = document.querySelectorAll('.checklist-item');
                const total = items.length;
                const dicentang = document.querySelectorAll('.checklist-item:checked').length;
                const persen = total === 0 ? 0 : Math.round((dicentang / total) * 100);
                
                document.getElementById('progresValue').innerText = persen + '%';
                document.getElementById('progresBarForm').style.width = persen + '%';
                document.getElementById('inputProgres').value = persen;
            }

            document.querySelectorAll('.checklist-item').forEach(function(item) {
                item.addEventListener('change', hitungProgres);
            });
        });
    </script>
</x-sidebar>
