@extends('admin.layouts.app')

@section('title') Dashboard @endsection

@section('styles')
<style>
    html body {
        height: 100vh;
        background: radial-gradient(circle, rgba(20, 30, 48, 0.8) 0%, rgba(10, 20, 30, 0.95) 100%), 
                    url("{{ asset('res/app-assets/images/backgrounds/bg-9.jpg') }}") center center no-repeat;
        background-size: cover;
        overflow: hidden;
        color: white;
        border: 8px solid;
        border-image: linear-gradient(to right, #009543 33%, #fbff00 33% 66%, #ef3340 66%) 1;
    }

    /* --- DRAPEAU HAUT GAUCHE --- */
    
    

    /* --- DMCE VERTICAL --- */
    .vertical-tag {
        position: absolute;
        left: 25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        font-weight: 900;
        letter-spacing: 15px;
        color: rgba(255, 255, 255, 0.1);
        writing-mode: vertical-rl;
        text-orientation: uppercase;
        border-left: 2px solid rgba(251, 255, 0, 0.2);
        padding-left: 10px;
        user-select: none;
    }
.central {
        color: #ff0000 !important; /* Rouge pur */
        font-weight: 900 !important; /* Gras maximum */
        text-transform: uppercase;   /* Optionnel : pour l'uniformité du design */
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5); /* Améliore la lisibilité sur fond sombre */
    }
    .scan-line {
        position: absolute;
        width: 100%;
        height: 100px;
        z-index: 0;
        background: linear-gradient(to bottom, transparent, rgba(251, 255, 0, 0.03), transparent);
        animation: scan 6s linear infinite;
    }

    @keyframes scan { 0% { top: -100px; } 100% { top: 100vh; } }

    .dashboard-ui { position: relative; z-index: 1; padding-top: 3vh; }

    /* --- TITRE LISIBLE --- */
    .main-header { text-align: center; margin-bottom: 40px; }
    .main-header h1 { 
        font-weight: 900; 
        letter-spacing: 2px; 
        text-transform: uppercase; 
        font-size: 2.2rem; 
        color: #ffffff;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8); /* Maximise la lisibilité */
        margin-bottom: 5px;
    }
    .highlight-yellow { color: #fbff00; }
    
    .main-header p { 
        font-size: 1.2rem;
        letter-spacing: 4px; 
        text-transform: uppercase;
        color: rgba(255,255,255,0.8);
        font-weight: 300;
    }

    .dept-tag { 
        background: #fbff00; 
        color: #000 !important; 
        padding: 5px 25px; 
        font-weight: 900; 
        display: inline-block; 
        margin-bottom: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    /* --- GRILLE --- */
    .cmd-grid {
        display: grid;
        grid-template-columns: repeat(3, 320px);
        gap: 25px;
        justify-content: center;
        align-items: center;
    }

    .cmd-btn {
        background: rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none !important;
    }

    .cmd-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-left: 5px solid #fbff00;
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .cmd-btn i { font-size: 2.2rem; color: #fbff00; }
    .cmd-btn span { font-weight: 800; text-transform: uppercase; font-size: 1rem; color: #fff; display: block; }
    .cmd-btn small { color: #009543; font-weight: 700; font-size: 0.7rem; letter-spacing: 1px; }

    .emblem-container { position: relative; width: 200px; margin: 0 auto; }
    .emblem-container img { width: 100%; filter: drop-shadow(0 0 25px rgba(251, 255, 0, 0.3)); position: relative; z-index: 2; }
    
    .orbit {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 260px; height: 260px; border: 1px dashed rgba(251, 255, 0, 0.4);
        border-radius: 50%; animation: rotate 25s linear infinite;
    }
    @keyframes rotate { from { transform: translate(-50%, -50%) rotate(0deg); } to { transform: translate(-50%, -50%) rotate(360deg); } }

</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="scan-line"></div>
    <div class="top-flag"></div>
    <div class="vertical-tag">DMCE</div>
    
    <div class="dashboard-ui">
        <div class="main-header">
            <div class="dept-tag">Département Des Migrations</div>
            <h1>Ministère de l'Intérieur et de la Décentralisation</h1>
            <p class="central" >Centrale d'Intelligence et de Documentation</p>
        </div>

        <div class="cmd-grid">
            <div class="d-flex flex-column gap-3">
                @can("demandes.create")
                <a href="{{route('demandes.newdocument')}}" class="cmd-btn">
                    <i class="feather icon-plus-square"></i>
                    <div class="label-box">
                        <span>Nouvelle Demande</span>
                        <small>SYSTÈME D'ENRÔLEMENT</small>
                    </div>
                </a>
                @endcan

                @can("demandes.renew")
                <a href="{{route('demandes.renouvellement')}}" class="cmd-btn">
                    <i class="feather icon-repeat"></i>
                    <div class="label-box">
                        <span>Renouvellement</span>
                        <small>MISE À JOUR DOCUMENTS</small>
                    </div>
                </a>
                @endcan
            </div>

            <div class="center-spacer text-center">
                <div class="emblem-container">
                    <div class="orbit"></div>
                    <img src="{{ asset('/img/congo.png') }}" alt="Armoiries">
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                @can("demandes.view.pending")
                <a href="{{route('demandes.attentes')}}" class="cmd-btn">
                    <i class="feather icon-clock"></i>
                    <div class="label-box">
                        <span>Dossiers Attente</span>
                        <small>FLUX DE TRAITEMENT</small>
                    </div>
                </a>
                @endcan

                @can("demandes.renew")
                <a href="{{route('demandes.contentieux')}}" class="cmd-btn" style="border-right: 2px solid #ef3340;">
                    <i class="feather icon-shield" style="color: #ef3340;"></i>
                    <div class="label-box">
                        <span>Contentieux</span>
                        <small>UNITÉ DE VÉRIFICATION</small>
                    </div>
                </a>
                @endcan
            </div>

            <div style="grid-column: span 3; margin-top: 20px; display: flex; justify-content: center;">
                @can("demandes.search.advanced")
                <a href="{{route('demandes.search.form')}}" class="cmd-btn" style="width: 700px; justify-content: center; border: 1px solid #fbff00; background: rgba(251, 255, 0, 0.05);">
                    <i class="feather icon-search"></i>
                    <div class="label-box">
                        <span>Module de Recherche Avancée</span>
                        <small>BASE DE DONNÉES NATIONALE</small>
                    </div>
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection