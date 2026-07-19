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
                        <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai?->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai?->format('Y-m-d\TH:i')) }}" required>
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
                            <!-- Container for checklist items -->
                            <div id="checklistContainer" class="d-flex flex-column gap-2 mb-3">
                                @php
                                    $persiapan = $kegiatan->persiapan;
                                    if (empty($persiapan)) {
                                        $persiapan = [
                                            ['name' => 'Proposal', 'checked' => false],
                                            ['name' => 'Surat Permohonan', 'checked' => false],
                                            ['name' => 'Surat Peminjaman', 'checked' => false],
                                            ['name' => 'Lokasi', 'checked' => false],
                                            ['name' => 'Keuangan', 'checked' => false],
                                            ['name' => 'Alat-alat', 'checked' => false],
                                            ['name' => 'Konsumsi', 'checked' => false],
                                            ['name' => 'LPJ', 'checked' => false],
                                        ];
                                    }
                                @endphp
                                @foreach($persiapan as $index => $item)
                                    <div class="row g-2 align-items-center checklist-row">
                                        <div class="col-auto">
                                            <input type="hidden" name="persiapan[{{ $index }}][checked]" value="0">
                                            <input class="form-check-input checklist-item" type="checkbox"
                                                   name="persiapan[{{ $index }}][checked]" value="1"
                                                   {{ !empty($item['checked']) ? 'checked' : '' }}>
                                        </div>
                                        <div class="col">
                                            <input type="text" name="persiapan[{{ $index }}][name]" 
                                                   class="form-control form-control-sm checklist-name" 
                                                   value="{{ $item['name'] }}" required placeholder="Nama item persiapan">
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle btn-hapus-item"
                                                    style="width: 32px; height: 32px; padding: 0;" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Add Item Button -->
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" id="btnTambahItem">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Item Persiapan
                            </button>
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
            const container = document.getElementById('checklistContainer');
            const btnTambah = document.getElementById('btnTambahItem');
            
            // Auto status trigger
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
            
            function hitungProgres() {
                const items = document.querySelectorAll('.checklist-item');
                const total = items.length;
                const dicentang = document.querySelectorAll('.checklist-item:checked').length;
                const persen = total === 0 ? 0 : Math.round((dicentang / total) * 100);
                
                document.getElementById('progresValue').innerText = persen + '%';
                document.getElementById('progresBarForm').style.width = persen + '%';
                document.getElementById('inputProgres').value = persen;
            }

            function reIndexInputs() {
                const rows = container.querySelectorAll('.checklist-row');
                rows.forEach((row, index) => {
                    const hiddenInput = row.querySelector('input[type="hidden"]');
                    const checkbox = row.querySelector('.checklist-item');
                    const textInput = row.querySelector('.checklist-name');
                    
                    hiddenInput.name = `persiapan[${index}][checked]`;
                    checkbox.name = `persiapan[${index}][checked]`;
                    textInput.name = `persiapan[${index}][name]`;
                });
            }

            container.addEventListener('click', function(e) {
                if (e.target.closest('.btn-hapus-item')) {
                    e.target.closest('.checklist-row').remove();
                    reIndexInputs();
                    hitungProgres();
                }
            });

            container.addEventListener('change', function(e) {
                if (e.target.classList.contains('checklist-item')) {
                    hitungProgres();
                }
            });

            btnTambah.addEventListener('click', function() {
                const newIndex = container.querySelectorAll('.checklist-row').length;
                const newRow = document.createElement('div');
                newRow.className = 'row g-2 align-items-center checklist-row';
                newRow.innerHTML = `
                    <div class="col-auto">
                        <input type="hidden" name="persiapan[${newIndex}][checked]" value="0">
                        <input class="form-check-input checklist-item" type="checkbox"
                               name="persiapan[${newIndex}][checked]" value="1">
                    </div>
                    <div class="col">
                        <input type="text" name="persiapan[${newIndex}][name]" 
                               class="form-control form-control-sm checklist-name" 
                               value="" required placeholder="Nama item persiapan">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle btn-hapus-item"
                                style="width: 32px; height: 32px; padding: 0;" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                newRow.querySelector('.checklist-name').focus();
                reIndexInputs();
                hitungProgres();
            });

            hitungProgres();
        });
    </script>
</x-sidebar>
