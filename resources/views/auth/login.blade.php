<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Inventory PTM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts (optional untuk tampilan elegan) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: #0f172a;

            background-size: cover;
            background-position: center;
            color: #fff;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 110%;
            /* background-color: #0f172a3e; */
            background-image: url("{{ asset('IMG/ptm_company_logo.png') }}");
            background-size: cover;
            background-position: center;

            z-index: 1;
        }

        .login-container {
            height: 100vh;
            /* PENTING: supaya flex container tinggi layar penuh */
        }

        .login-box {
            position: relative;
            z-index: 2;
            background: rgba(217, 255, 0, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            max-width: 420px;
            width: 100%;
            margin: auto;
            /* margin-top dihapus supaya gak naik turun */
        }

        .login-box h4 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 25px;
            color: #ffffff;
        }

        .form-label {
            color: #ffffff;
        }

        .btn-custom {
            background-color: #1d4ed8;
            color: #fff;
            font-weight: 600;
            border: none;
        }

        .btn-custom:hover {
            background-color: #2563eb;
            color: #fff;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>

<body>

    <!-- Particles Background -->
    <div id="particles-js"></div>

    <div class="container login-container d-flex align-items-center justify-content-center">
        <div class="login-box">
            <h4>Mandala Bangunan</h4>
            <form method="POST" action="{{ route('auth.verify') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com"
                        required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="******"
                        required>
                </div>

                <button type="submit" class="btn btn-custom w-100">Masuk</button>
            </form>
        </div>
    </div>

    <!-- Particles.js Config -->
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": { "enable": true, "value_area": 800 }
                },
                "color": { "value": "#ffffff" },
                "shape": {
                    "type": "circle",
                    "stroke": { "width": 0, "color": "#000000" },
                    "polygon": { "nb_sides": 5 }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": { "enable": false }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": { "enable": false }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 3,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": { "enable": true, "mode": "repulse" },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "repulse": { "distance": 100, "duration": 0.4 },
                    "push": { "particles_nb": 4 }
                }
            },
            "retina_detect": true
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ session("error") }}',
            confirmButtonColor: '#1d4ed8'
        });
    </script>
@endif

</body>

</html>