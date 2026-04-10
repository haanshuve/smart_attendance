<!DOCTYPE html>
<html>
<head>
    <title>Kelola Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h3>{{ $event->nama_event }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- FORM TAMBAH -->
    <div class="card p-3 mb-4">
        <form method="POST" action="/event/tambah-peserta">
            @csrf

            <select name="user_id" class="form-control mb-2" required>
                <option value="">-- Pilih Peserta --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}">
                        {{ $u->nama_lengkap }}
                    </option>
                @endforeach
            </select>

            <!-- 🔥 PILIH METODE -->
            <select name="method" class="form-control mb-2" required>
                <option value="">-- Pilih Metode --</option>
                <option value="rfid">RFID</option>
                <option value="qr">QR</option>
            </select>

            <input type="hidden" name="event_id" value="{{ $event->id_event }}">

            <button class="btn btn-primary">Tambah</button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card p-3">
        <table class="table">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>

            @foreach($participants as $p)
            <tr>
                <td>{{ $p->nama_lengkap }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ strtoupper($p->method) }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

</body>
</html>
