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
    <link rel="icon" href="/icon-192.png" type="image/png">
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Avenir d'Or">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            background-color: #1a252f;
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
            box-shadow: 0 8px 20px rgba(0, 106, 78, 0.3)
