<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se Connecter — DMCE</title>
    <link rel="apple-touch-icon" href="{{asset('logo.png')}}">
    <link rel="shortcut icon" type="image/png" href="{{asset('logo.png')}}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: url('{{ asset("img/login-bg.jpg") }}') center center / cover no-repeat;
            filter: brightness(0.55) saturate(1.1);
            z-index: -2;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(160deg, rgba(10,14,26,0.85) 0%, rgba(15,23,42,0.6) 50%, rgba(10,14,26,0.9) 100%);
            z-index: -1;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
            color: #fff;
        }

        .brand .badge-title {
            font-size: 11px;
            letter-spacing: 3px;
            color: #93c5fd;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .brand h1 {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.4;
            text-shadow: 0 2px 12px rgba(0,0,0,0.5);
        }

        .brand .subtitle {
            font-size: 12px;
            color: #cbd5e1;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        .card {
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .card-title {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
        }

        .form-group {
            position: relative;
            margin-bottom: 18px;
        }

        .form-group .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 16px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px 13px 42px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            color: #f1f5f9;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .form-control::placeholder { color: #64748b; }

        .form-control:focus {
            border-color: #3b82f6;
            background: rgba(255,255,255,0.09);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            color: #fca5a5;
            font-size: 12px;
            margin-top: 6px;
            display: block;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 8px;
        }

        .btn-login:hover { opacity: 0.92; }

        .footer-note {
            text-align: center;
            margin-top: 22px;
            font-size: 11px;
            color: #64748b;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="brand">
            <div class="badge-title">République du Congo</div>
            <h1>CID<br>DÉPARTEMENT DES MIGRATIONS<br>ET DU CONTRÔLE DES ÉTRANGERS</h1>
            <div class="subtitle">Unité · Travail · Progrès</div>
        </div>

        <div class="card">
            <div class="card-title">Connexion à votre espace</div>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="form-group">
                    <span class="icon">&#9993;</span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Votre email" required autofocus>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <span class="icon">&#128274;</span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Votre mot de passe" required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Se connecter</button>
            </form>
        </div>

        <div class="footer-note">SENTRI — Système sécurisé</div>
    </div>
</body>
</html>
