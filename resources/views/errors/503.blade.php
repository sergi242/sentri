<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service indisponible — SENTEI</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #0a0e1a;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { text-align: center; padding: 40px 20px; }
        .logo {
            font-size: 80px;
            font-weight: 900;
            letter-spacing: 14px;
            color: #fff;
            text-shadow: 0 0 40px rgba(59,130,246,0.9), 0 0 80px rgba(59,130,246,0.4);
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 11px;
            letter-spacing: 4px;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 64px;
        }
        .icon {
            font-size: 56px;
            margin-bottom: 28px;
            display: inline-block;
            animation: blink 1.6s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }
        .title {
            font-size: 26px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 14px;
        }
        .message {
            font-size: 15px;
            color: #94a3b8;
            max-width: 460px;
            margin: 0 auto 36px;
            line-height: 1.8;
        }
        .badge {
            display: inline-block;
            background: #0f172a;
            border: 1px solid #1e3a5f;
            border-radius: 6px;
            padding: 7px 18px;
            font-size: 12px;
            color: #475569;
            letter-spacing: 2px;
            margin-bottom: 44px;
        }
        .contact-box {
            background: #0f172a;
            border: 1px solid #1d4ed8;
            border-radius: 14px;
            padding: 22px 36px;
            display: inline-block;
            margin-bottom: 32px;
        }
        .contact-box p { font-size: 12px; color: #64748b; margin-bottom: 6px; }
        .contact-box strong { color: #3b82f6; font-size: 15px; font-weight: 700; }
        .btn {
            display: inline-block;
            padding: 11px 28px;
            background: #1d4ed8;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .btn:hover { background: #2563eb; }
        .divider {
            width: 60px;
            height: 2px;
            background: linear-gradient(to right, transparent, #1d4ed8, transparent);
            margin: 0 auto 44px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">SENTRI</div>
        <div class="subtitle">Système d'Enregistrement National des Titres et Résidents Immigrés</div>
        <div class="divider"></div>

        <div class="icon">🔌</div>

        <div class="title">Connexion au serveur échouée</div>
        <div class="message">
            Le serveur central est actuellement inaccessible.<br>
            Vos données sont sécurisées. Veuillez contacter l'administrateur système.
        </div>

        <div class="badge">ERREUR 503 &mdash; SERVEUR INDISPONIBLE</div>
        <br>

        <div class="contact-box">
            <p>Veuillez contacter</p>
            <strong>l'administrateur système</strong>
        </div>

        <br>
        <a href="{{ url('/') }}" class="btn">&#8635; Réessayer</a>
    </div>
</body>
</html>
