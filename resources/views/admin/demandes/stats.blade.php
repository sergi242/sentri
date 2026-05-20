@extends('admin.layouts.app')

@section('title')
Liste des demandes
@endsection

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.98);
        --primary-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .demande-card-wrapper { animation: fadeInUp 0.5s ease backwards; }

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
    .btn-action-trigger:hover { background: white; color: #11998e; transform: scale(1.1); }

    .popover { border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border-radius: 15px; min-width: 220px; }
    .pop-action-item {
        display: flex; align-items: center; padding: 10px 15px; margin-bottom: 5px;
        border-radius: 10px; color: #2d3436; text-decoration: none !important;
        transition: 0.2s; background: #f8f9fa; width: 100%; border: none;
    }
    .pop-action-item:hover { background: #11998e; color: white !important; transform: translateX(5px); }

    .avatar-wrapper { position: absolute; bottom: -40px; z-index: 2; }
    .modern-avatar {
        width: 95px; height: 95px; border-radius: 28px;
        border: 4px solid #fff; object-fit: cover;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .type-label {
        font-size: 0.75rem; font-weight: 600; color: #11998e;
        background: rgba(17, 153, 142, 0.1);
        padding: 2px 12px; border-radius: 20px;
        display: inline-block; margin-top: 5px;
    }

    .info-section { padding: 0 20px 20px 20px; }
    .name-title { font-size: 1.1rem; font-weight: 800; color: #2d3436; text-decoration: none !important; }
    .stat-pill { background: #f1f3f9; border-radius: 15px; padding: 12px; margin: 18px 0; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-4 align-items-center">
            <div class="col-md-7">
                @php
                    $titres = [
                        'jour' => "Demandes d'aujourd'hui",
                        'semaine' => "Demandes de cette semaine",
                        'mois' => "Demandes du mois courant",
                        'annee' => "Demandes de l'année courante"
                    ];
                    $titreAffiche = $titres[$critere] ?? "Toutes les demandes";
                @endphp
                <h1 class="font-weight-bold text-uppercase">📋 {{ $titreAffiche }}</h1>
                <p class="text-muted">Vue d'ensemble des dossiers filtrés par critère temporel.</p>
            </div>
            <div class="col-md-5 text-md-right">
                <div class="d-flex justify-content-md-end align-items-center" style="gap: 10px;">
                    <input type="text" id="searchDemande" class="form-control border-0 shadow-sm round" placeholder="Rechercher un impétrant..." style="width: 250px;">
                    <a href="{{ route('demandes.newdocument') }}" class="btn btn-primary round shadow-sm">
                        <i class="la la-plus"></i> Nouveau
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row" id="demandeContainer">
                @forelse ($demandes as $key => $demande)
                <div class="col-xl-4 col-md-6 col-12 demande-card-wrapper" style="animation-delay: {{ $key * 0.05 }}s">
                    <div class="card modern-card shadow-sm">
                        
                        <div class="photo-container">
                            <div class="card-banner"></div>
                            
                            <div class="btn-action-trigger" 
                                 data-toggle="popover" 
                                 data-html="true" 
                                 data-placement="bottom"
                                 data-content='
                                    <div class="d-flex flex-column">
                                        <a href="#" class="pop-action-item">
                                            <i class="la la-eye"></i> Détails
                                        </a>
                                        <a href="#" class="pop-action-item">
                                            <i class="la la-edit"></i> Modifier
                                        </a>
                                    </div>'>
                                <i class="la la-ellipsis-v"></i>
                            </div>
                            
                            <div class="avatar-wrapper">
                                <img src="{{asset('app/'.$demande->photo)}}" class="modern-avatar" onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                            </div>
                        </div>

                        <div class="card-body info-section text-center">
                            <span class="name-title d-block mb-0">
                                {{$demande->nom}} {{$demande->prenom}}
                            </span>
                            
                            <span class="type-label">{{ $demande->type_demande }}</span>

                            <div class="row stat-pill no-gutters">
                                <div class="col-4">
                                    <small class="text-muted d-block">Genre</small>
                                    <span class="font-weight-bold">{{$demande->sexe}}</span>
                                </div>
                                <div class="col-4 border-left border-right">
                                    <small class="text-muted d-block">Validité</small>
                                    <span class="font-weight-bold text-success">{{$demande->validite}} an(s)</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Statut</small>
                                    @php
                                        $badgeColor = 'text-secondary';
                                        if(str_contains(strtolower($demande->statut_demande), 'valid')) $badgeColor = 'text-success';
                                        if(str_contains(strtolower($demande->statut_demande), 'attent')) $badgeColor = 'text-warning';
                                    @endphp
                                    <span class="font-weight-bold {{ $badgeColor }}">{{ $demande->statut_demande }}</span>
                                </div>
                            </div>

                            <div class="text-muted font-small-2 d-flex justify-content-between px-1">
                                <span><i class="la la-birthday-cake"></i> {{ \Carbon\Carbon::parse($demande->date_naissance)->format('d/m/Y') }}</span>
                                <span><i class="la la-calendar"></i> {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="card modern-card p-5">
                        <i class="la la-folder-open text-muted" style="font-size: 5rem;"></i>
                        <p class="text-muted mt-2">Aucune demande trouvée pour ce critère.</p>
                    </div>
                </div>
                @endforelse
            </div>

            @if(method_exists($demandes, 'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $demandes->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Initialisation des Popovers
        $('[data-toggle="popover"]').popover({ trigger: 'click', sanitize: false });
        
        // Fermer le popover en cliquant ailleurs
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        // Recherche dynamique en temps réel
        $("#searchDemande").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#demandeContainer .demande-card-wrapper").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection