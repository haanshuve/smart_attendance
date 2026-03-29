<?php 
include 'config.php'; 

// Fungsi sederhana untuk generate QR Code via Google Charts API
function generateQR($data) {
    return "https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=" . urlencode($data);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5"> <title>Smart Event Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">🛡️ Smart Event & Visitor Management</a>
            <a href="scanner.php" class="btn btn-outline-light btn-sm">Buka Scanner QR</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Log Kehadiran Real-Time</h5>
                <span class="badge bg-primary">Live Monitoring</span>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Metode</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi / QR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query mengambil data attendance dan join ke users
                        // u.qr_data diambil untuk generate QR di baris tabel
                        $sql = "SELECT a.waktu_hadir, u.nama_lengkap, u.role, a.metode_input, a.status_verifikasi, u.qr_data 
                                FROM attendance a 
                                JOIN users u ON a.id_user = u.id_user 
                                ORDER BY a.waktu_hadir DESC LIMIT 10";
                        $result = mysqli_query($conn, $sql);
                        
                        while($row = mysqli_fetch_assoc($result)) {
                            // Logika Warna Status
                            $status_class = ($row['status_verifikasi'] == 'verified') ? 'bg-success' : 'bg-warning text-dark';
                            
                            // Logika Ikon Metode
                            if (strtolower($row['metode_input']) == 'rfid') {
                                $metode_ikon = "<i class='bi bi-credit-card-2-front-fill text-primary'></i> RFID";
                            } else {
                                $metode_ikon = "<i class='bi bi-qr-code-scan text-danger'></i> QR Code";
                            }

                            echo "<tr>
                                    <td>{$row['waktu_hadir']}</td>
                                    <td><strong>{$row['nama_lengkap']}</strong></td>
                                    <td><span class='badge bg-info text-dark'>{$row['role']}</span></td>
                                    <td>$metode_ikon</td>
                                    <td><span class='badge $status_class'>{$row['status_verifikasi']}</span></td>
                                    <td>
                                        <img src='" . generateQR($row['qr_data']) . "' alt='QR' style='width:50px; cursor:pointer;' 
                                             onclick='alert(\"Data QR: {$row['qr_data']}\")'>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-white border rounded">
            <h6>💡 Tips Pengetesan:</h6>
            <small class="text-muted">
                1. Untuk simulasi <strong>RFID</strong>, akses: <code>api_rfid.php?uid=[isi_rfid_uid_di_db]</code><br>
                2. Untuk simulasi <strong>QR</strong>, buka halaman <code>scanner.php</code> dan arahkan gambar QR di atas ke kamera.
            </small>
        </div>
    </div>
</body>
</html>