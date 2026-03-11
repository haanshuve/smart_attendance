<?php
include 'config.php';

// Menghindari error jika data tidak dikirim
$user_id  = isset($_POST['id_user']) ? $_POST['id_user'] : null;
$event_id = isset($_POST['id_event']) ? $_POST['id_event'] : null;
$metode   = isset($_POST['metode']) ? $_POST['metode'] : 'rfid'; 

if (!$user_id || !$event_id) {
    echo json_encode(["status" => "error", "message" => "Parameter tidak lengkap!"]);
    exit;
}

// Gunakan mysqli_real_escape_string untuk keamanan database
$user_id  = mysqli_real_escape_string($conn, $user_id);
$event_id = mysqli_real_escape_string($conn, $event_id);
$metode   = mysqli_real_escape_string($conn, $metode);

// Logic: Mengubah status kehadiran menjadi 'verified'
// Kita tambahkan pengecekan apakah baris data tersebut memang ada
$sql = "UPDATE attendance SET 
        status_verifikasi = 'verified', 
        waktu_hadir = NOW(),
        metode_input = '$metode'
        WHERE id_user = '$user_id' AND id_event = '$event_id'";

if (mysqli_query($conn, $sql)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(["status" => "success", "message" => "Verifikasi Berhasil!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan atau sudah terverifikasi."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Gagal Verifikasi: " . mysqli_error($conn)]);
}
?>