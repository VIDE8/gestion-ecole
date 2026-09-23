<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portail de Gestion Scolaire - C.S. L'AVENIR D'OR</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://googleapis.com" rel="stylesheet">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#006a4e">
    <link rel="icon" href="/icons/icon-192.png" type="image/png">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Avenir d'Or">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            background-color: #1a252f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://wikimedia.org') no-repeat center center;
            background-size: cover;
            opacity: 0.65;
            z-index: -1;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4) !important;
            background-color: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(5px);
            overflow: hidden;
            border-top: 6px solid #d21034 !important;
        }

        .badge-togo {
            background-color: #006a4e;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0, 106, 78, 0.2);
        }

        .main-title {
            color: #1a252f;
            font-weight: 700;
            font-size: 1.85rem;
            letter-spacing: -0.5px;
        }

        .school-badge-stylized {
            font-family: 'Playfair Display', serif;
            color: #b78a02;
            font-weight: 700;
            font-style: italic;
            font-size: 1.75rem;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 700;
            color: #4d5656;
            letter-spacing: 1px;
        }

        .form-control-custom {
            border: 2px solid #d5dbdb;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fcfcfc;
        }

        .form-control-custom:focus {
            border-color: #006a4e;
            background-color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(0, 106, 78, 0.15);
        }

        .btn-submit-custom {
            background: linear-gradient(90deg, #d21034 0%, #a60b25 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(210, 16, 52, 0.3);
        }

        .btn-submit-custom:hover {
            background: linear-gradient(90deg, #006a4e 0%, #118161 100%);
            color: #ffc107;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 106, 78, 0.3);
        }

        .footer-motto {
            color: #006a4e;
            font-style: italic;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card login-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="badge-togo mb-3">ENSEIGNEMENT PRIMAIRE - TOGO</span>
                        <h1 class="main-title mt-3 mb-1">Portail de Gestion Scolaire</h1>
                        <div class="school-badge-stylized">C.S. L'Avenir d'Or</div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label-custom">EMAIL PROFESSIONNEL</label>
                            <input id="email" type="email" name="email"
                                class="form-control form-control-custom @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="votre email" required autofocus
                                autocomplete="username">
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label-custom">MOT DE PASSE</label>
                            <input id="password" type="password" name="password"
                                class="form-control form-control-custom @error('password') is-invalid @enderror"
                                placeholder="votre mot de passe" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-submit-custom">Ouvrir la Session</button>
                        </div>
                    </form>

                    <div class="text-center mt-4 footer-motto">
                        ✨ Discipline - Travail - Succès ✨
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
