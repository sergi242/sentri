@extends('admin.layouts.app')

@section('title', $status)

@section('styles')
<style>
    /* Style pour la modale pro */
    .modal-content { border-radius: 20px; border: none; }
    .modal-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; }
    .btn-indigo { background: #764ba2; color: white; transition: 0.3s; }
    .btn-indigo:hover { background: #5a368a; color: white; box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3); }

    :root {
        --glass-bg: rgba(255, 255, 255, 0.95);
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .demande-card-wrapper { animation: fadeInScale 0.4s ease-out backwards; }

    .modern-card {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .modern-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(118, 75, 162, 0.15) !important; }

    .photo-header {
        position: relative;
        height: 120px;
        background: var(--primary-gradient);
        display: flex;
        justify-content: center;
        margin-bottom: 45px;
    }

    .badge-top-left {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        color: white;
        padding: 4px 12px;
        border-radius: 10px;
        font-size: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .action-btn-float {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        color: #764ba2;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: 0.2s;
    }
    .action-btn-float:hover { background: #764ba2; color: white; transform: rotate(90deg); }

    /* Style des filtres CRT/VISA */
    .btn-outline-indigo { border-color: #764ba2; color: #764ba2; }
    .btn-outline-indigo:hover, .btn-outline-indigo.active { background: #764ba2 !important; color: white !important; }
    .btn-group.round { border-radius: 30px; overflow: hidden; }

    .avatar-main {
        position: absolute;
        bottom: -35px;
        width: 85px;
        height: 85px;
        border-radius: 22px;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        background: #f0f0f0;
    }

    .info-body { padding: 0 20px 20px 20px; }
    .name-link { font-size: 1.15rem; font-weight: 700; color: #2d3436; transition: 0.2s; }
    .name-link:hover { color: #667eea; }

    .meta-box {
        background: #f9faff;
        border-radius: 18px;
        padding: 12px;
        margin: 15px 0;
        border: 1px solid #edf2f7;
    }

    .label-small { font-size: 0.65rem; text-transform: uppercase; color: #a0aec0; letter-spacing: 0.5px; font-weight: 600; }
    .value-medium { font-size: 0.85rem; font-weight: 700; color: #4a5568; }

    .popover-body { padding: 10px; }
    .custom-popover-item {
        padding: 8px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #4a5568;
        text-decoration: none !important;
        transition: background 0.2s;
    }
    .custom-popover-item:hover { background: #f0f4ff; color: #667eea; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-end">
                <div>
                    <h2 class="text-uppercase font-weight-bold mb-0" style="letter-spacing: 1px;">{{ $status }}</h2>
                    <p class="text-muted">Dossiers reçus en attente d'attribution.</p>
                </div>
                <div class="mb-1">
                    <a href="?layout=table" class="btn btn-outline-primary btn-sm round px-2">
                        <i class="la la-list"></i> Vue Liste
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row mb-2">
                <div class="col-12 d-flex justify-content-center">
                    <div class="btn-group shadow-sm round">
                        <button type="button" class="btn btn-outline-indigo px-3 active filter-btn" data-type="CRT">
                            <i class="la la-file-text"></i> Dossiers CRT
                        </button>
                        <button type="button" class="btn btn-outline-indigo px-3 filter-btn" data-type="VISA">
                            <i class="la la-certificate"></i> Dossiers VISA
                        </button>
                        <button type="button" class="btn btn-outline-indigo px-3 filter-btn" data-type="ALL">
                            Tous
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="position-relative has-icon-left">
                        <input type="text" id="searchDemande" class="form-control round shadow-sm" placeholder="Rechercher par nom, pays ou type...">
                        <div class="form-control-position">
                            <i class="la la-search"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="demandesContainer">
                @forelse ($demandes as $key => $demande)
                <div class="col-xl-4 col-md-6 col-12 demande-card-wrapper demande-item" 
                     style="animation-delay: {{ $key * 0.04 }}s"
                     data-search="{{ strtolower($demande->impetrant?->nomcomplet() . ' ' . $demande->type_demande . ' ' . ($demande->impetrant?->pays?->lib_pays ?? '')) }}"
                    data-type="{{ $demande->type_demande === 'Visa' ? 'VISA' : 'CRT' }}">
                    
                    <div class="card modern-card shadow-sm">
                        <div class="photo-header">
                            <span class="badge-top-left">
                                <i class="la la-calendar"></i> 
                                @if($demande->created_at->isToday())
                                    Auj. {{ $demande->created_at->format('H:i') }}
                                @else
                                    {{ $demande->created_at->format('d M Y') }}
                                @endif
                            </span>

                            <div class="action-btn-float" 
                                 data-toggle="popover" 
                                 data-trigger="click"
                                 data-html="true" 
                                 data-placement="left"
                                 data-content='
                                    <div class="d-flex flex-column">
                                        <a href="{{route("demandes.show", $demande->id)}}" class="custom-popover-item"><i class="la la-eye"></i> Consulter</a>
                                        <a href="{{route("demandes.edit", $demande->id)}}" class="custom-popover-item"><i class="la la-edit"></i> Modifier</a>
                                        <div class="dropdown-divider"></div>
                                        <x-demandes.demande-actions :demande="$demande" />
                                    </div>'>
                                <i class="la la-cog"></i>
                            </div>

                            <img src="{{ asset('app/'.$demande->photo) }}" class="avatar-main" onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                        </div>

                        <div class="info-body text-center">
                            <a href="{{route('demandes.show', $demande->id)}}" class="name-link d-block mb-0">
                                {{ $demande->impetrant?->nomcomplet() }}
                            </a>
                            <span class="badge badge-light-primary badge-pill font-small-2 type-badge">
                                {{ $demande->type_demande }}
                            </span>

                            <div class="meta-box">
                                <div class="row no-gutters">
                                    <div class="col-6 border-right">
                                        <div class="label-small">Nationalité</div>
                                        <div class="value-medium">{{ $demande->impetrant?->pays?->lib_pays ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="label-small">Validité</div>
                                        <div class="value-medium">{{ $demande->validite }} an(s)</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="text-left">
                                    <div class="label-small">Sexe</div>
                                    <div class="value-medium text-dark">{{ $demande->impetrant?->sexe }}</div>
                                </div>
                                <button class="btn btn-sm btn-indigo btn-glow px-2 round shadow btn-attribution" 
                                    data-toggle="modal" 
                                    data-target="#modalAttribution"
                                    data-id="{{ $demande->id }}"
                                    data-name="{{ $demande->impetrant?->nomcomplet() }}"
                                    data-type="{{ $demande->type_demande }}">
                                    Attribuer <i class="la la-user-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="empty-state">
                        <i class="la la-clipboard-check text-muted" style="font-size: 5rem; opacity: 0.2;"></i>
                        <h4 class="text-muted mt-2">Aucune demande en attente.</h4>
                    </div>
                </div>
                @endforelse

                <div id="noResults" class="col-12 text-center py-5 d-none">
                    <h4 class="text-muted">Aucun résultat ne correspond à votre recherche.</h4>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $demandes->links('admin.pagination.pagination') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAttribution" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="la la-edit"></i> Finaliser l'Attribution</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAttribution" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p id="modalSubTitle" class="text-muted mb-2"></p>
                    <div class="form-group">
                        <label class="label-control font-weight-bold">Numéro du document *</label>
                        <input type="text" name="numero_document" class="form-control" placeholder="Ex: VSA-99820" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control font-weight-bold">Date d'émission *</label>
                        <input type="date" name="date_emission" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light round" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-indigo round px-3">
                        Confirmer <i class="la la-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        
        // 1. Initialisation des Popovers
        $('[data-toggle="popover"]').popover({ 
            sanitize: false,
            template: '<div class="popover shadow-lg border-0" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        });

        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        // 2. Logique de Filtrage
        function filterCards() {
    var searchTerm = $("#searchDemande").val().toLowerCase();
    var activeFilter = $(".filter-btn.active").data('type');
    var visibleCount = 0;

    $(".demande-item").each(function() {
        var searchData = ($(this).data("search") || "").toLowerCase();
        var cardType = $(this).data("type");

        var matchesSearch = searchData.indexOf(searchTerm) > -1;
        var matchesFilter = (activeFilter === "ALL" || cardType === activeFilter);

        if (matchesSearch && matchesFilter) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });

    $("#noResults").toggleClass("d-none", visibleCount !== 0);
}


        // Ecouteur Recherche
        $("#searchDemande").on("keyup", filterCards);
        
        // Ecouteur Boutons Filtre
        $(".filter-btn").on("click", function() {
            $(".filter-btn").removeClass("active");
            $(this).addClass("active");
            filterCards();
        });

        // 3. Gestion de la Modale d'Attribution
        $(document).on('click', '.btn-attribution', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var type = $(this).data('type');
            
            var actionRoute = "{{ route('demandes.storeremplirformation', ':id') }}";
            actionRoute = actionRoute.replace(':id', id);
            
            $('#formAttribution').attr('action', actionRoute);
            $('#modalSubTitle').html("Attribution pour : <strong>" + name + "</strong> (" + type + ")");
        });

        // 4. Lancement initial
        filterCards();
    });
</script>
@endsection