<x-sidebar title="Keuangan">

    <h2 class="mb-4 fw-bold">Keuangan</h2>

    <!-- SUMMARY -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="icon green"><i class="bi bi-arrow-down-circle"></i></div>
                <h5>Total Pemasukan</h5>
                <h2>Rp0</h2>
                <p>Keseluruhan</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="icon" style="background:#c62828;"><i class="bi bi-arrow-up-circle"></i></div>
                <h5>Total Pengeluaran</h5>
                <h2>Rp0</h2>
                <p>Keseluruhan</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="icon orange"><i class="bi bi-wallet2"></i></div>
                <h5>Saldo Kas</h5>
                <h2>Rp0</h2>
                <p>Total</p>
            </div>
        </div>
    </div>

    <!-- SEARCH -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-lg">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari transaksi">
                    </div>
                </div>
                <div class="col-lg-auto">
                    <select class="form-select">
                        <option>Semua Jenis</option>
                        <option>Pemasukan</option>
                        <option>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-lg-auto">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle"></i> Catat Transaksi
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
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="py-5 text-muted">
                            <i class="bi bi-wallet2 fs-1 d-block mb-2"></i>
                            Belum ada transaksi.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-sidebar>
