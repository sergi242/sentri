@extends('admin.layouts.app')

@section('title', 'Expirations Proches')

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.98);
        /* Dégradé Alerte : Rouge vers Orange Sombre */
        --primary-gradient: linear-gradient(135deg, #ed213a 0%, #93291e 100%);
        --urgent-color: #ff4961;
        --safe-color: #28d094;
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
    .modern-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }

    /* Indicateurs latéraux selon l'urgence */
    .indicator-urgent { border-left: 6px solid var(--urgent-color); }
    .indicator-safe { border-left: 6px solid var(--safe-color); }

    .photo-container {
        position: relative;
        width: 100%;
        height: 100px;
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
        opacity: 0.9;
    }

    .status-badge-floating {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-action-trigger {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .avatar-wrapper { position: absolute; bottom: -40px; z-index: 2; }
    .modern-avatar {
        width: 90px; height: 90px; border-radius: 25px;
        border: 4px solid #fff; object-fit: cover;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .info-section { padding: 0 20px 20px 20px; }
    .name-title { font-size: 1.1rem; font-weight: 800; color: #2d3436; }
    
    .stat-pill { background: #f8f9fa; border-radius: 15px; padding: 10px; margin: 15px 0; }

    /* Animation d'alerte */
    .pulse-urgent {
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0px rgba(237, 33, 58, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(237, 33, 58, 0); }
        100% { box-shadow: 0 0 0 0px rgba(237, 33, 58, 0); }
    }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-3">
            <div class="col-md-8">
                <h1 class="font-weight-bold">Suivi des Expirations</h1>
                <p class="text-muted">Dossiers arrivant à échéance dans les 3 prochains mois.</p>
            </div>
            <div class="col-md-4 text-md-right">
                <div class="bg-white d-inline-block p-1 rounded shadow-sm">
                    <span class="text-muted small uppercase font-weight-bold">Total à risque : </span>
                    <span class="badge badge-danger badge-pill">{{ $demandes->count() }}</span>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row" id="expirationContainer">
                @forelse ($demandes as $key => $impetrant)
                    @php
                        $derniereDemande = $impetrant->demandes->last();
                        $dateExp = \Carbon\Carbon::parse($derniereDemande?->date_expiration);
                        $isUrgent = $dateExp->diffInMonths(now()) <= 3;
                    @endphp

                    <div class="col-xl-4 col-md-6 col-12 demande-card-wrapper" style="animation-delay: {{ $key * 0.05 }}s">
                        <div class="card modern-card shadow-sm {{ $isUrgent ? 'indicator-urgent pulse-urgent' : 'indicator-safe' }}">
                            
                            <div class="photo-container">
                                <div class="card-banner"></div>
                                
                                <div class="status-badge-floating {{ $isUrgent ? 'text-danger' : 'text-success' }}">
                                    <i class="la {{ $isUrgent ? 'la-warning' : 'la-check-circle' }}"></i>
                                    {{ $dateExp->diffForHumans() }}
                                </div>

                                <div class="btn-action-trigger" 
                                     data-toggle="popover" data-html="true" data-placement="bottom"
                                     data-content='<div class="d-flex flex-column">
                                        <a href="{{ route("impetrants.demandes", $impetrant->id) }}" class="p-1 text-dark"><i class="la la-eye"></i> Voir Dossier</a>
                                        <a href="#" class="p-1 text-primary"><i class="la la-envelope"></i> Alerter</a>
                                     </div>'>
                                    <i class="la la-ellipsis-v"></i>
                                </div>
                                
                                <a href="{{ route('impetrants.demandes', $impetrant->id) }}" class="avatar-wrapper">
                                    <img src="{{ asset('app/'.$derniereDemande?->photo) }}" class="modern-avatar" onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                                </a>
                            </div>

                            <div class="card-body info-section text-center">
                                <h4 class="name-title mb-0">{{ $impetrant->nomcomplet() }}</h4>
                                <small class="text-muted font-weight-bold text-uppercase">{{ $impetrant->pays?->lib_pays }}</small>

                                <div class="row stat-pill no-gutters">
                                    <div class="col-6 border-right">
                                        <small class="text-muted d-block">Historique</small>
                                        <span class="badge badge-pill badge-light-primary">{{ $impetrant->demandes->count() }} dossier(s)</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Dernier Type</small>
                                        <span class="font-weight-bold small text-dark">{{ $derniereDemande?->type_demande ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                    <div class="text-left">
                                        <small class="text-muted d-block">Échéance :</small>
                                        <span class="font-weight-bold {{ $isUrgent ? 'text-danger' : 'text-success' }}">
                                            {{ $dateExp->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('impetrants.demandes', $impetrant->id) }}" class="btn btn-dark btn-sm round px-2">
                                        Gérer <i class="la la-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="la la-check-circle text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                        <h4 class="text-muted mt-2">Aucune expiration proche détectée.</h4>
                    </div>
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
        $('[data-toggle="popover"]').popover({ trigger: 'click', sanitize: false });
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    });
</script>
@endsection