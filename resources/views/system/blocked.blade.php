<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMCE — Erreur système</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:"Segoe UI",sans-serif;background:#0a0a0f;color:#e2e8f0;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
        body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(255,68,68,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,68,68,0.03) 1px,transparent 1px);background-size:40px 40px;pointer-events:none}
        .container{width:100%;max-width:480px;position:relative;z-index:1}
        .card{background:#111118;border:1px solid #2a1010;border-radius:16px;overflow:hidden}
        .card::before{content:'';display:block;height:1px;background:linear-gradient(90deg,transparent,#e74c3c,transparent)}
        .header{text-align:center;padding:28px 28px 0}
        .icon{font-size:52px;margin-bottom:14px}
        .title{font-size:20px;font-weight:800;color:#e74c3c;margin-bottom:6px}
        .subtitle{font-size:12px;color:#4a5568;letter-spacing:1px}
        .body{padding:24px 28px}
        .error-box{background:rgba(231,76,60,0.08);border:1px solid rgba(231,76,60,0.2);border-left:3px solid #e74c3c;border-radius:8px;padding:12px 16px;font-family:monospace;font-size:13px;margin-bottom:20px}
        .error-box .label{font-size:10px;color:#e74c3c;letter-spacing:2px;text-transform:uppercase;margin-bottom:4px}
        .error-box .code{font-size:18px;font-weight:700;color:#ff8888}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:20px}
        .info-item{background:rgba(255,255,255,0.02);border:1px solid #1e1e2e;border-radius:8px;padding:10px 12px}
        .info-label{font-size:10px;color:#4a5568;font-family:monospace;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px}
        .info-value{font-size:12px;font-weight:700;color:#a0aec0;font-family:monospace}
        .contact{text-align:center;font-size:12px;color:#4a5568;line-height:1.8;margin-bottom:20px}
        .contact strong{color:#a0aec0}
        .unlock-toggle{display:block;width:100%;padding:10px;background:transparent;border:1px solid #2a2a3e;border-radius:8px;color:#4a5568;font-size:11px;cursor:pointer;transition:all .2s;text-align:center;font-family:inherit;margin-bottom:0}
        .unlock-toggle:hover{border-color:#00d4ff;color:#00d4ff}
        .unlock-section{display:none;margin-top:16px;padding-top:16px;border-top:1px solid #1e1e2e}
        .unlock-section.show{display:block}
        .lbl{display:block;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#4a5568;margin-bottom:6px;font-family:monospace}
        .inp{width:100%;padding:11px 14px;background:rgba(255,255,255,0.04);border:1px solid #1e1e2e;border-radius:8px;color:#e2e8f0;font-size:14px;font-family:monospace;letter-spacing:2px;text-transform:uppercase;outline:none;margin-bottom:10px}
        .inp:focus{border-color:#00d4ff;box-shadow:0 0 0 3px rgba(0,212,255,0.1)}
        .btn{width:100%;padding:12px;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00d4ff,#7c3aed);color:white;font-family:inherit}
        .btn:hover{opacity:0.9}
        @if(session('unlock_error'))
        .alert-error{background:rgba(231,76,60,0.1);border:1px solid rgba(231,76,60,0.3);border-radius:8px;padding:10px 14px;font-size:12px;color:#ff8888;margin-bottom:12px}
        @endif
        .footer{text-align:center;margin-top:16px;font-size:10px;color:#2a2a3e;font-family:monospace;letter-spacing:2px}
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <div class="icon">⚠️</div>
            <div class="title">Erreur système critique</div>
            <div class="subtitle">DMCE — Vérification d'intégrité échouée</div>
        </div>
        <div class="body">
            @if(session('unlock_error'))
            <div class="alert-error">⚠️ {{ session('unlock_error') }}</div>
            @endif

            <div class="error-box">
                <div class="label">Code d'erreur</div>
                <div class="code">{{ $error_code ?? session('error_code', 'E000') }}</div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Serveur</div>
                    <div class="info-value">{{ $info['hostname'] ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date</div>
                    <div class="info-value">{{ $info['date'] ?? now()->format('d/m/Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Machine ID</div>
                    <div class="info-value">{{ $info['machine_id'] ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Statut</div>
                    <div class="info-value" style="color:#e74c3c">BLOQUÉ</div>
                </div>
            </div>

            <div class="contact">
                Contactez l'administrateur système pour débloquer.<br>
                <strong>Lt ONDELE</strong> — Communiquez le code d'erreur ci-dessus.
            </div>

            <button class="unlock-toggle" onclick="document.getElementById('unlockSection').classList.toggle('show')">
                🔑 Saisir le code de déblocage
            </button>

            <div class="unlock-section" id="unlockSection">
                <form method="POST" action="/system/unlock">
                    @csrf
                    <label class="lbl">Code de déblocage (fourni par Lt ONDELE)</label>
                    <input type="text" name="unlock_code" class="inp" placeholder="XXXXXXXX" maxlength="8" autocomplete="off" oninput="this.value=this.value.toUpperCase()">
                    <button type="submit" class="btn">🔓 Débloquer le système</button>
                </form>
            </div>
        </div>
    </div>
    <div class="footer">DMCE — République du Congo — Ministère de l'Intérieur</div>
</div>
</body>
</html>
