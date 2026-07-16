<x-sidebar title="Dokumen">

    <h2 class="mb-4 fw-bold">Dokumen</h2>

    <!-- SEARCH -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-lg">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari dokumen">
                    </div>
                </div>
                <div class="col-lg-auto">
                    <select class="form-select">
                        <option>Semua Kategori</option>
                        <option>Surat</option>
                        <option>Laporan</option>
                        <option>Foto</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div class="col-lg-auto">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-upload"></i> Upload Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- LIST DOKUMEN -->
    <div class="row g-4">
        <div class="col-12 text-muted text-center py-5">
            <i class="bi bi-folder fs-1 d-block mb-2"></i>
            Belum ada dokumen.
        </div>
    </div>

</x-sidebar>
