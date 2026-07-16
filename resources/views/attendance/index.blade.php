<x-sidebar title="Absensi">

    <h2 class="mb-4 fw-bold">Absensi</h2>

    <!-- SEARCH -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-lg">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari nama anggota">
                    </div>
                </div>
                <div class="col-lg-auto">
                    <input type="date" class="form-control" style="border-radius:12px;">
                </div>
                <div class="col-lg-auto">
                    <select class="form-select">
                        <option>Semua Kegiatan</option>
                    </select>
                </div>
                <div class="col-lg-auto">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle"></i> Input Absensi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card border-0 shadow rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="py-5 text-muted">
                            <i class="bi bi-check2-square fs-1 d-block mb-2"></i>
                            Belum ada data absensi.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-sidebar>
