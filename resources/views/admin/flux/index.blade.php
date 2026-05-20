@extends('admin.layouts.app')

@section('title', 'Gestion du Flux Migratoire')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.2/css/flag-icons.min.css"/>
    <style>
        .stats-card {
            transition: all 0.3s ease; border: none;
            border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;
        }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.15); }
        .card-container { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
        .table thead th {
            background-color: #f8f9fa; text-transform: uppercase;
            font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 700;
            color: #555; border-bottom: 2px solid #eef2f5;
        }
        .table tbody td { vertical-align: middle !important; border-top: 1px solid #f4f7f9; }
        .badge-pill { padding: 0.6em 1.2em; font-weight: 600; border: none; }
        .badge-light-info { background-color: #e0f7fa; color: #00acc1; }
        .badge-light-danger { background-color: #fbe9e7; color: #d32f2f; }
        .fi { border-radius: 3px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-glow { box-shadow: 0 0 15px rgba(102, 110, 232, 0.4); }
        .action-btn { border-radius: 8px; }
        .avatar-icon {
            width: 35px; height: 35px; display: flex; align-items: center;
            justify-content: center; border-radius: 10px;
            background: rgba(102, 110, 232, 0.1); color: #666ee8;
        }
        .filter-card {
            background: white; border-radius: 14px; padding: 18px;
            border: 1px solid #edf2f9; margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .filter-card label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .filter-card .form-control { border-radius: 8px; font-size: 13px; }
        .active-filters { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
        .filter-tag {
            background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe;
            border-radius: 20px; padding: 2px 10px; font-size: 11px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .filter-tag a { color: #3b82f6; text-decoration: none; font-weight: 700; }
    </style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">

            {{-- STATS --}}
            <div class="row animate__animated animate__fadeInDown">
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card stats-card bg-gradient-x-info white">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h3 class="white mb-0">{{ number_format($flux->sum('total_entree'), 0, ',', ' ') }}</h3>
                                        <span class="font-small-3">Total Entrées</span>
                                    </div>
                                    <div class="align-self-center"><i class="ft-arrow-down-left font-large-2 opacity-50"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card stats-card bg-gradient-x-danger white">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h3 class="white mb-0">{{ number_format($flux->sum('total_sortie'), 0, ',', ' ') }}</h3>
                                        <span class="font-small-3">Total Sorties</span>
                                    </div>
                                    <div class="align-self-center"><i class="ft-arrow-up-right font-large-2 opacity-50"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card stats-card bg-gradient-x-success white">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h3 class="white mb-0">{{ $flux->count() }}</h3>
                                        <span class="font-small-3">Enregistrements</span>
                                    </div>
                                    <div class="align-self-center"><i class="ft-check-square font-large-2 opacity-50"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card stats-card bg-gradient-x-warning white">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-left">
                                        <h3 class="white mb-0">{{ $flux->unique('pays_id')->count() }}</h3>
                                        <span class="font-small-3">Nationalités</span>
                                    </div>
                                    <div class="align-self-center"><i class="ft-globe font-large-2 opacity-50"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTRES --}}
            <div class="filter-card animate__animated animate__fadeIn">
                <form method="GET" action="{{ route('flux.index') }}">
                    <div class="row align-items-end">

                        <div class="col-md-2 mb-2">
                            <label>Frontière</label>
                            <select name="frontiere_id" class="form-control form-control-sm">
                                <option value="">Toutes</option>
                                @foreach($frontieres as $f)
                                    <option value="{{ $f->id }}" {{ request('frontiere_id') == $f->id ? 'selected' : '' }}>
                                        {{ $f->lib_frontiere }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label>Nationalité</label>
                            <select name="pays_id" class="form-control form-control-sm">
                                <option value="">Toutes</option>
                                @foreach($pays as $p)
                                    <option value="{{ $p->id }}" {{ request('pays_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->lib_pays }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label>Type de flux</label>
                            <select name="type_flux" class="form-control form-control-sm">
                                <option value="">Tous</option>
                                <option value="entree" {{ request('type_flux') === 'entree' ? 'selected' : '' }}>Entrées uniquement</option>
                                <option value="sortie" {{ request('type_flux') === 'sortie' ? 'selected' : '' }}>Sorties uniquement</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label>Du</label>
                            <input type="date" name="date_debut" class="form-control form-control-sm"
                                   value="{{ request('date_debut') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <label>Au</label>
                            <input type="date" name="date_fin" class="form-control form-control-sm"
                                   value="{{ request('date_fin') }}">
                        </div>

                        <div class="col-md-2 mb-2 d-flex" style="gap:6px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill" style="border-radius:8px;">
                                <i class="ft-search"></i> Filtrer
                            </button>
                            <a href="{{ route('flux.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                                <i class="ft-x"></i>
                            </a>
                        </div>

                    </div>

                    {{-- TAGS filtres actifs --}}
                    @if(request()->hasAny(['frontiere_id', 'pays_id', 'type_flux', 'date_debut', 'date_fin']))
                    <div class="active-filters mt-2">
                        <span class="text-muted small mr-1">Filtres actifs :</span>
                        @if(request('frontiere_id'))
                            <span class="filter-tag">
                                Frontière : {{ $frontieres->firstWhere('id', request('frontiere_id'))?->lib_frontiere }}
                                <a href="{{ request()->fullUrlWithoutQuery(['frontiere_id']) }}">×</a>
                            </span>
                        @endif
                        @if(request('pays_id'))
                            <span class="filter-tag">
                                Pays : {{ $pays->firstWhere('id', request('pays_id'))?->lib_pays }}
                                <a href="{{ request()->fullUrlWithoutQuery(['pays_id']) }}">×</a>
                            </span>
                        @endif
                        @if(request('type_flux'))
                            <span class="filter-tag">
                                Type : {{ request('type_flux') === 'entree' ? 'Entrées' : 'Sorties' }}
                                <a href="{{ request()->fullUrlWithoutQuery(['type_flux']) }}">×</a>
                            </span>
                        @endif
                        @if(request('date_debut'))
                            <span class="filter-tag">
                                Du : {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['date_debut']) }}">×</a>
                            </span>
                        @endif
                        @if(request('date_fin'))
                            <span class="filter-tag">
                                Au : {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['date_fin']) }}">×</a>
                            </span>
                        @endif
                    </div>
                    @endif

                </form>
            </div>

            {{-- TABLEAU --}}
            <div class="row animate__animated animate__fadeInUp">
                <div class="col-12">
                    <div class="card card-container">
                        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-2">
                            <div>
                                <h4 class="card-title mb-0" style="font-weight: 700;">
                                    <i class="ft-list mr-1 text-primary"></i> Journal des Mouvements
                                </h4>
                                <small class="text-muted">
                                    {{ $flux->count() }} enregistrement(s) affiché(s)
                                </small>
                            </div>
                            <div class="heading-elements">
                                <a href="{{ route('flux.create') }}" class="btn btn-primary btn-glow px-2 action-btn shadow">
                                    <i class="ft-plus"></i> Ajouter une donnée
                                </a>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Frontière</th>
                                                <th>Flux Entrée</th>
                                                <th>Flux Sortie</th>
                                                <th>Nationalité</th>
                                                <th>Date de mouvement</th>
                                                <th>Date de saisie</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($flux as $index => $flu)
                                                <tr>
                                                    <td class="text-muted font-weight-bold">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-icon mr-2">
                                                                <i class="ft-map-pin"></i>
                                                            </div>
                                                            <span class="text-bold-600">{{ $flu->frontiere?->lib_frontiere ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-info badge-pill shadow-sm">
                                                            <i class="ft-download-cloud mr-1"></i>
                                                            {{ number_format($flu->total_entree, 0, ',', ' ') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-danger badge-pill shadow-sm">
                                                            <i class="ft-upload-cloud mr-1"></i>
                                                            {{ number_format($flu->total_sortie, 0, ',', ' ') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($flu->pays && $flu->pays->code)
                                                                <span class="fi fi-{{ strtolower($flu->pays->code) }} fis mr-2 shadow-sm"
                                                                      style="width:24px;height:18px;"></span>
                                                            @else
                                                                <i class="ft-flag text-muted mr-2"></i>
                                                            @endif
                                                            <span class="text-bold-600">{{ $flu->pays?->lib_pays }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-muted">
                                                        <i class="ft-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($flu->date_movement)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-muted">
                                                        <i class="ft-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($flu->updated_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-icon btn-pure btn-outline-dark dropdown-toggle"
                                                                    type="button" data-toggle="dropdown">
                                                                <i class="ft-more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animate__animated animate__fadeIn">
                                                                <a class="dropdown-item py-1" href="{{ route('flux.edit', $flu->id) }}">
                                                                    <i class="ft-edit-2 text-info mr-50"></i> Modifier
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <form action="{{ route('flux.destroy', $flu->id) }}" method="POST"
                                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item py-1 text-danger">
                                                                        <i class="ft-trash-2 mr-50"></i> Supprimer
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-5">
                                                        <div class="empty-state text-muted">
                                                            <i class="ft-info font-large-3 d-block mb-1"></i>
                                                            <p>Aucune donnée migratoire trouvée.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.zero-configuration').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json" },
            "pageLength": 10,
            "order": [[ 5, "desc" ]],
            "drawCallback": function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-separate pagination-flat');
                $('.dataTables_filter input').addClass('form-control shadow-sm').attr('placeholder', 'Rechercher...');
                $('.dataTables_length select').addClass('form-control');
            }
        });
    });
</script>
@endsection