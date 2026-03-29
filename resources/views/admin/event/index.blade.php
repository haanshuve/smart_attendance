<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Acara | Smart Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"></i> DASHBOARD</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Manajemen Acara (FR-03)</h2>
            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#modalEvent">+ Buat Acara Baru</button>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm p-4">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $e)
                    <tr>
                        <td><strong>{{ $e->nama_event }}</strong></td>
                        <td>{{ $e->tanggal }}</td>
                        <td>{{ $e->lokasi }}</td>
                        <td>
                            <span class="badge {{ $e->status_event == 'ongoing' ? 'bg-success' : ($e->status_event == 'planned' ? 'bg-primary' : 'bg-secondary') }}">
                                {{ strtoupper($e->status_event) }}
                            </span>
                        </td>
                        <td><button class="btn btn-sm btn-outline-secondary">Edit</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalEvent" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('event.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Event Baru</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Event</label>
                        <input type="text" name="nama_event" class="form-control" placeholder="Contoh: Rapat Mingguan Wasco" required>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>


                            #adalah


                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status_event" class="form-select">
                                <option value="planned">Planned</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Gedung Wasco Lt. 2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
