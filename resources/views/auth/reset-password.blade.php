<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Password - Revoria</title>
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
            padding: 1.5rem;
        }
        .reset-card {
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
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1.5px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #0f2d5c;
            box-shadow: 0 0 0 3px rgba(15,45,92,0.1);
        }
        .btn-submit {
            background: #0f2d5c;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: .3s;
        }
        .btn-submit:hover { background: #1f4b8f; color: white; }
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
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="login-logo">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4 class="fw-bold text-center mb-1">REVORIA</h4>
        <p class="text-center text-muted small mb-4">Atur Ulang Password</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $request->email) }}" placeholder="email@contoh.com" required autocomplete="username">
                </div>
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="••••••••" required autocomplete="new-password">
                </div>
                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           placeholder="••••••••" required autocomplete="new-password">
                </div>
                @error('password_confirmation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-submit w-100">
                Atur Ulang Password
            </button>
        </form>
    </div>
</body>
</html>
