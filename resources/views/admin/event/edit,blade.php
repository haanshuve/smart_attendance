<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Edit Event</h3>

    <form action="{{ route('event.update', $event->id_event) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Event</label>
            <input type="text" name="nama_event" class="form-control" value="{{ $event->nama_event }}">
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $event->tanggal }}">
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $event->lokasi }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status_event" class="form-control">
                <option value="planned" {{ $event->status_event == 'planned' ? 'selected' : '' }}>Planned</option>
                <option value="ongoing" {{ $event->status_event == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ $event->status_event == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ $event->deskripsi }}</textarea>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('event.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
