@extends('admin.layouts.app')

@section('title', $status)

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.98);
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Animation d'entrée */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .demande-card-wrapper { animation: fadeInUp 0.5s ease backwards; }

    /* Carte Modern */
    .modern-card {
        border: none;
        border-radius: 20px;
        background: var(--glass-bg);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .modern-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; }

    /* Header de la carte */
    .photo-container {
        position: relative;
        width: 100%;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 50px;
    }
    .card-banner {
        position: absolute;
        top: 0; left: 0; right: 0; height: 100%;
        background: var(--primary-gradient);
        border-radius: 20px 20px 0 0;
    }

    /* DRAPEAU FLOTTANT */
    .flag-container {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
    }
    .flag-img {
        width: 35px;
        height: 35px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.8);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* BOUTON ACTION EN HAUT */
    .btn-action-trigger {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        background: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-action-trigger:hover { background: white; color: #764ba2; transform: scale(1.1); }

    /* Popover d'actions */
    .popover { border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border-radius: 15px; min-width: 220px; }
    .pop-action-item {
        display: flex; align-items: center; padding: 10px 15px; margin-bottom: 5px;
        border-radius: 10px; color: #2d3436; text-decoration: none !important;
        transition: 0.2s; background: #f8f9fa; width: 100%; border: none;
    }
    .pop-action-item:hover { background: #764ba2; color: white !important; transform: translateX(5px); }
    .pop-action-item i { margin-right: 12px; font-size: 1.3rem; }

    /* Avatar Squircle */
    .avatar-wrapper { position: absolute; bottom: -40px; z-index: 2; }
    .modern-avatar {
        width: 95px; height: 95px; border-radius: 28px;
        border: 4px solid #fff; object-fit: cover;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    /* Nouveau badge pour le type de demande sous le nom */
    .type-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #764ba2;
        background: rgba(118, 75, 162, 0.1);
        padding: 2px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-top: 5px;
    }

    .info-section { padding: 0 20px 20px 20px; }
    .name-title { font-size: 1.2rem; font-weight: 800; color: #2d3436; text-decoration: none !important; }
    
    .stat-pill { background: #f1f3f9; border-radius: 15px; padding: 12px; margin: 18px 0; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
      <div class="row mb-4 align-items-center">
    <div class="col-md-4">
        <h1 class="font-weight-bold">{{ $status }}</h1>
        <p class="text-muted">Gestion visuelle des demandes en cours.</p>
    </div>
    <div class="col-md-4 text-center">
    <div class="btn-group shadow-sm round" style="border-radius: 30px; overflow: hidden; border: 1px solid #764ba2;">
        {{-- Bouton Tous --}}
        <a href="{{ request()->fullUrlWithQuery(['type' => 'ALL']) }}" 
           class="btn {{ request('type') == 'ALL' || !request('type') ? 'btn-primary text-white' : 'btn-outline-primary' }}" 
           style="border:none">Tous</a>

        {{-- Bouton Visa --}}
        <a href="{{ request()->fullUrlWithQuery(['type' => 'Visa']) }}" 
           class="btn {{ request('type') == 'Visa' ? 'btn-primary text-white' : 'btn-outline-primary' }}" 
           style="border:none">Visa</a>

        {{-- Bouton CRT --}}
        <a href="{{ request()->fullUrlWithQuery(['type' => 'Carte de Resident temporaire']) }}" 
           class="btn {{ request('type') == 'Carte de Resident temporaire' ? 'btn-primary text-white' : 'btn-outline-primary' }}" 
           style="border:none">CRT</a>
    </div>
</div>
    <div class="col-md-4 text-md-right">
        <input type="text" id="searchDemande" class="form-control border-0 shadow-sm round d-inline-block" placeholder="Rechercher..." style="width: 250px;">
    </div>
</div>

        <div class="content-body">
            <div class="row" id="demandeContainer">
                @forelse ($demandes as $key => $demande)
                <div class="col-xl-4 col-md-6 col-12 demande-card-wrapper" style="animation-delay: {{ $key * 0.05 }}s" data-type="{{ $demande->type_demande }}">
                    <div class="card modern-card shadow-sm">
                        
                        <div class="photo-container">
                            <div class="card-banner"></div>
                            
                            @php
                                $pays = null;
                                if ($demande->impetrant && $demande->impetrant->nationalites_id) {
                                    $pays = \App\Models\Pays::find($demande->impetrant->nationalites_id);
                                }
                                $flagPath = $pays && $pays->code
                                    ? 'res/flags/' . strtolower(trim($pays->code)) . '.png'
                                    : null;
                            @endphp

                            @if($flagPath && file_exists(public_path($flagPath)))
                                <div class="flag-container" title="{{ $demande->impetrant?->pays?->lib_pays }}">
                                    <img src="{{ asset($flagPath) }}" class="flag-img" alt="Flag">
                                </div>
                            @endif

                            <div class="btn-action-trigger" 
                                 data-toggle="popover" 
                                 data-html="true" 
                                 data-placement="bottom"
                                 data-content='
                                    <div class="d-flex flex-column">
                                        <a href="{{ ($demande->impetrant?->id ? route('impetrants.demandes', $demande->impetrant->id) : '#') }}" class="pop-action-item">
                                            <i class="la la-user-tie"></i> Voir la fiche
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <x-demandes.demande-actions :demande="$demande" />
                                    </div>'>
                                <i class="la la-ellipsis-v"></i>
                            </div>
                            
                            <a href="{{ ($demande->impetrant?->id ? route('impetrants.demandes', $demande->impetrant->id) : '#') }}" class="avatar-wrapper">
                                <img src="{{asset('app/'.$demande->photo)}}" class="modern-avatar" onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                            </a>
                        </div>

                        <div class="card-body info-section text-center">
                            <a href="{{ ($demande->impetrant?->id ? route('impetrants.demandes', $demande->impetrant->id) : '#') }}" class="name-title d-block mb-0">
                                {{$demande->impetrant?->nomcomplet()}}
                            </a>
                            
                            <span class="type-label">{{ $demande->type_demande }}</span>

                            <div class="row stat-pill no-gutters">
                                <div class="col-4">
                                    <small class="text-muted d-block">Genre</small>
                                    <span class="font-weight-bold">{{$demande->impetrant?->sexe}}</span>
                                </div>
                                <div class="col-4 border-left border-right">
                                    <small class="text-muted d-block">Validité</small>
                                    <span class="font-weight-bold text-primary">{{$demande->validite}} an</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Statut</small>
                                    <span class="badge badge-pill {{ $demande->statut_demande == 'Approuvé' ? 'badge-success' : 'badge-warning' }} font-small-1">
                                        {{$demande->statut_demande}}
                                    </span>
                                </div>
                            </div>

                            <div class="text-muted font-small-2">
                                <i class="la la-clock"></i> Soumis le {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $demandes->links('admin.pagination.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Initialisation Popover
        $('[data-toggle="popover"]').popover({ trigger: 'click', sanitize: false });

        // Fermer popover en cliquant ailleurs
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        // Fonction combinée Recherche + Filtre
        function applyFilters() {
            var searchTerm = $("#searchDemande").val().toLowerCase();
            var activeType = $(".filter-btn.active").data('type');

            $(".demande-card-wrapper").each(function() {
                var cardText = $(this).text().toLowerCase();
                var cardType = $(this).data('type');

                var matchesSearch = cardText.indexOf(searchTerm) > -1;
                var matchesType = (activeType === "ALL" || cardType === activeType);

                if (matchesSearch && matchesType) {
                    $(this).fadeIn(300);
                } else {
                    $(this).hide();
                }
            });
        }

        // Event : Saisie dans la recherche
        $("#searchDemande").on("keyup", applyFilters);

        // Event : Clic sur les boutons de filtre
        $(".filter-btn").on("click", function() {
            $(".filter-btn").removeClass("active btn-primary text-white").addClass("btn-outline-primary");
            $(this).addClass("active btn-primary text-white").removeClass("btn-outline-primary");
            applyFilters();
        });
    });
</script>
@endsection