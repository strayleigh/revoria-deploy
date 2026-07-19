<x-sidebar title="Pengaturan">

    <h2 class="mb-4 fw-bold">Pengaturan</h2>

    <div class="row g-4">

        <!-- Update Info -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="profile-card w-100">
                <h5 class="fw-bold mb-4">Informasi Akun</h5>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('patch')
                    @if(session('status') === 'profile-updated')
                        <div class="alert alert-success rounded-3 py-2">Profil berhasil diperbarui.</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', auth()->user()->name) }}" required @disabled(auth()->user()->name !== 'admin')>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if(auth()->user()->name !== 'admin')
                            <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Nama lengkap hanya dapat diubah oleh Admin.</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            value="{{ old('no_hp', auth()->user()->anggota?->no_hp ?? auth()->user()->no_hp) }}" placeholder="Contoh: 08123456789">
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if(auth()->user()->anggota)
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select name="divisi_id" class="form-select @error('divisi_id') is-invalid @enderror" required>
                            @foreach($divisis as $d)
                                <option value="{{ $d->id_divisi }}" {{ old('divisi_id', auth()->user()->anggota->divisi_id) == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('divisi_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="profile-card w-100">
                <h5 class="fw-bold mb-4">Ubah Password</h5>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('put')
                    @if(session('status') === 'password-updated')
                        <div class="alert alert-success rounded-3 py-2">Password berhasil diubah.</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                            class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>
            </div>
        </div>

        <!-- Mode Tampilan (Theme Mode) -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="profile-card w-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-2">Mode Tampilan</h5>
                    <p class="text-muted small mb-4">Pilih tema tampilan untuk seluruh aplikasi.</p>
                    
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-4 border mb-0" style="background: rgba(0,0,0,0.02);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="theme-icon-container rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(0,0,0,0.05);">
                                <i class="bi bi-sun-fill fs-5 text-warning" id="themeIcon"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold" id="themeLabel">Mode Terang</h6>
                                <small class="text-muted" id="themeSublabel">Tampilan klasik yang bersih dan cerah.</small>
                            </div>
                        </div>
                        <div class="form-check form-switch fs-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" style="cursor: pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hapus Akun -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="profile-card border border-danger w-100">
                <h5 class="fw-bold text-danger mb-2">Hapus Akun</h5>
                <p class="text-muted small mb-3">Setelah akun dihapus, semua data akan hilang permanen.</p>
                <button class="btn btn-danger mt-auto" data-bs-toggle="modal" data-bs-target="#modalHapusAkun">
                    Hapus Akun
                </button>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeSwitch = document.getElementById('themeSwitch');
            const themeIcon = document.getElementById('themeIcon');
            const themeLabel = document.getElementById('themeLabel');
            const themeSublabel = document.getElementById('themeSublabel');

            const applyTheme = (isDark) => {
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    if (themeSwitch) themeSwitch.checked = true;
                    if (themeIcon) {
                        themeIcon.className = 'bi bi-moon-stars-fill fs-5 text-primary';
                    }
                    if (themeLabel) themeLabel.textContent = 'Mode Gelap';
                    if (themeSublabel) themeSublabel.textContent = 'Tampilan gelap yang nyaman untuk mata Anda.';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    if (themeSwitch) themeSwitch.checked = false;
                    if (themeIcon) {
                        themeIcon.className = 'bi bi-sun-fill fs-5 text-warning';
                    }
                    if (themeLabel) themeLabel.textContent = 'Mode Terang';
                    if (themeSublabel) themeSublabel.textContent = 'Tampilan klasik yang bersih dan cerah.';
                }
            };

            // Detect current theme
            const currentTheme = localStorage.getItem('theme') || 
                                 (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            applyTheme(currentTheme === 'dark');

            if (themeSwitch) {
                themeSwitch.addEventListener('change', (e) => {
                    applyTheme(e.target.checked);
                });
            }
        });
    </script>

    <!-- Modal Hapus Akun -->
    <div class="modal fade" id="modalHapusAkun" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger">Hapus Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf @method('delete')
                    <div class="modal-body">
                        <p class="text-muted">Masukkan password untuk konfirmasi penghapusan akun.</p>
                        <input type="password" name="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-sidebar>
