<x-sidebar title="Kegiatan">

    <h2 class="mb-4 fw-bold">Kegiatan</h2>

    <!-- SEARCH -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-lg">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari kegiatan">
                    </div>
                </div>
                <div class="col-lg-auto">
                    <select class="form-select">
                        <option>Semua Status</option>
                        <option>Berlangsung</option>
                        <option>Akan Datang</option>
                        <option>Selesai</option>
                    </select>
                </div>
                <div class="col-lg-auto">
                    <select class="form-select">
                        <option>Semua Tahun</option>
                        <option>2026</option>
                        <option>2025</option>
                    </select>
                </div>
                <div class="col-lg-auto">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle"></i> Tambah Kegiatan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- LIST KEGIATAN -->
    <div class="row g-4">
        <div class="col-12 text-muted text-center py-5">
            <i class="bi bi-calendar-event fs-1 d-block mb-2"></i>
            Belum ada kegiatan.
        </div>
    </div>

</x-sidebar>
