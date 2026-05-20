@extends("admin.layouts.app")

@section("title", $status)

@section("styles")
<style>
    :root {
        --primary-hub: #4834d4;
        --secondary-hub: #686de0;
    }

    /* Animation d'entrée */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-anim { animation: fadeInUp 0.4s ease backwards; }

    .card-user-modern {
        border: none;
        border-radius: 20px;
        background: #fff;
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 30px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .card-user-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(72, 52, 212, 0.12) !important;
    }

    /* Header de carte avec bouton d'options */
    .card-top-decoration {
        height: 90px;
        background: linear-gradient(135deg, var(--primary-hub), var(--secondary-hub));
        position: relative;
    }

    .options-top {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }

    .btn-options-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .btn-options-glass:hover { background: white; color: var(--primary-hub); }

    /* Photo de profil */
    .avatar-wrapper {
        margin-top: -45px;
        position: relative;
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }

    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #fff;
    }

    .user-info-body { padding: 0 20px 20px 20px; text-align: center; }
    .user-name { font-weight: 800; color: #2d3436; font-size: 1.1rem; line-height: 1.3; margin-bottom: 8px; }
    
    .type-badge {
        font-size: 0.65rem;
        font-weight: 700;
        background: #f1f2f6;
        color: #57606f;
        padding: 3px 12px;
        border-radius: 10px;
        display: inline-block;
        margin-bottom: 15px;
    }

    /* Grille d'infos style "ID Card" */
    .id-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1px;
        background: #f1f2f6;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 15px;
        border: 1px solid #f1f2f6;
    }
    .id-item { background: white; padding: 8px 5px; }
    .id-label { font-size: 0.55rem; color: #a4b0be; text-transform: uppercase; display: block; font-weight: 600; }
    .id-value { font-size: 0.75rem; font-weight: 700; color: #2f3542; display: block; }

    /* Fix Pagination */
    .pagination-container {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 20px 0;
        clear: both;
    }
</style>
@endsection

@section("content")
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            
            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <h2 class="font-weight-bold text-uppercase">{{ $status }}</h2>
                    <p class="text-muted">Répertoire centralisé des demandes (Visa / Résidence).</p>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="?layout=list" class="btn btn-outline-primary round px-2">
                        <i class="la la-list"></i> Mode Liste
                    </a>
                </div>
            </div>

            <div class="row">
                @forelse ($demandes as $key => $demande)
                <div class="col-xl-3 col-md-6 col-12 card-anim" style="animation-delay: {{ $key * 0.05 }}s">
                    <div class="card card-user-modern shadow-sm">
                        
                        <div class="card-top-decoration">
                            <div class="options-top dropdown">
                                <a href="#" class="btn-options-glass" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="la la-cog"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0">
                                    <a class="dropdown-item" href="{{ route('demandes.show', $demande->id) }}">
                                        <i class="la la-eye text-primary"></i> Voir le dossier
                                    </a>
                                    <a class="dropdown-item" href="{{ route('demandes.edit', $demande->id) }}">
                                        <i class="la la-edit text-info"></i> Modifier
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="la la-trash"></i> Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="avatar-wrapper">
                            <img src="{{ asset('app/'.$demande->photo) }}" class="avatar-circle" 
                                 onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                        </div>

                        <div class="user-info-body">
                            <div class="user-name">
                                {{ $demande->impetrant?->nom }} <br>
                                {{ $demande->impetrant?->prenom }}
                            </div>
                            <span class="type-badge">{{ $demande->type_demande }}</span>

                            <div class="id-grid">
                                <div class="id-item">
                                    <span class="id-label">Pays</span>
                                    <span class="id-value text-truncate">{{ $demande->impetrant?->pays?->lib_pays }}</span>
                                </div>
                                <div class="id-item">
                                    <span class="id-label">Sexe</span>
                                    <span class="id-value">{{ $demande->impetrant?->sexe == 'Masculin' ? 'H' : 'F' }}</span>
                                </div>
                                <div class="id-item">
                                    <span class="id-label">Né(e) le</span>
                                    <span class="id-value">{{ date('d/m/Y', strtotime($demande->impetrant?->date_naissance)) }}</span>
                                </div>
                                <div class="id-item">
                                    <span class="id-label">Statut</span>
                                    <span class="id-value text-primary" style="font-size: 0.65rem">{{ $demande->statut_demande }}</span>
                                </div>
                            </div>

                            <div class="mb-2 text-muted" style="font-size: 0.7rem;">
                                <i class="la la-clock"></i> Il y a {{ TechnoDev::timespan($demande->created_at) }}
                            </div>

                            <a href="{{ route('demandes.show', $demande->id) }}" class="btn btn-primary btn-block round shadow-sm">
                                Ouvrir le dossier
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Aucune demande trouvée.</h4>
                </div>
                @endforelse
            </div>

            <div class="row">
                <div class="col-12 pagination-container">
                    {{ $demandes->links('admin.pagination.pagination') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection