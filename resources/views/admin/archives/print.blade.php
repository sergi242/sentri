<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivage — {{ $archive->typeLabel() }} — {{ $impetrant->nom }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: white; color: #1a202c; padding: 30px; }

        .header {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding-bottom: 20px; border-bottom: 3px solid #4834d4; margin-bottom: 25px;
        }
        .header-logo h2 { color: #4834d4; font-size: 1.5rem; font-weight: 800; }
        .header-logo p  { color: #64748b; font-size: 12px; margin-top: 2px; }
        .header-meta    { text-align: right; font-size: 11px; color: #94a3b8; }

        .doc-title {
            background: linear-gradient(135deg, #4834d4, #6c63ff);
            color: white; border-radius: 12px; padding: 16px 20px;
            margin-bottom: 20px; display: flex; align-items: center; gap: 12px;
        }
        .doc-title h3 { font-size: 1.2rem; font-weight: 700; margin: 0; }
        .doc-title p  { font-size: 12px; opacity: 0.8; margin: 4px 0 0; }

        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;
        }
        .info-block {
            background: #f8fafc; border-radius: 10px; padding: 14px;
            border-left: 4px solid #4834d4;
        }
        .info-block h4 { font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; }
        .info-row span:first-child { color: #64748b; }
        .info-row span:last-child  { font-weight: 600; }

        .doc-image-wrap {
            text-align: center; margin: 20px 0;
            border: 2px dashed #e2e8f0; border-radius: 12px; padding: 16px;
            background: #f8fafc;
        }
        .doc-image-wrap img { max-width: 100%; max-height: 500px; border-radius: 8px; }
        .doc-image-wrap .pdf-notice {
            padding: 40px; color: #64748b; font-size: 14px;
        }

        .footer {
            margin-top: 30px; padding-top: 20px; border-top: 2px solid #edf2f9;
            display: flex; justify-content: space-between; align-items: flex-end;
        }
        .footer-stamp {
            width: 120px; height: 80px; border: 2px dashed #cbd5e1;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            color: #94a3b8; font-size: 11px; text-align: center;
        }
        .footer-sig { text-align: right; font-size: 12px; color: #64748b; }
        .footer-sig strong { display: block; color: #1a202c; margin-top: 4px; }

        .watermark {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 5rem; font-weight: 900; color: rgba(72, 52, 212, 0.04);
            pointer-events: none; white-space: nowrap; z-index: 0;
        }

        .expired-banner {
            background: #fef2f2; border: 2px solid #fecaca; color: #dc2626;
            border-radius: 10px; padding: 10px 16px; margin-bottom: 16px;
            font-weight: 700; font-size: 13px; text-align: center;
        }

        @media print {
            body { padding: 15px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="watermark">DMCE</div>

{{-- Bouton imprimer --}}
<div class="no-print" style="margin-bottom:20px;display:flex;gap:10px;">
    <button onclick="window.print()"
            style="background:#4834d4;color:white;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-size:14px;">
        🖨️ Imprimer
    </button>
    <button onclick="window.history.back()"
            style="background:#f1f5f9;color:#475569;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-size:14px;">
        ← Retour
    </button>
</div>

{{-- En-tête --}}
<div class="header">
    <div class="header-logo">
        <h2>DMCE</h2>
        <p>Département des Migrations et du Contrôle des Étrangers</p>
        <p style="margin-top:4px;color:#4834d4;font-weight:600;">Document d'archivage officiel</p>
    </div>
    <div class="header-meta">
        <div>Réf. archive : #{{ str_pad($archive->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div>Imprimé le : {{ now()->format('d/m/Y à H:i') }}</div>
        <div>Par : {{ auth()->user()->prenom ?? '' }} {{ auth()->user()->nom ?? '' }}</div>
    </div>
</div>

{{-- Titre document --}}
<div class="doc-title">
    <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        📄
    </div>
    <div>
        <h3>{{ $archive->typeLabel() }}</h3>
        <p>Document archivé pour {{ $impetrant->nom }} {{ $impetrant->prenom }}</p>
    </div>
</div>

{{-- Bannière expiration --}}
@if($archive->estExpire())
<div class="expired-banner">
    ⚠️ ATTENTION — Ce document est expiré depuis le {{ $archive->date_expiration->format('d/m/Y') }}
</div>
@endif

{{-- Grille infos --}}
<div class="info-grid">
    <div class="info-block">
        <h4>Informations de l'impétrant</h4>
        <div class="info-row">
            <span>Nom complet</span>
            <span>{{ strtoupper($impetrant->nom) }} {{ $impetrant->prenom }}</span>
        </div>
        <div class="info-row">
            <span>Nationalité</span>
            <span>{{ $impetrant->pays?->lib_pays ?? '—' }}</span>
        </div>
        @if($impetrant->date_naissance)
        <div class="info-row">
            <span>Date de naissance</span>
            <span>{{ \Carbon\Carbon::parse($impetrant->date_naissance)->format('d/m/Y') }}</span>
        </div>
        @endif
    </div>

    <div class="info-block">
        <h4>Informations du document</h4>
        @if($archive->numero_document)
        <div class="info-row">
            <span>Numéro</span>
            <span>{{ $archive->numero_document }}</span>
        </div>
        @endif
        @if($archive->date_emission)
        <div class="info-row">
            <span>Date d'émission</span>
            <span>{{ $archive->date_emission->format('d/m/Y') }}</span>
        </div>
        @endif
        @if($archive->date_expiration)
        <div class="info-row">
            <span>Date d'expiration</span>
            <span style="{{ $archive->estExpire() ? 'color:#dc2626;font-weight:700;' : '' }}">
                {{ $archive->date_expiration->format('d/m/Y') }}
            </span>
        </div>
        @endif
        <div class="info-row">
            <span>Archivé le</span>
            <span>{{ $archive->created_at->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span>Archivé par</span>
            <span>{{ $archive->user?->prenom }} {{ $archive->user?->nom }}</span>
        </div>
    </div>
</div>

@if($archive->notes)
<div style="background:#fffbeb;border-radius:10px;padding:14px;margin-bottom:16px;border-left:4px solid #f59e0b;">
    <strong style="font-size:11px;color:#92400e;text-transform:uppercase;">Notes</strong>
    <p style="font-size:13px;color:#78350f;margin-top:6px;">{{ $archive->notes }}</p>
</div>
@endif

{{-- Image du document --}}
<div class="doc-image-wrap">
    @php $isPdf = str_ends_with(strtolower($archive->chemin_fichier), '.pdf'); @endphp
    @if($isPdf)
    <div class="pdf-notice">
        <div style="font-size:3rem;">📄</div>
        <p style="margin-top:8px;">Document PDF — {{ $archive->nom_original }}</p>
        <a href="{{ asset('storage/'.$archive->chemin_fichier) }}" target="_blank"
           style="color:#4834d4;font-size:12px;">Ouvrir le PDF</a>
    </div>
    @else
    <img src="{{ asset('storage/'.$archive->chemin_fichier) }}" alt="{{ $archive->typeLabel() }}">
    @endif
</div>

{{-- Pied de page --}}
<div class="footer">
    <div>
        <p style="font-size:11px;color:#94a3b8;max-width:300px;">
            Ce document a été archivé par le DMCE et constitue une copie officielle.
            En cas de perte du document original, ce certificat d'archivage peut être présenté aux autorités compétentes.
        </p>
    </div>
    <div style="display:flex;gap:20px;align-items:flex-end;">
        <div class="footer-stamp">Cachet officiel</div>
        <div class="footer-sig">
            Signature de l'agent<br>
            <strong>{{ $archive->user?->prenom }} {{ $archive->user?->nom }}</strong>
            <div style="margin-top:30px;border-top:1px solid #cbd5e1;padding-top:4px;width:150px;">Signature</div>
        </div>
    </div>
</div>

</body>
</html>