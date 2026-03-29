<?php
include 'config.php';
header('Content-Type: application/json');

// Mengambil data, bisa dari parameter 'uid' (RFID) atau 'qr' (QR Code)
$id_input = isset($_GET['uid']) ? $_GET['uid'] : (isset($_GET['qr']) ? $_GET['qr'] : null);
$metode = isset($_GET['uid']) ? 'rfid' : 'qr';

if ($id_input) {
    // Mencari di database: cocokkan dengan rfid_uid ATAU qr_data
    $sql = "SELECT id_user, nama_lengkap FROM users 
            WHERE rfid_uid = '$id_input' OR qr_data = '$id_input' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $id_user = $user['id_user'];

        // Masukkan ke attendance
        $insert = mysqli_query($conn, "INSERT INTO attendance (id_user, id_event, metode_input, status_verifikasi) 
                                       VALUES ('$id_user', 1, '$metode', 'pending')");
        
        echo json_encode(["status" => "success", "nama" => $user['nama_lengkap'], "metode" => $metode]);
    } else {
        echo json_encode(["status" => "error", "message" => "ID Tidak Dikenal"]);
    }
}
?>