@extends('admin.layouts.app')

@section('title')
    Impression CRT - {{ $status }}
@endsection

@section('styles')
    <style>
        /* --- Conteneur principal pour l'effet Flip --- */
        .card-container {
            width: 500px;
            height: 315px;
            margin: 0 auto 30px auto;
            perspective: 1000px;
        }

        .crt-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            cursor: pointer;
        }

        .card-container:hover .crt-card-inner {
            transform: rotateY(180deg);
        }

        .crt-card-front, .crt-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .crt-card-back {
            transform: rotateY(180deg);
            background-image: url("{{ asset('img/crt/verso.png') }}");
            background-size: 100% 100%;
        }

        .crt-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("{{ asset('img/crt/recto.png') }}");
            background-size: 100% 100%;
            z-index: 1;
        }

       .crt-photo {
    position: absolute;
    /* On ajuste la position pour bien centrer dans le cadre après réduction */
    top: 95px; 
    left: 30px; 
    
    /* On réduit légèrement la taille (ajuste selon ton besoin) */
    width: 110px; 
    height: 135px; 
    
    object-fit: cover; 
    z-index: 5;

    /* Effet fondu (vignettage) sur les contours */
    /* Le centre est opaque (black), les bords deviennent transparents */
    -webkit-mask-image: radial-gradient(circle, black 60%, transparent 95%);
    mask-image: radial-gradient(circle, black 60%, transparent 95%);
    
    /* Optionnel : un léger flou sur les bords pour adoucir encore plus */
    filter: contrast(1.1); /* Redonne un peu de peps après le fondu */
}

        .crt-data-container {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 10;
        }

        .crt-text {
            position: absolute;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 15px;
            color: #000;
            white-space: nowrap;
        }
.card-actions {
        margin-bottom: 10px;
        text-align: center;
    }

    .link-display {
        font-size: 10px;
        color: #666;
        word-break: break-all;
        display: block;
        margin-bottom: 5px;
    }
        /* --- POSITIONS RECTO --- */
        .val-nom { top: 92px; left: 170px; font-size: 15px; }
        .val-doc-num { top: 75px; left: 363px; font-family: arial; font-size: 15px; }
        .val-prenom { top: 128px; left: 170px; }
        .val-sexe { top: 162px; left: 170px; }
        .val-nationalite { top: 195px; left: 170px; font-size: 15px; }
        .val-date-naiss { top: 228px; left: 170px; }
        .val-lieu-naiss { top: 264px; left: 170px; }

        /* --- POSITIONS VERSO --- */
        .val-emission { top: 75px; left: 60px; }
        .val-expiration { top: 110px; left: 60px; }
        .val-profession { top: 140px; left: 60px; }
.val-adresse { 
    top: 180px; /* Ajusté pour laisser de la place aux 3 lignes */
    left: 60px; 
    width: 440px; 
    white-space: nowrap; /* On empêche le texte de déborder si une ligne est trop longue */
    line-height: 0.7; /* Espace confortable entre les lignes */
    font-size: 13px;
    font-weight: 900;
}

.adr-line {
    display: block; /* Force chaque span à se comporter comme une ligne */
    text-transform: uppercase;
}
        /* --- ZONE MRZ (BAS DU VERSO) --- */
    /* --- ZONE MRZ (BAS DU VERSO) --- */
.crt-mrz {
    position: absolute;
    bottom: 18px;    /* Distance par rapport au bas de la carte */
    left: 40px;      /* Marge à gauche */
    right: 40px;     /* Marge à droite (doit être identique pour centrer) */
    
    font-family: 'Courier New', Courier, monospace;
    font-size: 16px; 
    line-height: 1.0;
    
    /* Ajustez le letter-spacing pour étirer plus ou moins les lettres */
    letter-spacing: 5px; 
    
    color: #000;
    font-weight: bold;
    text-transform: uppercase;

    /* Force l'étalement horizontal parfait entre les marges définies */
    text-align: justify;
    text-align-last: justify; 
    
    /* Sécurité contre le débordement si un nom est trop long */
    overflow: hidden;
    white-space: nowrap;
}
/* Conteneur pour aligner la carte et l'icône côte à côte */
.card-wrapper-flex {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px; /* Espace entre la carte et l'icône */
    margin-bottom: 30px;
}

