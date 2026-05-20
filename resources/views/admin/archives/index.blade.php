@extends('admin.layouts.app')
@section('title') Service d'Archivage @endsection

@section('content')
<style>
    .arch-wrapper { padding: 1.5rem; background: #f4f7fa; min-height: 100vh; }

    .page-header {
        background: linear-gradient(135deg, #2d3748, #4a5568);
        color: white; border-radius: 14px; padding: 22px 28px; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;
    }

    .filter-card {
        background: white; border-radius: 14px; padding: 20px 24px;
        border: 1px solid #edf2f9; margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .filter-label {
        font-size: 11px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block;
    }

    .imp-row {
        background: white; border-radius: 12px; padding: 14px 20px;
        border: 1px solid #edf2f9; margin-bottom: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        transition: 0.2s; display: flex; align-items: center; gap: 16px;
    }
    .imp-row:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-1px); border-color: #dbeafe; }

    .imp-photo {
        width: 54px; height: 54px; border-radius: 12px;
        object-fit: cover; border: 2px solid #edf2f9; flex-shrink: 0;
    }
    .imp-photo-placeholder {
        width: 54px; height: 54px; border-radius: 12px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    .doc-badge {
        padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap;
    }
    .doc-badge.has  { background: #eff6ff; color: #4834d4; border: 1px solid #bfdbfe; }
    .doc-badge.none { background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; }

    .filter-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: #eff6ff; color: #4834d4; border: 1px solid #bfdbfe;
        border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600;
    }
    .filter-tag a { color: #94a3b8; text-decoration: none; font-size: 13px; line-height: 1; margin-left: 2px; }
    .filter-tag a:hover { color: #dc2626; }

    .meta-pill {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; color: #64748b;
    }
    .meta-pill i { font-size: 11px; }

    .results-bar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 12px; padding: 0 4px;
    }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <div class="arch-wrapper">

                {{-- ══ HEADER ══ --}}
                <div class="page-header">
                    <div>
                        <h4 class="mb-1 font-weight-bold">
                            <i class="la la-archive mr-2"></i> Service d'Archivage
                        </h4>
                        <p class="mb-0 small" style="opacity:.75;">
                            Archivage et gestion des documents des impétrants
                        </p>
                    </div>
                    <span style="background:rgba(255,255,255,0.15);padding:8px 18px;border-radius:20px;font-size:13px;font-weight:700;">
                        <i class="la la-users mr-1"></i> {{ $impetrants->total() }} impétrant(s)
                    </span>
                </div>

                {{-- ══ FILTRES ══ --}}
                <div class="filter-card">
                    <form method="GET" action="{{ route('archives.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="filter-label">Nom / Prénom</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8fafc;border-right:0;">
                                            <i class="la la-search text-muted" style="font-size:13px;"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                           style="border-radius:0 8px 8px 0;"
                                           placeholder="Rechercher..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="filter-label">Nationalité</label>
                                <select name="pays_id" class="form-control form-control-sm" style="border-radius:8px;">
                                    <option value="">Toutes</option>
                                    @foreach($pays as $p)
                                    <option value="{{ $p->id }}" {{ request('pays_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->lib_pays }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="filter-label">Numéro de document</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8fafc;border-right:0;">
                                            <i class="la la-hashtag text-muted" style="font-size:13px;"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="numero_document" class="form-control form-control-sm"
                                           style="border-radius:0 8px 8px 0;"
                                           placeholder="Ex: AB123456..."
                                           value="{{ request('numero_document') }}">
                                </div>
                            </div>

                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="filter-label">Documents archivés</label>
                                <select name="avec_docs" class="form-control form-control-sm" style="border-radius:8px;">
                                    <option value="">Tous</option>
                                    <option value="1" {{ request('avec_docs') === '1' ? 'selected' : '' }}>Avec documents</option>
                                    <option value="0" {{ request('avec_docs') === '0' ? 'selected' : '' }}>Sans documents</option>
                                </select>
                            </div>

                            <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0" style="gap:6px;">
                                <button type="submit" class="btn btn-primary btn-sm btn-block" style="border-radius:8px;">
                                    <i class="la la-search"></i>
                                </button>
                                @if(request()->hasAny(['search','pays_id','avec_docs','numero_document']))
                                <a href="{{ route('archives.index') }}"
                                   class="btn btn-outline-secondary btn-sm"
                                   style="border-radius:8px;flex-shrink:0;"
                                   title="Réinitialiser">
                                    <i class="la la-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Tags filtres actifs --}}
                    @if(request()->hasAny(['search','pays_id','avec_docs','numero_document']))
                    <div class="d-flex flex-wrap align-items-center mt-3 pt-3" style="border-top:1px solid #f1f5f9;gap:6px;">
                        <small class="text-muted font-weight-bold mr-1">Filtres actifs :</small>
                        @if(request('search'))
                        <span class="filter-tag">
                            <i class="la la-search" style="font-size:11px;"></i>
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery('search') }}">×</a>
                        </span>
                        @endif
                        @if(request('pays_id'))
                        <span class="filter-tag">
                            <i class="la la-globe" style="font-size:11px;"></i>
                            {{ $pays->firstWhere('id', request('pays_id'))?->lib_pays }}
                            <a href="{{ request()->fullUrlWithoutQuery('pays_id') }}">×</a>
                        </span>
                        @endif
                        @if(request('numero_document'))
                        <span class="filter-tag">
                            <i class="la la-hashtag" style="font-size:11px;"></i>
                            {{ request('numero_document') }}
                            <a href="{{ request()->fullUrlWithoutQuery('numero_document') }}">×</a>
                        </span>
                        @endif
                        @if(request('avec_docs') !== null && request('avec_docs') !== '')
                        <span class="filter-tag">
                            <i class="la la-file" style="font-size:11px;"></i>
                            {{ request('avec_docs') === '1' ? 'Avec documents' : 'Sans documents' }}
                            <a href="{{ request()->fullUrlWithoutQuery('avec_docs') }}">×</a>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- ══ RÉSULTATS ══ --}}
                @if($impetrants->count() === 0)
                <div class="text-center py-5" style="background:white;border-radius:14px;border:1px solid #edf2f9;">
                    <i class="la la-search" style="font-size:3.5rem;color:#cbd5e1;"></i>
                    <p class="mt-2 mb-3 font-weight-bold text-muted">Aucun impétrant trouvé</p>
                    <a href="{{ route('archives.index') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
                        <i class="la la-times mr-1"></i> Réinitialiser les filtres
                    </a>
                </div>
                @else

                <div class="results-bar">
                    <small class="text-muted">
                        <strong>{{ $impetrants->firstItem() }}–{{ $impetrants->lastItem() }}</strong>
                        sur <strong>{{ $impetrants->total() }}</strong> impétrant(s)
                    </small>
                    <small class="text-muted">
                        Page <strong>{{ $impetrants->currentPage() }}</strong> / {{ $impetrants->lastPage() }}
                    </small>
                </div>

                @foreach($impetrants as $imp)
                    @php
                        $photo = $imp->demandes->firstWhere(fn($d) => !empty($d->photo))?->photo;
                    @endphp
                <div class="imp-row">

                    {{-- Photo --}}
                    @if($photo)
                        <img src="{{ asset('app/'.$photo) }}" class="imp-photo">
                    @else
                        <div class="imp-photo-placeholder">
                            <i class="la la-user" style="font-size:20px;color:#94a3b8;"></i>
                        </div>
                    @endif

                    {{-- Identité --}}
                    <div style="flex:1;min-width:0;">
                        <div class="font-weight-bold text-uppercase" style="font-size:13px;color:#1e293b;letter-spacing:0.3px;">
                            {{ $imp->nom }} {{ $imp->prenom }}
                        </div>
                        <div class="d-flex flex-wrap mt-1" style="gap:12px;">
                            @if($imp->pays)
                            <span class="meta-pill">
                                <i class="la la-globe"></i> {{ $imp->pays->lib_pays }}
                            </span>
                            @endif
                            @if($imp->date_naissance)
                            <span class="meta-pill">
                                <i class="la la-calendar"></i>
                                {{ \Carbon\Carbon::parse($imp->date_naissance)->format('d/m/Y') }}
                            </span>
                            @endif
                            @if($imp->sexe)
                            <span class="meta-pill">
                                <i class="la la-{{ $imp->sexe === 'Masculin' ? 'male' : 'female' }}"></i>
                                {{ $imp->sexe }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Badge documents --}}
                    <div style="min-width:110px;text-align:center;">
                        @if($imp->archives_count > 0)
                        <span class="doc-badge has">
                            <i class="la la-file" style="font-size:11px;"></i>
                            {{ $imp->archives_count }} document(s)
                        </span>
                        @else
                        <span class="doc-badge none">Aucun document</span>
                        @endif
                    </div>

                    {{-- Bouton --}}
                    <a href="{{ route('archives.show', $imp->id) }}"
                       class="btn btn-primary btn-sm"
                       style="border-radius:8px;flex-shrink:0;white-space:nowrap;font-size:12px;">
                        <i class="la la-archive"></i> Gérer
                    </a>
                </div>
                @endforeach

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $impetrants->appends(request()->query())->links('admin.pagination.pagination') }}
                </div>

                @endif
            </div>
        </div>
    </div>
</div>
@endsection