<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #000; margin: 0; padding: 0; }
        .page { padding: 20mm 15mm; }

        /* En-tête officiel */
        .entete { text-align: center; margin-bottom: 10mm; border-bottom: 2px solid #000; padding-bottom: 5mm; }
        .entete .republique { font-size: 9pt; text-transform: uppercase; }
        .entete .ministere  { font-size: 10pt; font-weight: bold; text-transform: uppercase; }
        .entete .departement{ font-size: 9pt; }
        .entete .logo { width: 25mm; margin: 0 auto 5mm; display: block; }
        .entete .devise { font-size: 8pt; font-style: italic; }

        /* Titre du document */
        .titre-doc {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            border: 2px solid #000;
            padding: 5mm;
            margin: 8mm 0;
            letter-spacing: 1px;
        }
        .numero-cert {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 8mm;
        }

        /* Sections */
        .section { margin-bottom: 6mm; }
        .section-title {
            background: #f0f0f0;
            border-left: 4px solid #333;
            padding: 2mm 4mm;
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }
        .info-row { display: flex; margin-bottom: 2mm; }
        .info-label { width: 45mm; font-weight: bold; font-size: 10pt; color: #555; flex-shrink: 0; }
        .info-value { font-size: 10pt; border-bottom: 1px dotted #999; flex: 1; padding-bottom: 1mm; }

        /* Pied de page */
        .signature-zone { margin-top: 15mm; }
        .signature-box {
            border: 1px solid #ccc;
            padding: 4mm;
            min-height: 30mm;
            width: 60mm;
            float: right;
            text-align: center;
        }
        .signature-label { font-size: 9pt; font-weight: bold; }
        .tampon-zone { margin-top: 25mm; text-align: center; font-size: 9pt; color: #666; }

        .clearfix::after { content:''; display:table; clear:both; }
        .text-center { text-align: center; }
        .mt-5mm { margin-top: 5mm; }
        .validity-banner {
            border: 1px solid #999;
            padding: 3mm 5mm;
            font-size: 9pt;
            background: #fafafa;
            margin-top: 5mm;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- En-tête officiel --}}
    <div class="entete">
        <div class="republique">République du Congo</div>
        <img src="{{ public_path('img/congo.png') }}" class="logo" alt="Armoiries">
        <div class="ministere">Ministère de l'Intérieur, de la Décentralisation<br>et du Développement Local</div>
        <div class="departement">Département des Migrations et du Contrôle des Étrangers (DMCE)</div>
        <div class="devise">Unité — Travail — Progrès</div>
    </div>

    {{-- Titre --}}
    <div class="titre-doc">Certificat d'Hébergement</div>
    <div class="numero-cert">N° {{ $certificat->numero_certificat }}</div>

    {{-- Texte d'introduction --}}
    <p style="text-align:justify; font-size:10pt; margin-bottom:6mm;">
        Le soussigné, Chef du Département des Migrations et du Contrôle des Étrangers,
        certifie que la personne désignée ci-après a déclaré héberger l'étranger mentionné
        sur son territoire de résidence, conformément aux dispositions légales en vigueur.
    </p>

    {{-- HÉBERGEUR --}}
    <div class="section">
        <div class="section-title">I. Informations sur l'Hébergeur</div>

        @if($certificat->hebergeur_type === 'Congolais' && $certificat->hebergeurCongolais)
        @php $heb = $certificat->hebergeurCongolais; @endphp
        <div class="info-row"><span class="info-label">Code hébergeur :</span><span class="info-value">{{ $heb->code_hebergeur }}</span></div>
        <div class="info-row"><span class="info-label">Nom et prénom :</span><span class="info-value">{{ strtoupper($heb->nom) }} {{ $heb->prenom }}</span></div>
        <div class="info-row"><span class="info-label">Sexe :</span><span class="info-value">{{ $heb->sexe }}</span></div>
        <div class="info-row"><span class="info-label">Date de naissance :</span><span class="info-value">{{ $heb->date_naissance ? \Carbon\Carbon::parse($heb->date_naissance)->format('d/m/Y') : '—' }}</span></div>
        <div class="info-row"><span class="info-label">Lieu de naissance :</span><span class="info-value">{{ $heb->lieu_naissance ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Nationalité :</span><span class="info-value">Congolaise</span></div>
        <div class="info-row"><span class="info-label">Pièce d'identité :</span><span class="info-value">{{ $heb->type_piece ?? '—' }} N° {{ $heb->numero_piece ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Adresse :</span><span class="info-value">
            {{ $heb->avenue_rue }}, N°{{ $heb->numero_adresse }}
            @if($heb->quartier) — {{ $heb->quartier->lib_quartier }} / {{ $heb->quartier->arrondissement?->lib_arrondissement }} @endif
        </span></div>
        <div class="info-row"><span class="info-label">Téléphone :</span><span class="info-value">{{ $heb->telephone }}</span></div>

        @elseif($certificat->hebergeur_type === 'Etranger' && $certificat->hebergeurEtranger)
        @php $heb = $certificat->hebergeurEtranger; @endphp
        <div class="info-row"><span class="info-label">Code hébergeur :</span><span class="info-value">{{ $heb->code_hebergeur }}</span></div>
        <div class="info-row"><span class="info-label">Nom et prénom :</span><span class="info-value">{{ strtoupper($heb->nom) }} {{ $heb->prenom }}</span></div>
        <div class="info-row"><span class="info-label">Date de naissance :</span><span class="info-value">{{ $heb->date_naissance ? \Carbon\Carbon::parse($heb->date_naissance)->format('d/m/Y') : '—' }}</span></div>
        <div class="info-row"><span class="info-label">Nationalité :</span><span class="info-value">{{ $heb->pays?->lib_pays ?? '—' }}</span></div>

        @elseif($certificat->hebergeur_type === 'Societe' && $certificat->hebergeurSociete)
        @php $heb = $certificat->hebergeurSociete; @endphp
        <div class="info-row"><span class="info-label">Code hébergeur :</span><span class="info-value">{{ $heb->code_hebergeur }}</span></div>
        <div class="info-row"><span class="info-label">Raison sociale :</span><span class="info-value">{{ $heb->nom_employeur }}</span></div>
        <div class="info-row"><span class="info-label">Adresse :</span><span class="info-value">{{ $heb->adresse_physique ?? '—' }}</span></div>
        @endif
    </div>

    {{-- HÉBERGÉ --}}
    <div class="section">
        <div class="section-title">II. Informations sur l'Hébergé</div>
        @if($certificat->heberge)
        @php $hbe = $certificat->heberge; @endphp
        <div class="info-row"><span class="info-label">Nom et prénom :</span><span class="info-value">{{ strtoupper($hbe->nom) }} {{ $hbe->prenom }}</span></div>
        <div class="info-row"><span class="info-label">Sexe :</span><span class="info-value">{{ $hbe->sexe ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Date de naissance :</span><span class="info-value">{{ $hbe->date_naissance ? \Carbon\Carbon::parse($hbe->date_naissance)->format('d/m/Y') : '—' }}</span></div>
        <div class="info-row"><span class="info-label">Lieu de naissance :</span><span class="info-value">{{ $hbe->lieu_naissance ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Nationalité :</span><span class="info-value">{{ $hbe->pays?->lib_pays ?? '—' }}</span></div>
        @else
        <p style="color:#666; font-size:10pt;">Non renseigné</p>
        @endif
    </div>

    {{-- SÉJOUR --}}
    <div class="section">
        <div class="section-title">III. Conditions du Séjour</div>
        <div class="info-row"><span class="info-label">Date d'arrivée :</span><span class="info-value">{{ $certificat->date_arrivee_prevue?->format('d/m/Y') ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Date de départ :</span><span class="info-value">{{ $certificat->date_depart_prevue?->format('d/m/Y') ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Durée du séjour :</span><span class="info-value">{{ $certificat->duree_sejour_jours ?? '—' }} jours</span></div>
        <div class="info-row"><span class="info-label">Lien de parenté :</span><span class="info-value">{{ $certificat->type_relation ?? '—' }}{{ $certificat->precision_relation ? ' — '.$certificat->precision_relation : '' }}</span></div>
        @if($certificat->motif_sejour)
        <div class="info-row"><span class="info-label">Motif du séjour :</span><span class="info-value">{{ $certificat->motif_sejour }}</span></div>
        @endif
    </div>

    {{-- Validité --}}
    <div class="validity-banner">
        <strong>Date d'émission :</strong> {{ $certificat->date_emission?->format('d/m/Y') ?? date('d/m/Y') }}
        &nbsp;&nbsp;&nbsp;
        <strong>Valable jusqu'au :</strong> {{ $certificat->date_expiration?->format('d/m/Y') ?? '—' }}
        &nbsp;&nbsp;&nbsp;
        <strong>Statut :</strong> {{ $certificat->statut }}
    </div>

    {{-- Signature --}}
    <div class="signature-zone clearfix mt-5mm">
        <div style="float:left; font-size:9pt;">
            <p>Fait à Brazzaville, le {{ $certificat->date_emission?->format('d/m/Y') ?? date('d/m/Y') }}</p>
            <p style="margin-top:5mm;">
                <strong>Signé par :</strong><br>
                Lieutenant {{ $certificat->validateur?->prenom ?? '' }} {{ $certificat->validateur?->nom ?? 'ONDELE' }}<br>
                Chef du DMCE
            </p>
        </div>
        <div class="signature-box">
            <div class="signature-label">Signature &amp; Cachet</div>
            <div style="height:20mm;"></div>
        </div>
    </div>

    <div class="tampon-zone" style="margin-top:20mm; border-top:1px solid #ccc; padding-top:3mm;">
        <small>
            Ce certificat est délivré sur déclaration de l'hébergeur et ne vaut pas autorisation de séjour.<br>
            DMCE — Ministère de l'Intérieur — République du Congo — {{ date('Y') }}
        </small>
    </div>

</div>
</body>
</html>