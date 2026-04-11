<!DOCTYPE html>
<html>
<head>
    <title>Scanner QR Code</title>

    <!-- Library QR -->
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body style="text-align:center; margin-top:50px;">

    <h2>📡 Scanner QR Code</h2>

    <div id="reader" style="width:300px; margin:auto;"></div>

    <div id="result" style="margin-top:20px; font-size:18px;"></div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log("Hasil scan:", decodedText);

            document.getElementById('result').innerHTML =
                "Scanning... " + decodedText;

            // kirim ke backend
            fetch(`/api/scan?qr=${decodedText}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('result').innerHTML =
                            "✅ " + data.message;
                    } else {
                        document.getElementById('result').innerHTML =
                            "❌ " + data.message;
                    }
                });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: 250 }
        );

        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>
</html>
