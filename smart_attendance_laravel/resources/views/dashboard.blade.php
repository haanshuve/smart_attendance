<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5"> 
    <title>Smart Attendance - Laravel Version</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-stats { transition: transform 0.2s; }
        .card-stats:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🛡️ Smart Attendance Dashboard</a>
            <div class="d-flex">
                <a href="{{ url('/scanner') }}" class="btn btn-light btn-sm fw-bold"><i class="bi bi-qr-code-scan"></i> Buka Scanner</a>
            </div>
        </div>
    </nav>


    <a href="{{ url('/reset-data') }}" class="btn btn-outline-danger btn-sm" 
   onclick="return confirm('Yakin ingin menghapus semua log?')">
   <i class="bi bi-trash"></i> Reset
</a>


    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stats border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div><h3 class="fw-bold">{{ $stats['total'] }}</h3><p class="mb-0">Total Absensi</p></div>
                            <div class="align-self-center"><i class="bi bi-people fill-white fs-1 opacity-50"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div><h3 class="fw-bold">{{ $stats['rfid'] }}</h3><p class="mb-0">Metode RFID</p></div>
                            <div class="align-self-center"><i class="bi bi-credit-card-2-front fs-1 opacity-50"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats border-0 shadow-sm bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div><h3 class="fw-bold">{{ $stats['qr'] }}</h3><p class="mb-0">Metode QR</p></div>
                            <div class="align-self-center"><i class="bi bi-qr-code fs-1 opacity-50"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats border-0 shadow-sm bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div><h3 class="fw-bold">{{ $stats['pending'] }}</h3><p class="mb-0">Status Pending</p></div>
                            <div class="align-self-center"><i class="bi bi-clock-history fs-1 opacity-50"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary fw-bold">Log Kehadiran Terbaru</h5>
                        <span class="badge bg-success shadow-sm">Live Updates</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Nama Peserta</th>
                                        <th>Role</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td class="text-muted small">{{ $log->waktu_hadir }}</td>
                                        <td><strong>{{ $log->user->nama_lengkap ?? 'User Tidak Dikenal' }}</strong></td>
                                        <td><span class="badge bg-light text-dark border">{{ $log->user->role ?? '-' }}</span></td>
                                        <td>
                                            @if($log->metode_input == 'rfid')
                                                <i class="bi bi-credit-card-2-front text-primary"></i> RFID
                                            @else
                                                <i class="bi bi-qr-code-scan text-danger"></i> QR Code
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
        <small>&copy; 2026 PBL Smart Attendance System - Laravel Framework</small>
    </footer>
</body>
</html>