<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Revoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #0f2d5c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .register-card {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            width: 70px;
            height: 70px;
            background: #0f2d5c;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: #ffd54f;
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1.5px solid #e0e0e0;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0f2d5c;
            box-shadow: 0 0 0 3px rgba(15,45,92,0.1);
        }
        .btn-register {
            background: #0f2d5c;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: .3s;
        }
        .btn-register:hover { background: #1f4b8f; color: white; }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f5f7fb;
            border: 1.5px solid #e0e0e0;
            border-right: none;
            color: #0f2d5c;
        }
        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }
        .input-group .form-control:focus {
            border-color: #0f2d5c;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="register-card">

        <div class="login-logo">
            <i class="bi bi-people-fill"></i>
        </div>

        <h4 class="fw-bold text-center mb-1">REVORIA</h4>
        <p class="text-center text-muted small mb-4">Buat Akun Baru</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" id="labelName">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" id="inputName"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Username" required autofocus>
                </div>
                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="email@contoh.com" required>
                </div>
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Daftar Sebagai</label>
                <select name="role" id="selectRole" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="anggota" {{ old('role', 'anggota') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                    <option value="pembina" {{ old('role') == 'pembina' ? 'selected' : '' }}>Pembina</option>
                </select>
                @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                </div>
                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100">
                <i class="bi bi-person-plus me-2"></i>Buat Akun
            </button>

            <p class="text-center text-muted small mt-3 mb-0">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#0f2d5c;">Masuk</a>
            </p>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const selectRole  = document.getElementById('selectRole');
        const labelName   = document.getElementById('labelName');
        const inputName   = document.getElementById('inputName');

        function updateNameField() {
            const isPembina = selectRole.value === 'pembina';
            labelName.textContent = isPembina ? 'Nama Lengkap' : 'Username';
            inputName.placeholder = isPembina ? 'Nama lengkap' : 'Username';
        }

        selectRole.addEventListener('change', updateNameField);

        // Jalankan saat load (untuk handle old() value saat validasi gagal)
        updateNameField();
    </script>
</body>
</html>
