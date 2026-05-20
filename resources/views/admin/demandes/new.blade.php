@extends('admin.layouts.app')

@section('title') Nouvelle Procédure @endsection

@section('styles')
<style>
    .selection-container { background: #f4f7f6; padding: 50px 20px; border-radius: 30px; min-height: 85vh; }
    
    .main-title { font-weight: 800; color: #1a202c; text-transform: uppercase; letter-spacing: 2px; }
    .main-subtitle { color: #718096; margin-bottom: 50px; }

    /* Séparateur élégant */
    .section-header { display: flex; align-items: center; margin: 40px 0; }
    .section-header hr { flex: 1; border: 0; border-top: 1px solid #e2e8f0; }
    .section-header span { padding: 0 20px; text-transform: uppercase; font-weight: 700; color: #94a3b8; font-size: 0.8rem; letter-spacing: 3px; }

    /* Carte stylisée */
    .procedure-card {
        background: white; border-radius: 20px; overflow: hidden; border: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        text-decoration: none !important; display: block; height: 100%;
    }

    .procedure-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
    }

    /* L'image de fond avec overlay */
    .img-box {
        height: 200px; background-size: cover; background-position: center;
        position: relative; transition: all 0.5s ease;
        background-color: #f8f9fa; 
    }
    
    .procedure-card:hover .img-box { transform: scale(1.05); }

    /* Overlay dégradé pour le texte sur l'image */
    .img-overlay {
        position: absolute; bottom: 0; left: 0; right: 0; top: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.7));
        display: flex; align-items: flex-end; padding: 20px;
    }

    .img-overlay h3 { color: white; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }

    /* Contenu descriptif */
    .card-body { padding: 25px; text-align: center; }
    .card-body p { color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 20px; min-height: 50px; }

    .btn-action {
        color: #3b82f6; font-weight: 800; font-size: 0.75rem; 
        text-transform: uppercase; display: flex; align-items: center; 
        justify-content: center; gap: 8px; transition: 0.3s;
    }
    .procedure-card:hover .btn-action { gap: 15px; color: #2563eb; }

</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="selection-container">
            
            <div class="text-center">
                <h1 class="main-title">Nouvelle Procédure</h1>
                <p class="main-subtitle lead">Sélectionnez le type de dossier à enregistrer dans le système.</p>
            </div>

            <div class="section-header">
                <hr><span>Premières Demandes</span><hr>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <a href="{{ route('demandes.newcrt') }}" class="procedure-card">
                        <div class="img-box" style="background-image: url('{{ asset('img/crt/recto.png') }}');">
                            <div class="img-overlay"><h3>Demande de CRT</h3></div>
                        </div>
                        <div class="card-body">
                            <p>Établissement initial d'une Carte de Résident Temporaire pour un impétrant.</p>
                            <div class="btn-action">Commencer <i class="feather icon-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4">
                    <a href="{{ route('demandes.newvisa') }}" class="procedure-card">
                        <div class="img-box" style="background-image: url('{{ asset('img/crt/passeport.png') }}');">
                            <div class="img-overlay"><h3>Demande de Visa</h3></div>
                        </div>
                        <div class="card-body">
                            <p>Procédure pour l'obtention d'un visa d'entrée ou de séjour sécurisé.</p>
                            <div class="btn-action">Commencer <i class="feather icon-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4">
                    <a href="{{ route('demandes.newdiplomate') }}" class="procedure-card">
                        <div class="img-box" style="background-image: url('{{ asset('img/crt/diplomate.png') }}');">
                            <div class="img-overlay"><h3>Corps Diplomatique</h3></div>
                        </div>
                        <div class="card-body">
                            <p>Dossiers réservés exclusivement aux diplomates et agents officiels accrédités.</p>
                            <div class="btn-action">Commencer <i class="feather icon-arrow-right"></i></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="section-header">
                <hr><span>Mises à Jour</span><hr>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <a href="{{ route('demandes.renouvellement') }}" class="procedure-card">
                        <div class="img-box" style="background-image: url('{{ asset('img/crt/renew.png') }}');">
                            <div class="img-overlay"><h3>Renouvellement</h3></div>
                        </div>
                        <div class="card-body">
                            <p>Prolongation de validité ou mise à jour d'un document arrivant à expiration.</p>
                            <div class="btn-action">Commencer <i class="feather icon-arrow-right"></i></div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection