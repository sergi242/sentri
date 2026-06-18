<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMCE — Accès bloqué</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0a0a0f;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(124,58,237,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #00d4ff, #7c3aed);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 3px;
            background: linear-gradient(90deg, #00d4ff, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            font-size: 11px;
            color: #4a5568;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* CARD */
        .card {
            background: #111118;
            border: 1px solid #1e1e2e;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e74c3c, transparent);
        }

        /* TABS */
        .tabs {
            display: flex;
            border-bottom: 1px solid #1e1e2e;
        }

        .tab {
            flex: 1;
            padding: 14px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            color: #4a5568;
            background: transparent;
            border: none;
            transition: all 0.2s;
            position: relative;
        }

        .tab.active {
            color: #e2e8f0;
            background: rgba(255,255,255,0.03);
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, #00d4ff, #7c3aed);
        }

        .tab:hover:not(.active) { color: #a0aec0; }

        /* TAB CONTENT */
        .tab-content { display: none; padding: 28px; }
        .tab-content.active { display: block; }

        /* BLOCKED TAB */
        .blocked-icon {
            text-align: center;
            font-size: 52px;
            margin-bottom: 16px;
            animation: shake 0.5s ease 0.3s;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        .blocked-title {
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            color: #e74c3c;
            margin-bottom: 6px;
        }

        .blocked-subtitle {
            text-align: center;
            font-size: 12px;
            color: #4a5568;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .reason-box {
            background: rgba(231,76,60,0.08);
            border: 1px solid rgba(231,76,60,0.2);
            border-left: 3px solid #e74c3c;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .reason-box strong { color: #e74c3c; }

        .contact-info {
            text-align: center;
            font-size: 12px;
            color: #4a5568;
            line-height: 1.8;
        }

        .contact-info strong { color: #a0aec0; }

        /* GRACE SECTION */
        .grace-toggle {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background: transparent;
            border: 1px solid #2a2a3e;
            border-radius: 8px;
            color: #4a5568;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .grace-toggle:hover { border-color: #f39c12; color: #f39c12; }

        .grace-section {
            display: none;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #1e1e2e;
            animation: fadeIn 0.3s ease;
        }

        .grace-section.show { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .grace-warning {
            background: rgba(243,156,18,0.08);
            border: 1px solid rgba(243,156,18,0.2);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 11px;
            color: #f39c12;
            margin-bottom: 14px;
            line-height: 1.6;
        }

        /* ACTIVATE TAB */
        .activate-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 14px;
        }

        .activate-title {
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            color: #00d4ff;
            margin-bottom: 6px;
        }

        .activate-subtitle {
            text-align: center;
            font-size: 12px;
            color: #4a5568;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        /* FORMS */
        .form-group { margin-bottom: 14px; }

        .form-group label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #4a5568;
            margin-bottom: 6px;
            font-family: monospace;
        }

        .form-group input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255,255,255,0.04);
            border: 1px solid #1e1e2e;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 13px;
            font-family: monospace;
            letter-spacing: 1px;
            transition: all 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
            background: rgba(0,212,255,0.03);
        }

        .form-group input.key-input {
            font-size: 12px;
            text-transform: uppercase;
        }

        .btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 4px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #7c3aed);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,212,255,0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #1a1a2e;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(243,156,18,0.3);
        }

        /* ALERTS */
        .alert {
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 14px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }

        .alert-error {
            background: rgba(231,76,60,0.1);
            border: 1px solid rgba(231,76,60,0.3);
            color: #ff8888;
        }

        .alert-success {
            background: rgba(0,208,132,0.1);
            border: 1px solid rgba(0,208,132,0.3);
            color: #00d084;
        }

        .alert-warning {
            background: rgba(243,156,18,0.1);
            border: 1px solid rgba(243,156,18,0.3);
            color: #f39c12;
        }

        /* KEY FORMAT HINT */
        .key-format {
            font-size: 10px;
            color: #2a2a4e;
            font-family: monospace;
            text-align: center;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        /* DIVIDER */
        hr { border: none; border-top: 1px solid #1e1e2e; margin: 18px 0; }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 10px;
            color: #2a2a3e;
            letter-spacing: 2px;
            font-family: monospace;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo">
            <div class="logo-icon">🔐</div>
            <span class="logo-text">DMCE</span>
        </div>
        <p>Département des Migrations et du Contrôle des Étrangers</p>
    </div>

    <div class="card">
        <!-- TABS -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('blocked', this)">
                🔒 Accès bloqué
            </button>
            <button class="tab" onclick="switchTab('activate', this)">
                ⚡ Activer licence
            </button>
        </div>

        <!-- TAB : BLOQUÉ -->
        <div class="tab-content active" id="tab-blocked">

            @if(session('grace_error'))
                <div class="alert alert-error">⚠️ {{ session('grace_error') }}</div>
            @endif

            @if(session('activate_error'))
                <div class="alert alert-error">⚠️ {{ session('activate_error') }}</div>
            @endif

            <div class="blocked-icon">🔒</div>
            <div class="blocked-title">Accès bloqué</div>
            <div class="blocked-subtitle">Licence invalide ou expirée</div>

            <div class="reason-box">
                <strong>Raison :</strong> {{ $reason ?? 'Licence invalide ou expirée' }}
            </div>

            <div class="contact-info">
                Pour renouveler votre licence, contactez :<br>
                <strong>Lt ONDELE</strong> — Administrateur DMCE
            </div>

            <!-- PÉRIODE DE GRÂCE -->
            <button class="grace-toggle" onclick="document.getElementById('graceSection').classList.toggle('show')">
                🕐 Période de grâce (72h) — usage unique
            </button>

            <div class="grace-section" id="graceSection">
                <div class="grace-warning">
                    ⚠️ Utilisable <strong>une seule fois</strong>. Nécessite un compte Admin ou SuperAdmin.
                </div>
                <form method="POST" action="/license/grace">
                    @csrf
                    <div class="form-group">
                        <label>Email Admin</label>
                        <input type="email" name="email" placeholder="admin@dmce.cg" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-warning">🔓 Activer 72h de grâce</button>
                </form>
            </div>
        </div>

        <!-- TAB : ACTIVER LICENCE -->
        <div class="tab-content" id="tab-activate">

            @if(session('activate_error'))
                <div class="alert alert-error">⚠️ {{ session('activate_error') }}</div>
            @endif

            @if(session('activate_success'))
                <div class="alert alert-success">✅ {{ session('activate_success') }}</div>
            @endif

            <div class="activate-icon">⚡</div>
            <div class="activate-title">Activer une licence</div>
            <div class="activate-subtitle">
                Saisissez la clé reçue de l'administrateur.<br>
                Format : <code style="color:#00d4ff;font-size:11px;">DMCE-YYYY-XXXXX-XXXXX-XXXXX-XXXXX-XXXX</code>
            </div>

            <form method="POST" action="/license/activate-client">
                @csrf
                <div class="form-group">
                    <label>Clé de licence</label>
                    <input
                        type="text"
                        name="license_key"
                        class="key-input"
                        placeholder="DMCE-2026-XXXXX-XXXXX-XXXXX-XXXXX-XXXX"
                        required
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="characters"
                        spellcheck="false"
                        oninput="this.value = this.value.toUpperCase()"
                    >
                    <div class="key-format">La clé vous a été fournie par Lt ONDELE</div>
                </div>

                <button type="submit" class="btn btn-primary">⚡ Activer la licence</button>
            </form>
        </div>
    </div>

    <div class="footer">DMCE — République du Congo — Ministère de l'Intérieur</div>
</div>

<script>
function switchTab(name, el) {
    // Désactiver tous les onglets
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

    // Activer l'onglet sélectionné
    el.classList.add('active');
    document.getElementById('tab-' + name).classList.add('active');
}

// Si erreur d'activation, ouvrir automatiquement l'onglet activation
@if(session('activate_error') || request()->is('*license*'))
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');
        switchTab('activate', tabs[1]);
    });
@endif
</script>
</body>
</html>
