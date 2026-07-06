<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - DiAbsen+</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .quick-login-title {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            text-align: center;
        }
        .quick-login-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
        .btn-quick {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.75rem;
            padding: 0.6rem 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            font-family: var(--font-sans);
            font-weight: 500;
        }
        .btn-quick:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="bg-glow-secondary"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">DiAbsen<span style="color: #3b82f6;">+</span></div>
                <div class="login-subtitle">Sistem Absensi Guru & Staf Berbasis QR Code</div>
            </div>

            @if ($errors->any())
                <div class="alert">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 0.5rem; flex-shrink: 0;"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nama@sekolah.sch.id" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Masuk ke Aplikasi</button>
            </form>

            <div class="quick-login-title">Pilih Akun Demo (Quick Login)</div>
            <div class="quick-login-grid">
                <button type="button" class="btn-quick" onclick="fillLogin('guru@example.com')">Guru</button>
                <button type="button" class="btn-quick" onclick="fillLogin('piket@example.com')">Admin Piket</button>
                <button type="button" class="btn-quick" onclick="fillLogin('tu@example.com')">Tata Usaha</button>
                <button type="button" class="btn-quick" onclick="fillLogin('kepala@example.com')">Kepala Sekolah</button>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
