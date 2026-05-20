@extends('admin.layouts.app')
@section('title') Certificat {{ $certificat->numero_certificat }} @endsection
@section('styles')
<style>
.info-block { background:#f8f9fa; border-radius:8px; padding:16px; margin-bottom:16px; }
.info-block .label { font-size:11px; text-transform:uppercase; color:#6c757d; font-weight:600; }
.info-block .value { font-size:15px; font-weight:600; color:#333; }
.cert-header { background:linear-gradient(135deg,#1E9FF2,#0d7bc4); color:#fff; border-radius:10px; padding:20px 24px; margin-bottom:20px; }
.badge-statut { font-size:13px; padding:6px 14px; border-radius:20px; }
.badge-en-attente { background:#FF9149; }
.badge-valide     { background:#28D094; }
.badge-rejete     { background:#FF4961; }
.badge-expire     { background:#6c757d; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h3 class="content-header-title">
                    <i class="la la-file-text"></i> Certificat d'hébergement
                </h3>
            </div>
            <div class="content-header-right col-md-3 col-12 text-right">
                <a href="{{ route('certificats-hebergement.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-8 offset-md-2">

                    {{-- En-tête certificat --}}
                    <div class="cert-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-size:11px; opacity:.8;">NUMÉRO DE CERTIFICAT</div>
                                <div style="font-size:1.8rem; font-weight:800; letter-spacing:1px;">
                                    {{ $certificat->numero_certificat }}
                                </div>
                                <div style="font-size:13px; opacity:.85;">
                                    Créé le {{ $certificat->created_at->format('d/m/Y à H:i') }}
                                    par {{ $certificat->createur?->prenom }} {{ $certificat->createur?->nom }}
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $sc = strtolower(str_replace([' ','é'],['','e'], $certificat->statut));
                                @endphp
                                <span class="badge badge-statut badge-{{ $sc }} text-white">
                                    {{ $certificat->statut }}
                                </span>
                                @if($certificat->statut === 'Validé')
                                    <div style="font-size:12px; opacity:.85; margin-top:6px;">
                                        Validé le {{ $certificat->valide_le?->format('d/m/Y') }}
                                        par {{ $certificat->validateur?->prenom }} {{ $certificat->validateur?->nom }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mb-4" style="gap:8px;">
                        @if($certificat->statut === 'En attente')
                            <form action="{{ route('certificats-hebergement.valider', $certificat->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Confirmer la validation de ce certificat ?')">
                                    <i class="la la-check"></i> Valider
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalRejet">
                                <i class="la la-times"></i> Rejeter
                            </button>
                        @endif
                        <a href="{{ route('certificats-hebergement.imprimer', $certificat->id) }}"
                           class="btn btn-dark" target="_blank">
                            <i class="la la-print"></i> Imprimer
                        </a>
                        @if($certificat->statut !== 'Validé')
                            <form action="{{ route('certificats-hebergement.destroy', $certificat->id) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Supprimer ce certificat ?')">
                                    <i class="la la-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- HÉBERGEUR --}}
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white py-2">
                            <strong><i class="la la-home"></i> Hébergeur</strong>
                            <span class="badge badge-light text-dark ml-2">{{ $certificat->hebergeur_type }}</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="label">Nom complet</div>
                                        <div class="value">{{ $certificat->nom_hebergeur }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="label">Code hébergeur</div>
                                        <div class="value">
                                            <span class="badge badge-primary" style="font-size:14px;">
                                                {{ $certificat->code_hebergeur }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($certificat->hebergeur_type === 'Congolais' && $certificat->hebergeurCongolais)
                                @php $heb = $certificat->hebergeurCongolais; @endphp
                                <div class="col-md-4">
                                    <div class="info-block">
                                        <div class="label">Téléphone</div>
                                        <div class="value">{{ $heb->telephone }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-block">
                                        <div class="label">Profession</div>
                                        <div class="value">{{ $heb->profession ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-block">
                                        <div class="label">Pièce d'identité</div>
                                        <div class="value">{{ $heb->type_piece ?? '—' }} {{ $heb->numero_piece ? '— '.$heb->numero_piece : '' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="info-block">
                                        <div class="label">Adresse</div>
                                        <div class="value">
                                            {{ $heb->avenue_rue }}, N°{{ $heb->numero_adresse }}
                                            — {{ $heb->quartier?->lib_quartier }}
                                            / {{ $heb->quartier?->arrondissement?->lib_arrondissement }}
                                            / {{ $heb->quartier?->arrondissement?->departement?->lib_departement }}
                                        </div>
                                    </div>
                                </div>

                                @elseif($certificat->hebergeur_type === 'Etranger' && $certificat->hebergeurEtranger)
                                @php $heb = $certificat->hebergeurEtranger; @endphp
                                <div class="col-md-4">
                                    <div class="info-block">
                                        <div class="label">Date de naissance</div>
                                        <div class="value">{{ $heb->date_naissance ? \Carbon\Carbon::parse($heb->date_naissance)->format('d/m/Y') : '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-block">
                                        <div class="label">Nationalité</div>
                                        <div class="value">{{ $heb->pays?->lib_pays ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-block">
                                        <div class="label">Dossier DMCE</div>
                                        <div class="value">
                                            <a href="{{ route('impetrants.demandes', $heb->id) }}">
                                                Voir les demandes
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @elseif($certificat->hebergeur_type === 'Societe' && $certificat->hebergeurSociete)
                                @php $heb = $certificat->hebergeurSociete; @endphp
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="label">Adresse société</div>
                                        <div class="value">{{ $heb->adresse_physique ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="label">Téléphone</div>
                                        <div class="value">{{ $heb->telephone ?? '—' }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- HÉBERGÉ --}}
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white py-2">
                            <strong><i class="la la-user"></i> Hébergé</strong>
                        </div>
                        <div class="card-body">
                            @if($certificat->heberge)
                            @php $hbe = $certificat->heberge; @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="label">Nom complet</div>
                                        <div class="value">{{ strtoupper($hbe->nom) }} {{ $hbe->prenom }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-block">
                                        <div class="label">Date de naissance</div>
                                        <div class="value">{{ $hbe->date_naissance ? \Carbon\Carbon::parse($hbe->date_naissance)->format('d/m/Y') : '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-block">
                                        <div class="label">Nationalité</div>
                                        <div class="value">{{ $hbe->pays?->lib_pays ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('impetrants.demandes', $hbe->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="la la-folder"></i> Voir les demandes de {{ $hbe->prenom }}
                                    </a>
                                </div>
                            </div>
                            @else
                            <p class="text-muted">Aucun hébergé enregistré</p>
                            @endif
                        </div>
                    </div>

                    {{-- SÉJOUR --}}
                    <div class="card mb-3">
                        <div class="card-header bg-warning text-white py-2">
                            <strong><i class="la la-calendar"></i> Informations du séjour</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-block">
                                        <div class="label">Date d'arrivée</div>
                                        <div class="value">{{ $certificat->date_arrivee_prevue?->format('d/m/Y') ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-block">
                                        <div class="label">Date de départ</div>
                                        <div class="value">{{ $certificat->date_depart_prevue?->format('d/m/Y') ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-block">
                                        <div class="label">Durée</div>
                                        <div class="value">{{ $certificat->duree_sejour_jours ?? '—' }} jours</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-block">
                                        <div class="label">Type de relation</div>
                                        <div class="value">{{ $certificat->type_relation ?? '—' }}</div>
                                    </div>
                                </div>
                                @if($certificat->precision_relation)
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="label">Précision relation</div>
                                        <div class="value">{{ $certificat->precision_relation }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($certificat->motif_sejour)
                                <div class="col-md-12">
                                    <div class="info-block">
                                        <div class="label">Motif du séjour</div>
                                        <div class="value">{{ $certificat->motif_sejour }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Demande liée --}}
                    @if($certificat->demande)
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white py-2">
                            <strong><i class="la la-link"></i> Demande de titre de séjour liée</strong>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('demandes.show', $certificat->demande_id) }}" class="btn btn-outline-secondary">
                                <i class="la la-external-link"></i>
                                Dossier N° {{ $certificat->demande->uuid }}
                                — {{ $certificat->demande->statut_demande }}
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Motif rejet --}}
                    @if($certificat->statut === 'Rejeté' && $certificat->motif_rejet)
                    <div class="alert alert-danger">
                        <strong><i class="la la-times-circle"></i> Motif de rejet :</strong>
                        {{ $certificat->motif_rejet }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal rejet --}}
<div class="modal fade" id="modalRejet" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="la la-times"></i> Rejeter le certificat</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('certificats-hebergement.rejeter', $certificat->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Motif du rejet *</label>
                        <textarea name="motif_rejet" class="form-control" rows="4"
                                  placeholder="Expliquez la raison du rejet (minimum 10 caractères)..."
                                  required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="la la-times"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection