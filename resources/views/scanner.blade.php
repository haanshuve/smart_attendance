<!DOCTYPE html>
<html>
<head>
    <title>Scanner QR Code</title>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            text-align: center;
            margin: 0;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        #reader {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }

        .hasil-box {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
        }

        .loading {
            background: #e3f2fd;
            color: #0d6efd;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 15px;
            font-size: 12px;
            color: gray;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>📷 Scanner QR Code</h2>

    <div id="reader"></div>

    <div id="hasil" class="hasil-box">
        Menunggu scan...
    </div>

    <div class="footer">
        Smart Attendance System 🚀
    </div>

</div>

<script>
let scanned = false;

function onScanSuccess(decodedText) {

    if (scanned) return;
    scanned = true;

    let hasil = document.getElementById("hasil");

    hasil.className = "hasil-box loading";
    hasil.innerHTML = "🔄 Memproses: " + decodedText;

    fetch("http://127.0.0.1:8000/api/scan?qr=" + decodedText)
        .then(res => res.json())
        .then(data => {

            if (data.status === 'success') {
                hasil.className = "hasil-box success";
                hasil.innerHTML = "✅ " + data.message;
            } else {
                hasil.className = "hasil-box error";
                hasil.innerHTML = "❌ " + data.message;
            }

            // reset setelah 3 detik
            setTimeout(() => {
                scanned = false;
                hasil.className = "hasil-box";
                hasil.innerHTML = "Menunggu scan...";
            }, 3000);

        })
        .catch(err => {
            hasil.className = "hasil-box error";
            hasil.innerHTML = "❌ Gagal koneksi ke server";
        });
}

const html5QrCode = new Html5Qrcode("reader");

Html5Qrcode.getCameras().then(devices => {
    if (devices.length) {
        html5QrCode.start(
            devices[0].id,
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess
        );
    }
});
</script>

</body>
</html>
