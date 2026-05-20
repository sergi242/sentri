@extends("admin.layouts.app")
@section("title")
    Situation des demandes — {{ $impetrant->nomcomplet() }}
@endsection
@section('styles')
<style>
.photo-timeline {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.photo-thumb {
    position: relative;
    cursor: pointer;
    border-radius: 10px;
    overflow: hidden;
    border: 3px solid #dee2e6;
    transition: all .2s;
    width: 90px;
}
.photo-thumb:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,.15);
    border-color: #1E9FF2;
}
.photo-thumb.active {
    border-color: #1E9FF2;
    box-shadow: 0 0 0 3px rgba(30,159,242,.25);
}
.photo-thumb img {
    width: 90px; height: 90px;
    object-fit: cover; object-position: top;
    display: block;
}
.photo-thumb .photo-label {
    background: rgba(0,0,0,.65);
    color: #fff;
    font-size: 9px;
    text-align: center;
    padding: 3px 4px;
    line-height: 1.2;
}
.photo-thumb .photo-badge {
    position: absolute;
    top: 4px; right: 4px;
    font-size: 9px; padding: 1px 5px;
    border-radius: 10px; font-weight: 700;
}
.photo-main-wrap {
    position: sticky; top: 80px;
}
.photo-main {
    width: 100%; border-radius: 12px;
    object-fit: cover; object-position: top;
    border: 3px solid #dee2e6;
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
    display: block; margin-bottom: 8px;
    transition: all .3s;
}
.photo-main-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
}
.badge-attente     { background:#FF9149; color:#fff; }
.badge-approuvee   { background:#28D094; color:#fff; }
.badge-rejetee     { background:#FF4961; color:#fff; }
.badge-contentieux { background:#FF9149; color:#fff; }
.badge-livree      { background:#1E9FF2; color:#fff; }
.table-demandes thead th { background:#2c3e50; color:#fff; font-size:12px; }
.table-demandes tbody tr:hover { background:#f0f4ff; }

.btn-creer-demande {
    background: linear-gradient(135deg, #28D094, #1aaa78);
    color: #fff !important;
    border: none;
    font-weight: 600;
    letter-spacing: .02em;
    box-shadow: 0 3px 10px rgba(40,208,148,.35);
    transition: box-shadow .2s, transform .1s;
}
.btn-creer-demande:hover {
    box-shadow: 0 5px 16px rgba(40,208,148,.5);
    transform: translateY(-1px);
    color: #fff !important;
}
.btn-creer-demande:active { transform: scale(.98); }

.direct-banner {
    background: linear-gradient(135deg, #f0f8ff, #e8f4fd);
    border: 1.5px dashed #1E9FF2;
    border-radius: 10px;
    padding: 1rem 1.2rem;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.direct-banner i { font-size: 2rem; color: #1E9FF2; flex-shrink: 0; }
</style>
@endsection

@section("content")
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">

            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h4 class="mb-0 font-weight-bold">
                        <i class="la la-folder text-primary"></i>
                        {{ $impetrant->nomcomplet() }}
                    </h4>
                    <small class="text-muted">
                        {{ $impetrant->demandes->count() }} demande(s) enregistrée(s)
                        · {{ $impetrant->pays?->lib_pays }}
                        @if(isset($impetrant->source) && $impetrant->source === 'direct')
                            <span class="badge badge-info ml-1">
                                <i class="la la-id-card mr-1"></i>Enregistrement direct
                            </span>
                        @endif
                    </small>
                </div>
                <div style="gap:8px;" class="d-flex flex-wrap">
                    <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                       class="btn btn-sm btn-creer-demande">
                        <i class="la la-plus-circle mr-1"></i> Créer une demande
                    </a>
                    <a href="{{ route('impetrants.casier', $impetrant->id) }}"
                       class="btn btn-dark btn-sm">
                        <i class="la la-book mr-1"></i> Casier judiciaire
                    </a>
                    @if($impetrant->demandes->count() === 0)
                    <form method="POST"
                          action="{{ route('impetrants.destroy', $impetrant->id) }}"
                          onsubmit="return confirm('Supprimer définitivement cet impétrant ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="la la-trash mr-1"></i> Supprimer l'impétrant
                        </button>
                    </form>
                    @endif
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                        <i class="la la-arrow-left mr-1"></i> Retour
                    </a>
                </div>
            </div>

            <div class="row">

                {{-- ── COLONNE GAUCHE : photo + infos ── --}}
                <div class="col-md-3">
                    <div class="photo-main-wrap">

                        {{-- Grande photo --}}
                        @php
                            $photoSrc = null;
                            if (!empty($impetrant->photo)) {
                                $photoSrc = asset('app/' . $impetrant->photo);
                            } elseif ($latestDemandePhoto) {
                                $photoSrc = asset('app/' . $latestDemandePhoto);
                            } else {
                                $photoSrc = asset('res/app-assets/images/portrait/small/avatar-s-1.png');
                            }
                        @endphp
                        <img id="main-photo"
                             src="{{ $photoSrc }}"
                             class="photo-main"
                             style="height:280px;"
                             alt="Photo"
                             onerror="this.src='{{ asset('res/app-assets/images/portrait/small/avatar-s-1.png') }}'">

                        <div class="photo-main-info mb-3" id="main-photo-info">
                            <strong id="main-photo-uuid" class="text-primary d-block"></strong>
                            <span id="main-photo-date" class="text-muted d-block" style="font-size:12px;"></span>
                            <span id="main-photo-statut" class="badge mt-1"></span>
                        </div>

                        {{-- Infos impétrant --}}
                        <div class="card">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center mb-2">
                                    @php
                                        $flagPath = $impetrant->pays?->code
                                            ? 'res/flags/'.strtolower(trim($impetrant->pays->code)).'.png'
                                            : null;
                                    @endphp
                                    @if($flagPath && file_exists(public_path($flagPath)))
                                        <img src="{{ asset($flagPath) }}"
                                             style="width:24px;height:auto;border-radius:3px;margin-right:8px;border:1px solid #dee2e6;">
                                    @endif
                                    <strong>{{ $impetrant->pays?->lib_pays ?? '—' }}</strong>
                                </div>
                                <div style="font-size:13px;" class="text-muted">
                                    <div><i class="la la-calendar mr-1"></i>
                                        {{ $impetrant->date_naissance ? date('d/m/Y', strtotime($impetrant->date_naissance)) : '—' }}
                                    </div>
                                    <div><i class="la la-user mr-1"></i>{{ $impetrant->sexe }}</div>
                                    <div><i class="la la-map-marker mr-1"></i>{{ $impetrant->lieu_naissance ?? '—' }}</div>
                                </div>

                                @if($impetrant->nom_pere || $impetrant->nom_mere)
                                <hr class="my-2">
                                <div style="font-size:12px;" class="text-muted">
                                    @if($impetrant->nom_pere)
                                    <div><i class="la la-male mr-1"></i>
                                        {{ trim($impetrant->nom_pere . ' ' . ($impetrant->prenom_pere ?? '')) ?: '—' }}
                                    </div>
                                    @endif
                                    @if($impetrant->nom_mere)
                                    <div><i class="la la-female mr-1"></i>
                                        {{ trim($impetrant->nom_mere . ' ' . ($impetrant->prenom_mere ?? '')) ?: '—' }}
                                    </div>
                                    @endif
                                </div>
                                @endif

                            </div>{{-- fin card-body --}}
                        </div>{{-- fin card infos --}}

                        {{-- ══════════════════════════════════════════ --}}
                        {{-- SECTION : Documents enregistrés           --}}
                        {{-- ══════════════════════════════════════════ --}}
                        <div class="card card-outline card-primary mt-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="la la-id-card mr-1"></i>
                                    Documents enregistrés
                                </h3>
                                <div class="card-tools">
                                    <button type="button"
                                            class="btn btn-xs btn-primary"
                                            onclick="ouvrirModalDoc()">
                                        <i class="la la-plus"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if($impetrant->documents->isEmpty())
                                    <div class="text-center text-muted py-4">
                                        <i class="la la-folder-open la-2x"></i>
                                        <p class="mt-2 mb-0">Aucun document enregistré</p>
                                    </div>
                                @else
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Type</th>
                                                <th>Numéro</th>
                                                <th>Délivrance</th>
                                                <th>Expiration</th>
                                                <th>Statut</th>
                                                <th>Source</th>
                                                <th>Saisi par</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($impetrant->documents as $doc)
                                            <tr>
                                                <td>
                                                    <i class="la la-passport mr-1 text-primary"></i>
                                                    {{ $doc->type_document }}
                                                </td>
                                                <td><code>{{ $doc->numero_document }}</code></td>
                                                <td>
                                                    {{ $doc->date_delivrance
                                                       ? $doc->date_delivrance->format('d/m/Y')
                                                       : '—' }}
                                                </td>
                                                <td>
                                                    {{ $doc->date_expiration
                                                       ? $doc->date_expiration->format('d/m/Y')
                                                       : '—' }}
                                                </td>
                                                <td>
                                                    @php $statut = $doc->statut_expiration; @endphp
                                                    @if($statut === 'Valide')
                                                        <span class="badge badge-success">Valide</span>
                                                    @elseif($statut === 'Bientôt expiré')
                                                        <span class="badge badge-warning">Bientôt expiré</span>
                                                    @elseif($statut === 'Expiré')
                                                        <span class="badge badge-danger">Expiré</span>
                                                    @else
                                                        <span class="badge badge-secondary">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($doc->source === 'lecteur')
                                                        <span class="badge badge-info">
                                                            <i class="la la-microchip"></i> Lecteur
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">
                                                            <i class="la la-keyboard"></i> Manuel
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $doc->createur ? $doc->createur->nom : '—' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>

                        {{-- Bouton créer demande (colonne gauche) --}}
                        <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                           class="btn btn-block btn-creer-demande mt-2">
                            <i class="la la-plus-circle mr-1"></i> Créer une demande
                        </a>

                    </div>{{-- fin photo-main-wrap --}}
                </div>{{-- fin col-md-3 --}}

                {{-- ── COLONNE DROITE ── --}}
                <div class="col-md-9">

                    @if($impetrant->demandes->count() === 0)
                    <div class="direct-banner">
                        <i class="la la-info-circle"></i>
                        <div>
                            <strong>Aucune demande enregistrée</strong><br>
                            <span class="text-muted" style="font-size:.85rem;">
                                Cet impétrant a été enregistré directement sans demande associée.
                                Cliquez sur <strong>Créer une demande</strong> pour lui associer un dossier.
                            </span>
                        </div>
                        <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                           class="btn btn-sm btn-creer-demande ml-auto flex-shrink-0">
                            <i class="la la-plus-circle mr-1"></i> Créer une demande
                        </a>
                    </div>
                    @endif

                    {{-- Photo impétrant direct --}}
                    @if(!empty($impetrant->photo))
                    <div class="card mb-3">
                        <div class="card-header py-2 d-flex align-items-center justify-content-between">
                            <strong><i class="la la-id-card text-primary mr-1"></i>
                                Photo d'identité de l'impétrant
                            </strong>
                            <span class="badge badge-info">Photo principale</span>
                        </div>
                        <div class="card-body py-3 d-flex align-items-center gap-3">
                            <img src="{{ asset('app/' . $impetrant->photo) }}"
                                 style="width:90px;height:110px;object-fit:cover;object-position:top;
                                        border-radius:8px;border:3px solid #1E9FF2;cursor:pointer;"
                                 onclick="document.getElementById('main-photo').src=this.src"
                                 onerror="this.parentElement.style.display='none'"
                                 alt="Photo impétrant">
                            <div class="text-muted" style="font-size:.82rem;">
                                <i class="la la-info-circle text-primary mr-1"></i>
                                Photo liée à l'impétrant. Elle apparaît par défaut sur toutes les demandes.
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Galerie photos demandes --}}
                    @php
                        $demavecPhoto = $impetrant->demandes->filter(fn($d) => !empty($d->photo));
                    @endphp
                    @if($demavecPhoto->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header py-2 d-flex align-items-center justify-content-between">
                            <strong><i class="la la-camera text-primary"></i>
                                Historique des photos ({{ $demavecPhoto->count() }})
                            </strong>
                            <small class="text-muted">Cliquez sur une photo pour l'agrandir</small>
                        </div>
                        <div class="card-body py-3">
                            <div class="photo-timeline">
                                @foreach($impetrant->demandes as $dem)
                                @if(!empty($dem->photo))
                                @php
                                    $sc = match($dem->statut_demande) {
                                        'Approuvée'              => 'approuvee',
                                        'Rejetée'                => 'rejetee',
                                        'Livrée'                 => 'livree',
                                        'Envoyée au contentieux' => 'contentieux',
                                        default                  => 'attente',
                                    };
                                @endphp
                                <div class="photo-thumb {{ $loop->first ? 'active' : '' }}"
                                     onclick="showPhoto(
                                        '{{ asset('app/'.$dem->photo) }}',
                                        '{{ $dem->uuid }}',
                                        '{{ $dem->date_demande ? date('d/m/Y', strtotime($dem->date_demande)) : '' }}',
                                        '{{ $dem->statut_demande }}',
                                        '{{ $sc }}',
                                        this
                                     )">
                                    <img src="{{ asset('app/'.$dem->photo) }}"
                                         alt="Photo {{ $dem->uuid }}"
                                         onerror="this.parentElement.style.display='none'">
                                    <span class="photo-badge badge badge-{{ $sc === 'approuvee' ? 'success' : ($sc === 'rejetee' ? 'danger' : ($sc === 'livree' ? 'info' : 'warning')) }}">
                                        {{ date('Y', strtotime($dem->created_at)) }}
                                    </span>
                                    <div class="photo-label">{{ $dem->uuid }}</div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tableau demandes --}}
                    <div class="card">
                        <div class="card-header py-2 d-flex align-items-center justify-content-between">
                            <strong><i class="la la-list text-primary"></i>
                                Toutes les demandes
                            </strong>
                            <a href="{{ route('demandes.create', ['impetrant_id' => $impetrant->id]) }}"
                               class="btn btn-sm btn-creer-demande">
                                <i class="la la-plus mr-1"></i> Nouvelle demande
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-demandes table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Référence</th>
                                        <th>Validité</th>
                                        <th>N° Document</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($impetrant->demandes as $item)
                                    @php
                                        $sc = match($item->statut_demande) {
                                            'Approuvée'              => 'success',
                                            'Rejetée'                => 'danger',
                                            'Livrée'                 => 'info',
                                            'Envoyée au contentieux' => 'warning',
                                            default                  => 'secondary',
                                        };
                                    @endphp
                                    <tr>
                                        <td style="width:52px;">
                                            @if(!empty($item->photo))
                                            <img src="{{ asset('app/'.$item->photo) }}"
                                                 style="width:42px;height:42px;border-radius:6px;object-fit:cover;object-position:top;cursor:pointer;border:2px solid #dee2e6;"
                                                 title="{{ $item->uuid }}"
                                                 onclick="showPhoto(
                                                    '{{ asset('app/'.$item->photo) }}',
                                                    '{{ $item->uuid }}',
                                                    '{{ $item->date_demande ? date('d/m/Y', strtotime($item->date_demande)) : '' }}',
                                                    '{{ $item->statut_demande }}',
                                                    '{{ match($item->statut_demande) { 'Approuvée'=>'approuvee','Rejetée'=>'rejetee','Livrée'=>'livree',default=>'attente' } }}',
                                                    null
                                                 )"
                                                 onerror="this.style.display='none'">
                                            @else
                                            <div style="width:42px;height:42px;border-radius:6px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                                                <i class="la la-user text-muted"></i>
                                            </div>
                                            @endif
                                        </td>
                                        <td>{{ $item->date_demande ? date('d/m/Y', strtotime($item->date_demande)) : '—' }}</td>
                                        <td>{{ $item->type_demande }}</td>
                                        <td><strong class="text-primary">{{ $item->uuid }}</strong></td>
                                        <td>{{ $item->validite }} an(s)</td>
                                        <td>
                                            @if($item->numero_document)
                                                <span class="badge badge-dark">{{ $item->numero_document }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $sc }}">{{ $item->statut_demande }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('demandes.show', $item->id) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="la la-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="la la-inbox" style="font-size:2.5rem;display:block;margin-bottom:.5rem;"></i>
                                            Aucune demande trouvée
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>{{-- fin col-md-9 --}}
            </div>{{-- fin row --}}

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- MODAL photo — EN DEHORS de tout conteneur                         --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalPhoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content" style="border-radius:14px; overflow:hidden;">
            <div class="modal-header py-2" id="modal-photo-header">
                <h6 class="modal-title mb-0" id="modal-photo-title"></h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <img id="modal-photo-img" src="" alt="Photo"
                 style="width:100%; max-height:500px; object-fit:cover; object-position:top;">
            <div class="modal-footer py-2 justify-content-start" id="modal-photo-footer">
                <span id="modal-photo-statut" class="badge"></span>
                <span id="modal-photo-date" class="text-muted ml-2" style="font-size:12px;"></span>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- MODAL ajout document — EN DEHORS de tout conteneur                --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAjoutDocument" tabindex="-1" role="dialog" aria-labelledby="modalAjoutDocumentLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('impetrants.documents.store', $impetrant->id) }}"
                  method="POST" id="formAjoutDoc">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="modalAjoutDocumentLabel">
                        <i class="la la-plus-circle mr-1"></i>
                        Ajouter un document
                    </h5>
                    <button type="button" class="close text-white"
                            data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Type de document <span class="text-danger">*</span></label>
                        <select name="type_document" class="form-control" required>
                            <option value="Passeport">Passeport</option>
                            <option value="Titre de voyage">Titre de voyage</option>
                            <option value="Laissez-passer">Laissez-passer</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Numéro <span class="text-danger">*</span></label>
                        <input type="text"
                               name="numero_document"
                               id="inputNumeroDoc"
                               class="form-control text-uppercase"
                               placeholder="Ex: AB1234567"
                               required
                               autocomplete="off">
                        <small id="msgVerifDoc" class="form-text"></small>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Date délivrance</label>
                                <input type="date" name="date_delivrance" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Date expiration</label>
                                <input type="date" name="date_expiration_doc" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info" id="btnLirePasseportDoc"
                            onclick="lirePasseportModal()">
                        <i class="la la-id-card mr-1"></i>
                        <span id="lblLirePasseportDoc">Lire le passeport</span>
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSauveDoc">
                        <i class="la la-save mr-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Fonction globale : doit être dans un <script> ordinaire, HORS @push et HORS document.ready --}}
{{-- Sinon onclick="ouvrirModalDoc()" ne trouve pas la fonction dans la portée globale          --}}
<script>
function ouvrirModalDoc() {
    if (document.activeElement) document.activeElement.blur();
    $('#modalAjoutDocument').modal('show');
}

function lirePasseportModal() {
    var btn   = document.getElementById('btnLirePasseportDoc');
    var label = document.getElementById('lblLirePasseportDoc');
    btn.disabled = true;
    label.textContent = 'Lecture en cours...';

    $.ajax({
        url: 'http://127.0.0.1:8085/read',
        method: 'GET',
        timeout: 15000,
        success: function(data) {
            // Type document
            var typeMap = { 'P': 'Passeport', 'T': 'Titre de voyage', 'L': 'Laissez-passer' };
            var typeDoc = typeMap[data.type_doc] || 'Passeport';
            $('select[name="type_document"]').val(typeDoc);

            // Numéro document
            if (data.num_doc) {
                $('#inputNumeroDoc').val(data.num_doc.toUpperCase()).trigger('input');
            }

            // Date délivrance (lecteur ne la fournit pas toujours — laisser vide si absent)
            if (data.date_delivrance) {
                $('input[name="date_delivrance"]').val(data.date_delivrance);
            }

            // Date expiration
            if (data.expiration) {
                // Format lecteur : YYMMDD → YYYY-MM-DD
                var exp = data.expiration.toString();
                if (exp.length === 6) {
                    var yy = parseInt(exp.substring(0,2));
                    var yyyy = (yy <= 30 ? 2000 : 1900) + yy;
                    var formatted = yyyy + '-' + exp.substring(2,4) + '-' + exp.substring(4,6);
                    $('input[name="date_expiration_doc"]').val(formatted);
                } else {
                    $('input[name="date_expiration_doc"]').val(exp);
                }
            }

            toastr.success('Passeport lu avec succès');
        },
        error: function(xhr, status) {
            if (status === 'timeout') {
                toastr.error('Délai dépassé — vérifiez que le lecteur est branché');
            } else {
                toastr.error('Lecteur inaccessible (port 8085)');
            }
        },
        complete: function() {
            btn.disabled = false;
            label.textContent = 'Lire le passeport';
        }
    });
}
</script>

@push('scripts')
<script>
// ── Reset du formulaire à l'ouverture du modal ────────────────────────
$('#modalAjoutDocument').on('show.bs.modal', function () {
    document.getElementById('formAjoutDoc').reset();
    document.getElementById('msgVerifDoc').innerHTML = '';
    document.getElementById('btnSauveDoc').disabled  = false;
});

// ── Vérification doublon en temps réel ────────────────────────────────
(function () {
    var timer;
    var checkUrl = "{{ route('impetrants.api.check-document') }}";

    document.getElementById('inputNumeroDoc').addEventListener('input', function () {
        clearTimeout(timer);
        var num = this.value.trim().toUpperCase();
        this.value = num;

        var msg = document.getElementById('msgVerifDoc');
        var btn = document.getElementById('btnSauveDoc');

        if (num.length < 4) {
            msg.innerHTML = '';
            btn.disabled  = false;
            return;
        }

        timer = setTimeout(function () {
            $.get(checkUrl, { numero: num })
             .done(function (resp) {
                if (resp.trouve) {
                    msg.innerHTML =
                        '<span class="text-danger">' +
                        '<i class="la la-exclamation-triangle"></i> ' +
                        'Document déjà enregistré pour : <strong>' +
                        resp.nom + ' ' + resp.prenom + '</strong>' +
                        (resp.url_fiche
                            ? ' — <a href="' + resp.url_fiche + '" target="_blank">Voir la fiche</a>'
                            : '') +
                        '</span>';
                    btn.disabled = true;
                } else {
                    msg.innerHTML =
                        '<span class="text-success">' +
                        '<i class="la la-check"></i> Numéro disponible' +
                        '</span>';
                    btn.disabled = false;
                }
             })
             .fail(function () {
                msg.innerHTML = '';
                btn.disabled  = false;
             });
        }, 600);
    });
}());

// ── Photos ────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    var first = document.querySelector('.photo-thumb.active');
    if (first) first.click();
});

function showPhoto(src, uuid, date, statut, statutClass, el) {
    document.getElementById('main-photo').src = src;
    document.getElementById('main-photo-uuid').textContent  = uuid  ? 'N° ' + uuid : '';
    document.getElementById('main-photo-date').textContent  = date;
    var statutEl = document.getElementById('main-photo-statut');
    statutEl.textContent = statut;
    statutEl.className   = 'badge badge-' + getBadgeClass(statutClass) + ' mt-1';
    document.querySelectorAll('.photo-thumb').forEach(function (t) { t.classList.remove('active'); });
    if (el) el.classList.add('active');
}

function getBadgeClass(sc) {
    return {
        'approuvee'  : 'success',
        'rejetee'    : 'danger',
        'livree'     : 'info',
        'contentieux': 'warning',
        'attente'    : 'secondary'
    }[sc] || 'secondary';
}

document.querySelectorAll('.photo-thumb').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
        var img   = this.querySelector('img');
        var label = this.querySelector('.photo-label');
        if (!img) return;
        document.getElementById('modal-photo-img').src          = img.src;
        document.getElementById('modal-photo-title').textContent = label ? label.textContent.trim() : '';
        $('#modalPhoto').modal('show');
    });
});
</script>
@endpush
@endsection