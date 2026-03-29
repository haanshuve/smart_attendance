<?php
include 'config.php';

header('Content-Type: application/json');

$user_id  = isset($_POST['id_user']) ? $_POST['id_user'] : null;
$event_id = isset($_POST['id_event']) ? $_POST['id_event'] : 1; 

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "ID User tidak ditemukan!"]);
    exit;
}

$user_id = mysqli_real_escape_string($conn, $user_id);


$sql = "UPDATE attendance SET 
        status_verifikasi = 'verified', 
        waktu_hadir = NOW()
        WHERE id_user = '$user_id' AND id_event = '$event_id' AND status_verifikasi = 'pending'
        ORDER BY waktu_hadir DESC LIMIT 1";

if (mysqli_query($conn, $sql)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(["status" => "success", "message" => "Wajah Cocok! Absensi Berhasil."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tidak ada antrean absensi untuk user ini."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database Error: " . mysqli_error($conn)]);
}
?>