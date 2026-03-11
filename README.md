# 🛡️ Smart Event & Visitor Management System

Sistem manajemen kehadiran berbasis IoT dan Web dengan fitur **Multi-Factor Authentication (MFA)** untuk memastikan keamanan dan validitas data kehadiran pada acara rapat atau kunjungan tamu.

## 📋 Deskripsi Proyek
Proyek ini dikembangkan untuk mengatasi kelemahan sistem absensi tradisional (seperti titip absen). Dengan mengintegrasikan hardware IoT dan kecerdasan buatan (Face Recognition), sistem ini menjamin bahwa setiap peserta yang hadir benar-benar berada di lokasi.

### ✨ Fitur Utama
- **Multi-Device Input**: Mendukung identitas digital via RFID (Smart Card) dan QR Code (Visitor).
- **MFA Security**: Verifikasi berlapis menggunakan RFID/QR + Fingerprint + Face Recognition.
- **Real-Time Monitoring**: Dashboard web untuk memantau kehadiran secara langsung.
- **Automated Minutes of Meeting**: Generasi notulensi otomatis yang dikirimkan ke email peserta.

## 🛠️ Tech Stack
- **Hardware**: ESP32, RFID RC522, QR Scanner Module, AS608 Fingerprint Sensor.
- **Backend**: PHP 8.x, MySQL (Relational Database).
- **Frontend**: Bootstrap 5, Face-api.js (AI Face Recognition).
- **Communication**: HTTP REST API.

## 📂 Struktur Repositori
- `/hardware`: Source code Arduino/ESP32 (.ino).
- `/web-app`: Source code dashboard, konfigurasi database, dan endpoint API (PHP).
- `/database`: Skema database relasional (.sql).

## 🚀 Cara Instalasi
1. **Database**: Import file `smart_attendance.sql` yang ada di folder `/database` ke phpMyAdmin.
2. **Server**: Pindahkan seluruh isi folder `/web-app` ke direktori server lokal (contoh: `htdocs/smart_attendance`).
3. **Hardware**: Buka file di folder `/hardware` menggunakan Arduino IDE, sesuaikan SSID/Password WiFi, lalu upload ke ESP32.
4. **Akses**: Buka `localhost/smart_attendance` di browser Anda.

---
**PBL Project - Jurusan Teknik Informatika**