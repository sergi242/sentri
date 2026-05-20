@extends('admin.layouts.app')

@section('title', $status)

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.98);
        /* Dégradé Ambre/Orange pour le contentieux */
        --primary-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
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
    .btn-action-trigger:hover { background: white; color: #fda085; transform: scale(1.1); }

    .popover { border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border-radius: 15px; min-width: 220px; }
    .pop-action-item {
        display: flex; align-items: center; padding: 10px 15px; margin-bottom: 5px;
        border-radius: 10px; color: #2d3436; text-decoration: none !important;
        transition: 0.2s; background: #f8f9fa; width: 100%; border: none;
    }
    .pop-action-item:hover { background: #fda085; color: white !important; transform: translateX(5px); }

    .avatar-wrapper { position: absolute; bottom: -40px; z-index: 2; }
    .modern-avatar {
        width: 95px; height: 95px; border-radius: 28px;
        border: 4px solid #fff; object-fit: cover;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .type-label {
        font-size: 0.75rem; font-weight: 600; color: #e67e22;
        background: rgba(230, 126, 34, 0.1);
        padding: 2px 12px; border-radius: 20px;
        display: inline-block; margin-top: 5px;
    }

    .info-section { padding: 0 20px 20px 20px; }
    .name-title { font-size: 1.2rem; font-weight: 800; color: #2d3436; text-decoration: none !important; }
    .stat-pill { background: #f1f3f9; border-radius: 15px; padding: 12px; margin: 18px 0; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-4 align-items-center">
            <div class="col-md-7">
                <h1 class="font-weight-bold text-uppercase">{{ $status }}</h1>
                <p class="text-muted">Dossiers en cours de litige ou nécessitant une vérification approfondie.
                    <br><small><a href="?layout=table" class="text-warning fw-600"><i class="la la-table"></i> Mode tableau classique</a></small>
                </p>
            </div>
            <div class="col-md-5 text-md-right">
                <input type="text" id="searchDemande" class="form-control border-0 shadow-sm round d-inline-block" placeholder="Rechercher un dossier..." style="width: 250px;">
            </div>
            {{-- BOUTON --}}
<div class="col-12 mt-3">
    <button type="button" class="btn btn-danger btn-lg shadow"
            data-toggle="modal" data-target="#addContentieuxModal">
        <i class="la la-plus-circle"></i> Ajouter un dossier au contentieux
    </button>
</div>
        </div>

        <div class="content-body">
            <div class="row" id="demandeContainer">
                @forelse ($demandes as $key => $demande)
                <div class="col-xl-4 col-md-6 col-12 demande-card-wrapper" style="animation-delay: {{ $key * 0.05 }}s">
                    <div class="card modern-card shadow-sm">
                        
                        <div class="photo-container">
                            <div class="card-banner"></div>
                            
                            @php
                                $pays = $demande->impetrant?->pays;
                                $flagPath = $pays && $pays->code ? 'res/flags/' . strtolower(trim($pays->code)) . '.png' : null;
                            @endphp

                            @if($flagPath && file_exists(public_path($flagPath)))
                                <div class="flag-container" title="{{ $pays->lib_pays }}">
                                    <img src="{{ asset($flagPath) }}" class="flag-img">
                                </div>
                            @endif

                            <div class="btn-action-trigger" 
                                 data-toggle="popover" 
                                 data-html="true" 
                                 data-placement="bottom"
                                 data-content='
                                    <div class="d-flex flex-column">
                                        <a href="{{ route("impetrants.demandes", $demande->impetrant?->id) }}" class="pop-action-item">
                                            <i class="la la-user-tie"></i> Voir la fiche
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <x-demandes.demande-actions :demande="$demande" />
                                    </div>'>
                                <i class="la la-ellipsis-v"></i>
                            </div>
                            
                            <a href="{{ route('impetrants.demandes', $demande->impetrant?->id) }}" class="avatar-wrapper">
                                <img src="{{asset('app/'.$demande->photo)}}" class="modern-avatar" onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">
                            </a>
                        </div>

                        <div class="card-body info-section text-center">
                            <a href="{{ route('impetrants.demandes', $demande->impetrant?->id) }}" class="name-title d-block mb-0">
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
                                    <span class="font-weight-bold text-warning">{{$demande->validite}} an(s)</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Statut</small>
                                    <span class="badge badge-warning badge-pill font-small-1">Litige</span>
                                </div>
                            </div>

                            <div class="text-muted font-small-2">
                                <i class="la la-clock"></i> Soumis le {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="la la-folder-open text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-2">Aucun dossier en contentieux actuellement.</p>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $demandes->links('admin.pagination.pagination') }}
            </div>
        </div>
    </div>
</div>
{{-- MODAL AJOUT CONTENTIEUX --}}
<div class="modal fade" id="addContentieuxModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">

            <div class="modal-header" style="background:linear-gradient(135deg,#f6d365,#fda085);">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="la la-gavel"></i> Ajouter un dossier au contentieux
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">

                {{-- RECHERCHE --}}
                <div class="form-group">
                    <label class="font-weight-bold">
                        <i class="la la-search"></i> Rechercher par numéro de dossier, nom ou prénom
                    </label>
                    <input type="text" id="ctxSearch"
                           class="form-control form-control-lg"
                           placeholder="Ex: 1234, YANDZA, LECKON..."
                           autocomplete="off">
                    <small class="text-muted">Minimum 2 caractères</small>
                </div>

                {{-- RÉSULTATS --}}
                <div id="ctxResults" class="mb-3" style="display:none;">
                    <label class="font-weight-bold text-muted">Résultats :</label>
                    <div id="ctxResultList"></div>
                </div>

                {{-- DOSSIER SÉLECTIONNÉ --}}
                <div id="ctxSelected" style="display:none;">
                    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <i class="la la-check-circle text-warning"></i>
                            <strong>Dossier sélectionné :</strong>
                            <span id="ctxSelectedLabel"></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="ctxClear">
                            <i class="la la-times"></i> Changer
                        </button>
                    </div>

                    {{-- ALERTE SOIT-TRANSMIS --}}
                    <div id="ctxSoitTransmisAlert" class="alert alert-danger" style="display:none;">
                        <i class="la la-exclamation-triangle"></i>
                        Ce dossier est actuellement dans le soit-transmis
                        <strong id="ctxSoitTransmisNum"></strong>.
                        Il sera <strong>retiré automatiquement</strong> du soit-transmis.
                    </div>

                    {{-- FORMULAIRE --}}
                    <form action="{{ route('demandes.store.contentieux.global') }}" method="POST" id="ctxForm">
                        @csrf
                        <input type="hidden" name="demande_id" id="ctxDemandeId">

                        <div class="form-group">
                            <label class="font-weight-bold">Motif *</label>
                            <select name="motifs_id" class="form-control" required>
                                <option value="">-- Sélectionner un motif --</option>
                                @foreach(\App\Models\MotifContentieux::all() as $motif)
                                    <option value="{{ $motif->id }}">{{ $motif->lib_motif }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Description <small class="text-muted">(optionnel)</small></label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Précisez le motif..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger btn-block btn-lg">
                            <i class="la la-gavel"></i> Confirmer l'envoi au contentieux
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let searchTimer = null;

    $('#ctxSearch').on('input', function() {
        const q = $(this).val().trim();
        clearTimeout(searchTimer);

        if (q.length < 2) {
            $('#ctxResults').hide();
            return;
        }

        searchTimer = setTimeout(function() {
            $.get('{{ route("demandes.search.contentieux") }}', { q: q }, function(data) {
                $('#ctxResultList').empty();

                if (data.length === 0) {
                    $('#ctxResultList').html('<p class="text-muted text-center py-2">Aucun dossier trouvé.</p>');
                } else {
                    data.forEach(function(d) {
                        const soitTransmisInfo = d.soit_transmis
                            ? `<span class="badge badge-warning ml-1"><i class="la la-paper-plane"></i> ST: ${d.soit_transmis}</span>`
                            : '';

                        $('#ctxResultList').append(`
                            <div class="ctx-result-item border rounded p-3 mb-2"
                                 style="cursor:pointer;transition:all .2s;"
                                 data-id="${d.id}"
                                 data-label="N°${d.uuid} — ${d.nom} ${d.prenom}"
                                 data-soit-transmis="${d.soit_transmis || ''}"
                                 onmouseover="this.style.background='#fff3e0'"
                                 onmouseout="this.style.background='#fff'">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${d.nom} ${d.prenom}</strong>
                                        <span class="text-muted ml-2">${d.date_naissance}</span>
                                        ${soitTransmisInfo}
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-secondary">N° ${d.uuid}</span>
                                        <small class="d-block text-muted mt-1">${d.type_demande}</small>
                                    </div>
                                </div>
                                <small class="text-muted">Statut : ${d.statut}</small>
                            </div>
                        `);
                    });
                }

                $('#ctxResults').show();
            });
        }, 400);
    });

    // Sélection d'un résultat
    $(document).on('click', '.ctx-result-item', function() {
        const id          = $(this).data('id');
        const label       = $(this).data('label');
        const soitTransmis= $(this).data('soit-transmis');

        $('#ctxDemandeId').val(id);
        $('#ctxSelectedLabel').text(label);
        $('#ctxResults').hide();
        $('#ctxSearch').val('');

        // Alerte soit-transmis
        if (soitTransmis) {
            $('#ctxSoitTransmisNum').text(soitTransmis);
            $('#ctxSoitTransmisAlert').show();
        } else {
            $('#ctxSoitTransmisAlert').hide();
        }

        $('#ctxSelected').show();
    });

    // Réinitialiser la sélection
    $('#ctxClear').on('click', function() {
        $('#ctxSelected').hide();
        $('#ctxDemandeId').val('');
        $('#ctxSearch').val('').focus();
    });

    // Reset modal à la fermeture
    $('#addContentieuxModal').on('hidden.bs.modal', function() {
        $('#ctxSearch').val('');
        $('#ctxResults').hide();
        $('#ctxSelected').hide();
        $('#ctxDemandeId').val('');
        $('#ctxSoitTransmisAlert').hide();
    });
});
</script>
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

        $("#searchDemande").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#demandeContainer .demande-card-wrapper").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection