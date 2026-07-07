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
    <canvas id="fireflies-canvas"></canvas>
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

        // Glowing Fireflies Background Animation
        (function() {
            const canvas = document.getElementById('fireflies-canvas');
            const ctx = canvas.getContext('2d');

            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });

            class Firefly {
                constructor() {
                    this.reset();
                    this.x = Math.random() * width;
                    this.y = Math.random() * height;
                }

                reset() {
                    this.x = Math.random() * width;
                    this.y = Math.random() * height;
                    this.radius = Math.random() * 1.6 + 0.8; // 0.8px to 2.4px
                    this.vx = (Math.random() - 0.5) * 0.25;
                    this.vy = (Math.random() - 0.5) * 0.25;
                    this.alpha = Math.random();
                    this.targetAlpha = Math.random() * 0.8 + 0.2;
                    this.alphaSpeed = Math.random() * 0.015 + 0.005;
                    
                    // Warm golden amber tones with a touch of soft blue matching the accent logo
                    const colors = [
                        'rgba(253, 224, 71, ', // Yellow/Gold
                        'rgba(245, 158, 11, ', // Amber
                        'rgba(251, 146, 60, ', // Orange
                        'rgba(96, 165, 250, '  // Soft light blue accent
                    ];
                    // 80% warm colors, 20% blue accent
                    this.colorBase = Math.random() > 0.2 
                        ? colors[Math.floor(Math.random() * 3)] 
                        : colors[3];
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;

                    // Wrap boundaries
                    if (this.x < -10 || this.x > width + 10 || this.y < -10 || this.y > height + 10) {
                        this.reset();
                        const edge = Math.floor(Math.random() * 4);
                        if (edge === 0) { this.x = -5; this.y = Math.random() * height; }
                        else if (edge === 1) { this.x = width + 5; this.y = Math.random() * height; }
                        else if (edge === 2) { this.x = Math.random() * width; this.y = -5; }
                        else { this.x = Math.random() * width; this.y = height + 5; }
                    }

                    // Opacity fluctuation (flicker)
                    if (Math.abs(this.alpha - this.targetAlpha) < 0.05) {
                        this.targetAlpha = Math.random() * 0.8 + 0.2;
                        this.alphaSpeed = Math.random() * 0.01 + 0.005;
                    }

                    if (this.alpha < this.targetAlpha) {
                        this.alpha += this.alphaSpeed;
                    } else {
                        this.alpha -= this.alphaSpeed;
                    }

                    this.alpha = Math.max(0.01, Math.min(1, this.alpha));
                }

                draw() {
                    ctx.beginPath();
                    
                    // Firefly glowing particle gradient
                    const gradient = ctx.createRadialGradient(
                        this.x, this.y, 0,
                        this.x, this.y, this.radius * 6
                    );
                    
                    gradient.addColorStop(0, this.colorBase + this.alpha + ')');
                    gradient.addColorStop(0.2, this.colorBase + (this.alpha * 0.4) + ')');
                    gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');
                    
                    ctx.fillStyle = gradient;
                    ctx.arc(this.x, this.y, this.radius * 6, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            const fireflies = [];
            // responsive density
            const density = Math.min(Math.floor((width * height) / 18000), 75);
            
            for (let i = 0; i < density; i++) {
                fireflies.push(new Firefly());
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);
                for (let i = 0; i < fireflies.length; i++) {
                    fireflies[i].update();
                    fireflies[i].draw();
                }
                requestAnimationFrame(animate);
            }

            animate();
        })();
    </script>
</body>
</html>
