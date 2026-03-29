<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Smart Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); background: #ffffff; }
        .btn-login { background: #1abc9c; border: none; padding: 12px; font-weight: bold; border-radius: 10px; color: white; transition: 0.3s; }
        .btn-login:hover { background: #16a085; transform: translateY(-2px); }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #ddd; }
        .form-label { font-weight: 600; color: #555; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card login-card p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">SMART ATTENDANCE</h3>
                        <p class="text-muted small">Multi-Device IoT Integration System</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger p-2 small">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">PASSWORD</label>
                            <input type="password" name="password" class="form-control" placeholder="********" required>
                        </div>
                        <button type="submit" class="btn btn-login w-100 mb-3">MASUK KE DASHBOARD</button>
                    </form>

                    <div class="text-center">
                        <small class="text-muted">PBL-TRPL-403 &copy; 2026</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