.btn-print-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: #ff4b4b; /* Couleur PDF */
    color: white !important;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    font-size: 20px;
    flex-shrink: 0; /* Empêche l'icône de s'écraser */
}

.btn-print-icon:hover {
    background-color: #d43f3f;
    transform: scale(1.1);
}


        /* --- STAMPS --- */
        .stamp-layer { position: absolute; pointer-events: none; }
        .layer-round { z-index: 15; width: 110px; }
        .layer-nominatif { z-index: 16; width: 130px; }
        .layer-signature { z-index: 17; width: 120px; }

        @media print {
                .btn-print-icon { display: none !important; }

            .card-actions, .btn-pdf { display: none !important; }
            .card-container { margin: 10px; float: left; page-break-inside: avoid; box-shadow: none; }
            .crt-card-inner { transform: none !important; transition: none; }
            .crt-card-back { display: block; position: relative; margin-top: 20px; transform: none; }
            .d-print-none { display: none !important; }
        }

        .search-container {
    position: relative;
    width: 300px;
}

.search-input {
    width: 100%;
    padding: 8px 15px 8px 40px;
    border-radius: 25px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #ff4b4b;
    box-shadow: 0 2px 10px rgba(255, 75, 75, 0.2);
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
}
    </style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-2 d-print-none">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="font-weight-bold">UNITÉ D'IMPRESSION </h3>
        
        <div class="d-flex align-items-center" style="gap: 15px;">
            <div class="search-container">
                <i class="la la-search search-icon"></i>
                <input type="text" id="mrzSearch" class="form-control search-input" placeholder="Rechercher un nom ou UUID...">
            </div>
        </div>
    </div>
</div>

        <div class="content-body">
            <div class="row">
                @forelse ($demandes as $demande)
      @php 
    $impetrant = $demande->impetrant;
    $lienDemande = route('demandes.show', $demande->id);
    
    // 1. On récupère le quartier
    $quartier = $demande->quartier;
    $quartierNom = $quartier?->lib_quartier ?? "N/A";

    // 2. On récupère l'arrondissement lié à ce quartier
    // Assure-toi que la relation 'arrondissement' est définie dans ton modèle Quartier
    $arrondissementNom = $quartier?->arrondissement?->lib_arrondissement ?? "N/A";
    
    $nationalite = $impetrant?->pays?->nationalite ?? "CONGOLAISE";
    $num = $demande->uuid;
    $docNumber = "00" . str_pad((intval(substr(str_replace('-','',$num), 0, -1)) - 1), 7, '0', STR_PAD_LEFT);

    // 3. Construction de l'adresse complète incluant l'arrondissement
   $numRue = trim(($demande->numero_adresse ?? '') . ' ' . ($demande->avenue_rue ?? ''));
    $quartierNom = $demande->quartier?->lib_quartier ?? "N/A";
    $arrondissementNom = $demande->quartier?->arrondissement?->lib_arrondissement ?? "N/A";

    // Logique MRZ
$largeurMRZ = 30;

    // Ligne 1
    $l1 = "RCCOG" . str_replace('-', '', str_pad(intval(substr(str_replace('-','',substr($demande->uuid,0,-1)),0))-1, 4, '0', STR_PAD_LEFT));
    $mrzLine1 = str_pad(substr($l1, 0, $largeurMRZ), $largeurMRZ, "<");

    // Ligne 2
    $l2 = date('ymd', strtotime($impetrant?->date_naissance)) . "M" . date('ymd', strtotime($demande->date_expiration)) . (strtoupper($impetrant?->pays?->code ?? $demande->impetrant?->pays?->code ?? "COG"));
    $mrzLine2 = str_pad(substr($l2, 0, $largeurMRZ), $largeurMRZ, "<");

    // Ligne 3
    $l3 = str_replace(' ', '<', $impetrant?->nom) . "<<" . str_replace(' ', '<', $impetrant?->prenom);
    $mrzLine3 = str_pad(substr($l3, 0, $largeurMRZ), $largeurMRZ, "<");
