<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMCE — Licence invalide</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1a1a2e;
            color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            padding: 50px;
            background: #16213e;
            border-radius: 12px;
            border: 1px solid #e74c3c;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 0 40px rgba(231,76,60,0.3);
        }
        .icon { font-size: 72px; margin-bottom: 20px; }
        h1 { font-size: 24px; color: #e74c3c; margin-bottom: 10px; }
        .subtitle { font-size: 14px; color: #aaa; margin-bottom: 25px; }
        .reason {
            background: #0f3460;
            border-left: 4px solid #e74c3c;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 30px;
            text-align: left;
        }
        .info {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            line-height: 1.8;
        }
        .badge {
            display: inline-block;
            background: #e74c3c;
            color: white;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        .grace-btn {
            background: none;
            border: 1px solid #555;
            color: #888;
            font-size: 12px;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s;
        }
        .grace-btn:hover { border-color: #f39c12; color: #f39c12; }
        .grace-form {
            display: none;
            margin-top: 20px;
            text-align: left;
        }
        .grace-form.show { display: block; }
        .form-group { margin-bottom: 12px; }
        .form-group label { font-size: 12px; color: #aaa; display: block; margin-bottom: 4px; }
        .form-group input {
            width: 100%;
            padding: 9px 12px;
            background: #0f3460;
            border: 1px solid #333;
            border-radius: 6px;
            color: #eee;
            font-size: 13px;
        }
        .form-group input:focus { outline: none; border-color: #f39c12; }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #f39c12;
            color: #1a1a2e;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
        }
        .submit-btn:hover { background: #e67e22; }
        .alert-error {
            background: rgba(231,76,60,0.15);
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .alert-warning {
            background: rgba(243,156,18,0.15);
            border: 1px solid #f39c12;
            color: #f39c12;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .divider {
            border: none;
            border-top: 1px solid #2a2a4a;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔒</div>
        <div class="badge">ACCÈS BLOQUÉ</div>
        <h1>Licence DMCE invalide</h1>
        <p class="subtitle">Département des Migrations et du Contrôle des Étrangers</p>

        <div class="reason">
            <strong>Raison :</strong> {{ $reason ?? 'Licence invalide ou expirée' }}
        </div>

        @if(session('grace_error'))
            <div class="alert-error">⚠️ {{ session('grace_error') }}</div>
        @endif

        <p class="info">
            Pour renouveler votre licence, contactez l'administrateur système.<br>
            <strong>Lt ONDELE</strong> — DMCE Administration
        </p>

        <hr class="divider">

        <button class="grace-btn" onclick="document.getElementById('graceForm').classList.toggle('show')">
            🕐 Demander une période de grâce (48h)
        </button>

        <div class="grace-form" id="graceForm">
            <div class="alert-warning">
                ⚠️ Cette option est utilisable <strong>une seule fois</strong>.<br>
                Elle nécessite les identifiants d'un compte Admin.
            </div>

            <form method="POST" action="/license/grace">
                @csrf
                <div class="form-group">
                    <label>Email Admin</label>
                    <input type="text" name="email" placeholder="Email de l'admin" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="submit-btn">🔓 Activer 48h de grâce</button>
            </form>
        </div>
    </div>
</body>
</html>
