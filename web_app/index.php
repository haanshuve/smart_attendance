<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Event Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">🛡️ Smart Event & Visitor Management</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Log Kehadiran Real-Time</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Metode</th>
                                    <th>Status Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT a.waktu_hadir, u.nama_lengkap, u.role, a.metode_input, a.status_verifikasi 
                                        FROM attendance a 
                                        JOIN users u ON a.id_user = u.id_user 
                                        ORDER BY a.waktu_hadir DESC";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>{$row['waktu_hadir']}</td>
                                            <td>{$row['nama_lengkap']}</td>
                                            <td><span class='badge bg-info text-dark'>{$row['role']}</span></td>
                                            <td>" . strtoupper($row['metode_input']) . "</td>
                                            <td><span class='badge " . ($row['status_verifikasi'] == 'verified' ? 'bg-success' : 'bg-warning') . "'>{$row['status_verifikasi']}</span></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>