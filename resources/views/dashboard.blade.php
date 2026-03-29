<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>Dashboard | Smart Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-stats { transition: transform 0.2s; border: none; border-radius: 15px; }
        .card-stats:hover { transform: translateY(-5px); }
        .navbar { background-color: #2c3e50 !important; }
        .btn-manage { background-color: #1abc9c; color: white; border: none; }
        .btn-manage:hover { background-color: #16a085; color: white; }
        .btn-event { background-color: #3498db; color: white; border: none; }
        .btn-event:hover { background-color: #2980b9; color: white; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-shield-check"></i> SMART ATTENDANCE
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('peserta.index') }}" class="btn btn-manage btn-sm fw-bold me-2">
                    <i class="bi bi-people-fill"></i> Kelola Peserta
                </a>

                <a href="{{ route('event.index') }}" class="btn btn-event btn-sm fw-bold me-2">
                    <i class="bi bi-calendar-event"></i> Kelola Acara
                </a>

                <a href="{{ url('/scanner') }}" class="btn btn-light btn-sm fw-bold me-3 text-primary">
                    <i class="bi bi-qr-code-scan"></i> Scanner
                </a>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-0">Monitoring Kehadiran</h4>
                <p class="text-muted small">Data sinkron dengan tabel users dan events di database</p>
            </div>
            <a href="{{ url('/reset-data') }}" class="btn btn-outline-danger btn-sm"
               onclick="return confirm('Yakin ingin menghapus semua log?')">
                <i class="bi bi-trash"></i> Reset Log
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stats shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3><p class="small mb-0 opacity-75">Total Absensi</p></div>
                            <i class="bi bi-people fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h3 class="fw-bold mb-0">{{ $stats['rfid'] }}</h3><p class="small mb-0 opacity-75">Metode RFID</p></div>
                            <i class="bi bi-credit-card-2-front fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h3 class="fw-bold mb-0">{{ $stats['qr'] }}</h3><p class="small mb-0 opacity-75">Metode QR</p></div>
                            <i class="bi bi-qr-code fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3><p class="small mb-0 opacity-75">Pending Verifikasi</p></div>
                            <i class="bi bi-clock-history fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-list-ul"></i> Log Kehadiran Terbaru</h5>
                        <span class="badge bg-success shadow-sm">Live Updates (10s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Waktu</th>
                                        <th>Nama Peserta</th>
                                        <th>Role</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $log->waktu_hadir }}</td>
                                        <td><span class="fw-bold">{{ $log->user->nama_lengkap ?? 'User Tidak Dikenal' }}</span></td>
                                        <td><span class="badge bg-light text-dark border">{{ $log->user->role ?? '-' }}</span></td>
                                        <td>
                                            @if($log->metode_input == 'rfid')
                                                <span class="text-primary"><i class="bi bi-credit-card-2-front"></i> RFID</span>
                                            @else
                                                <span class="text-danger"><i class="bi bi-qr-code-scan"></i> QR Code</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $log->status_verifikasi == 'verified' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ strtoupper($log->status_verifikasi) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-database-exclamation fs-1 d-block mb-2"></i>
                                            Belum ada data kehadiran hari ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 mb-4 text-muted">
        <small>&copy; 2026 PBL-TRPL-403 | Batam State Polytechnic | Wasco Project Integration</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
