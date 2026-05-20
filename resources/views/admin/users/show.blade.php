@extends('admin.layouts.app')

@section('title')
    Profil de {{ $user->prenom }}
@endsection

@section('styles')
<style>
    :root {
        --profile-bg: rgba(255, 255, 255, 0.9);
        --accent-color: #3f73ac;
        --secondary-accent: #5888a6;
    }

    /* Animation d'entrée */
    .profile-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header de Profil Style Réseau Social */
    .profile-header-card {
        border: none;
        border-radius: 25px;
        background: white;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .profile-cover {
        height: 180px;
        background: linear-gradient(135deg, #3f73ac 0%, #5888a6 100%);
        position: relative;
    }

    .profile-main-info {
        padding: 0 40px 30px 40px;
        position: relative;
        margin-top: -60px;
    }

    .profile-img-big {
        width: 150px;
        height: 150px;
        border-radius: 35px;
        border: 6px solid #fff;
        background: #f8f9fa;
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    /* Badges et Textes */
    .role-label {
        background: rgba(63, 115, 172, 0.1);
        color: var(--accent-color);
        padding: 6px 15px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-block;
    }

    .stat-box {
        background: #f8faff;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        transition: 0.3s;
        border: 1px solid transparent;
    }
    .stat-box:hover {
        transform: translateY(-5px);
        background: white;
        border-color: var(--accent-color);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    /* Timeline Activité */
    .activity-item {
        border-left: 2px solid #eef2f7;
        padding-left: 20px;
        padding-bottom: 20px;
        position: relative;
    }
    .activity-item::before {
        content: '';
        position: absolute;
        left: -7px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent-color);
    }

    .glass-card {
        background: var(--profile-bg);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.05);
    }
</style>
@endsection

@section('content')
<div class="app-content content profile-fade-in">
    <div class="content-wrapper">
        
        <div class="profile-header-card">
            <div class="profile-cover">
                <div class="p-3 text-right">
                    <button type="button" class="btn btn-white round shadow-sm"
        data-toggle="modal" data-target="#exportPdfModal">
    <i class="la la-file-pdf-o"></i> Exporter la fiche
</button>
                </div>
            </div>
            <div class="profile-main-info">
                <div class="row align-items-end">
                    <div class="col-md-auto">
                        @if($user->photo)
                            <img src="{{ asset('uploads/users/'.$user->photo) }}" class="profile-img-big">
                        @else
                            <div class="profile-img-big d-flex align-items-center justify-content-center text-muted">
                                <i class="la la-user" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col md-mb-2">
                        <div class="mb-1"><span class="role-label">{{ $user->role?->lib_role }}</span></div>
                        <h1 class="font-weight-bold mb-0" style="color: #2c3e50;">{{ $user->getNomPrenom() }}</h1>
                        <p class="text-muted"><i class="la la-envelope"></i> {{ $user->email }} | <i class="la la-briefcase"></i> {{ $user->grade?->grade ?? 'Aucun grade' }}</p>
                    </div>
                    <div class="col-md-auto text-right">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary round px-2 shadow">
                            <i class="la la-edit"></i> Modifier le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="stat-box">
                                <i class="la la-file-text text-primary" style="font-size: 2.5rem;"></i>
                                <h2 class="font-weight-bold mt-1">{{ $user->demandes_creees_count ?? 0 }}</h2>
                                <span class="text-muted text-uppercase font-small-3">Demandes</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="stat-box">
                                <i class="la la-paper-plane text-success" style="font-size: 2.5rem;"></i>
                                <h2 class="font-weight-bold mt-1">{{ $user->soit_transmis_count ?? 0 }}</h2>
                                <span class="text-muted text-uppercase font-small-3">Soit Transmis</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="stat-box">
                                <i class="la la-globe text-info" style="font-size: 2.5rem;"></i>
                                <h2 class="font-weight-bold mt-1">{{ $user->flux_migratoires_count ?? 0 }}</h2>
                                <span class="text-muted text-uppercase font-small-3">Flux Migratoire</span>
                            </div>
                        </div>
                    </div>

                    <div class="card glass-card mt-2">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title font-weight-bold"><i class="la la-history"></i> Activité Récente</h4>
                        </div>
                        <div class="card-content p-2">
                            @if($user->demandes->isEmpty())
                                <div class="text-center py-3">
                                    <i class="la la-frown-o text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-1">Aucune activité enregistrée.</p>
                                </div>
                            @else
                                <div class="activity-container mt-1">
                                    @foreach($user->demandes->take(5) as $demande)
                                    <div class="activity-item">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('demandes.show', $demande->id) }}" class="font-weight-bold text-dark">
                                                Dossier #{{ $demande->id }} - {{ $demande->statut_demande }}
                                            </a>
                                            <small class="text-muted">{{ $demande->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="font-small-3 text-muted">Action effectuée le {{ $demande->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card glass-card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title font-weight-bold"><i class="la la-lock"></i> Sécurité & Accès</h4>
                        </div>
                        <div class="card-content p-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                    <span class="text-muted">Dernière IP</span>
                                    <span class="badge badge-secondary round">192.168.1.XX</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                    <span class="text-muted">ID Système</span>
                                    <span class="font-weight-bold">#{{ $user->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                    <span class="text-muted">Membre depuis</span>
                                    <span class="font-weight-bold">{{ $user->created_at->format('M Y') }}</span>
                                </li>
                            </ul>
                            
                            <div class="mt-3 bg-light p-2 border-radius-15">
                                <small class="text-muted d-block mb-1 text-uppercase">Statut Compte</small>
                                <div class="d-flex align-items-center">
                                    <div class="badge badge-success badge-circle-sm mr-1"></div>
                                    <span class="font-weight-bold text-success">ACTIF</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('users.index') }}" class="btn btn-block btn-outline-secondary mt-2 round">
                        <i class="la la-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exportPdfModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#3f73ac,#5888a6);">
                <h5 class="modal-title text-white font-weight-bold">
                    <i class="la la-file-pdf-o"></i> Générer le rapport PDF
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('users.activities.pdf', $user->id) }}" method="GET" target="_blank">
                <div class="modal-body p-4">

                    <div class="form-group">
                        <label class="font-weight-bold">Date de début *</label>
                        <input type="date" name="start_date" class="form-control"
                               value="{{ now()->startOfMonth()->format('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Date de fin *</label>
                        <input type="date" name="end_date" class="form-control"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    {{-- Raccourcis période --}}
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Raccourcis :</small>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('week')">Cette semaine</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('month')">Ce mois</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('quarter')">Ce trimestre</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('year')">Cette année</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPeriod('all')">Tout</button>
                        </div>
                    </div>

                </div>
                <div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" id="include_analysis" name="include_analysis" value="1" checked>
    <label class="form-check-label" for="include_analysis">
        <i class="la la-chart-line"></i> Inclure l'analyse automatique et les commentaires
    </label>
</div>
                <div class="form-group mt-3">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="includeDetail" name="include_detail" value="1" checked>
                        <label class="custom-control-label font-weight-bold" for="includeDetail">
                            Inclure le détail des demandes
                        </label>
                    </div>
                    <small class="text-muted">Si décoché, seules les statistiques globales seront affichées.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-file-pdf-o"></i> Générer le PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setPeriod(type) {
    const today = new Date();
    let start, end = today.toISOString().split('T')[0];

    if (type === 'week') {
        const d = new Date(today);
        d.setDate(today.getDate() - today.getDay() + 1);
        start = d.toISOString().split('T')[0];
    } else if (type === 'month') {
        start = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
    } else if (type === 'quarter') {
        const q = Math.floor(today.getMonth() / 3);
        start = new Date(today.getFullYear(), q * 3, 1).toISOString().split('T')[0];
    } else if (type === 'year') {
        start = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
    } else if (type === 'all') {
        start = '2020-01-01';
        end   = today.toISOString().split('T')[0];
    }

    document.querySelector('#exportPdfModal input[name="start_date"]').value = start;
    document.querySelector('#exportPdfModal input[name="end_date"]').value   = end;
}
</script>
@endsection