@endphp
                   <div class="col-xl-6 col-12 mb-4">
        <div class="d-flex align-items-center justify-content-center">
            
            <div class="card-container" style="margin: 0;"> 
                <a href="{{ $lienDemande }}" style="text-decoration: none; color: inherit;">
                    <div class="crt-card-inner">
                        <div class="crt-card-front">
                            <div class="crt-bg"></div>
                            <img src="{{ asset('app/'.$demande->photo) }}" class="crt-photo">
                            <div class="crt-data-container">
                                <div class="crt-text val-nom">{{ $impetrant?->nom }}</div>
                                <div class="crt-text val-doc-num">{{ $docNumber }}</div>
                                <div class="crt-text val-prenom">{{ $impetrant?->prenom }}</div>
                                <div class="crt-text val-sexe">{{ $impetrant?->sexe == 'Masculin' ? 'M' : 'F' }}</div>
                                <div class="crt-text val-nationalite">{{ $nationalite }}</div>
                                <div class="crt-text val-date-naiss">{{ date('d/m/Y', strtotime($impetrant?->date_naissance)) }}</div>
                                <div class="crt-text val-lieu-naiss">{{ $impetrant?->lieu_naissance }}</div>

                                <img src="{{ asset('img/crt/cachet_rond.png') }}" class="stamp-layer layer-round" style="top: 190px; left: 270px; transform: rotate(-8deg); opacity: 0.8;">
                                <img src="{{ asset('img/crt/cachet_nominatif.png') }}" class="stamp-layer layer-nominatif" style="top: 260px; left: 325px; opacity: 0.9;">
                                <img src="{{ asset('img/crt/signature.png') }}" class="stamp-layer layer-signature" style="top: 220px; left: 335px;">
                            </div>
                        </div>

                        <div class="crt-card-back">
                            <div class="crt-data-container">
                                <div class="crt-text val-emission">{{ date('d/m/Y', strtotime($demande?->date_emission)) }}</div>
                                <div class="crt-text val-expiration">{{ date('d/m/Y', strtotime($demande?->date_expiration)) }}</div>
                                <div class="crt-text val-profession">{{ $demande?->profession ?? 'N/A' }}</div>
                                <div class="crt-text val-adresse">
                                    <span class="adr-line">{{ $numRue ?: 'NON SPÉCIFIÉE' }}</span><br>
                                    <span class="adr-line">{{ $quartierNom }}</span><br>
                                    <span class="adr-line">{{ $arrondissementNom }}</span>
                                </div>
                                <div class="crt-mrz">
                                    {{ $mrzLine1 }}<br>
                                    {{ $mrzLine2 }}<br>
                                    {{ $mrzLine3 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <a href="{{ route('demandes.generate-pdf', $demande->id) }}" 
               class="btn-print-icon d-print-none" 
               style="margin-left: 15px;" 
               title="Télécharger PDF">
                <i class="la la-print"></i>
            </a>

        </div>
    </div>
                @empty
                    <div class="col-12 text-center py-5 d-print-none">Aucune donnée prête.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mrzSearch');
    // On cible les colonnes qui contiennent les cartes
    const cards = document.querySelectorAll('.col-xl-6.col-12');

    searchInput.addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();

        cards.forEach(card => {
            // On récupère le texte à l'intérieur de la carte (Nom, Prénom, UUID)
            const cardText = card.innerText.toLowerCase();
            
            if (cardText.includes(term)) {
                card.style.display = "block";
                // On s'assure que le flex est conservé
                card.classList.add('d-flex'); 
            } else {
                card.style.display = "none";
                card.classList.remove('d-flex');
            }
        });
    });
});
</script>
@endsection