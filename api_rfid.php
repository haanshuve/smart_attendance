<?php
include 'config.php';

// 1. Pastikan parameter 'uid' ada agar tidak muncul error 'Undefined Index'
if (isset($_GET['uid'])) {
    $uid = mysqli_real_escape_string($conn, $_GET['uid']); // Keamanan dari SQL Injection

    // 2. Query mencari user berdasarkan RFID UID
    $sql = "SELECT id_user, nama_lengkap, role FROM users WHERE rfid_uid = '$uid'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Berikan respon sukses beserta data nama agar bisa tampil di alat/web
        echo json_encode([
            "status" => "success", 
            "message" => "Akses Diterima",
            "nama" => $user['nama_lengkap'],
            "role" => $user['role']
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Kartu Tidak Terdaftar"
        ]);
    }
} else {
    // Jika file diakses langsung tanpa parameter UID
    echo json_encode([
        "status" => "error", 
        "message" => "Parameter UID tidak ditemukan"
    ]);
}
?>