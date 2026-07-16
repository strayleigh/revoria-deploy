<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Revoria' }} - Karang Taruna</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-lg-2 sidebar">
                <div class="logo">
                    <h4 class="text-white mb-0">REVORIA</h4>
                </div>
                <ul class="nav flex-column mt-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                            <i class="bi bi-people"></i> Anggota
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('activities*') ? 'active' : '' }}" href="/activities">
                            <i class="bi bi-calendar-event"></i> Kegiatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}" href="/attendance">
                            <i class="bi bi-check2-square"></i> Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('documents*') ? 'active' : '' }}" href="/documents">
                            <i class="bi bi-folder"></i> Dokumen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('finance*') ? 'active' : '' }}" href="/finance">
                            <i class="bi bi-wallet2"></i> Keuangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" href="/reports">
                            <i class="bi bi-file-earmark-text"></i> Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i> Profil
                        </a>
                    </li>
                </ul>
                <div class="logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="col-lg-10 content">
                {{ $slot }}
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
