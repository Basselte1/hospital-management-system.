<!DOCTYPE html>
<html lang="fr">
<head>
    <title>CMCU | Connexion</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('build/admin/images/faviconlogo.ico') }}" />

    {{-- Vite assets (Tailwind + app styles) --}}
    @vite(['resources/assets/sass/app.scss', 'resources/assets/sass/tailwind.css'])

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('vendor/css/all.min.css') }}">

    <style>
        /* Subtle animated mesh background */
        body { background-color: #0F2554; }

        .login-bg {
            background:
                radial-gradient(ellipse 80% 60% at 20% 30%, rgba(37,99,235,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 70%, rgba(20,184,166,0.2) 0%, transparent 55%),
                #0F2554;
        }

        /* Input focus override to match brand */
        .login-input:focus {
            outline: none;
            border-color: #1D4ED8;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
        }

        /* Password toggle */
        .password-wrapper { position: relative; }
        .password-wrapper .toggle-pw {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: color .15s;
        }
        .password-wrapper .toggle-pw:hover { color: #1D4ED8; }
    </style>
</head>
<body class="login-bg">
    @yield('content')
</body>
</html>