@extends('admin.layouts.app')

@section('title', 'Certificats pour ' . ($heberge->heberge_nom ?? 'cet hébergé'))

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <h3 class="content-header-title">Certificats reçus par l'hébergé</h3>
                @if($heberge)
                <p class="lead">
                    <strong>{{ $heberge->heberge_nom }} {{ $heberge->heberge_prenom }}</strong><br>
                    <small class="text-muted">Document N° {{ $numeroDocument }}</small>
                </p>
                @endif
            </div>
        </div>

        <div class="content-body">
            <section>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="la la-home"></i> Liste des hébergeurs 
                            <span class="badge badge-primary">{{ $certificats->total() }}</span>
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N° Certificat</th>
                                            <th>Hébergeur</th>
                                            <th>Adresse</th>
                                            <th>Période séjour</th>
                                            <th>Durée</th>
                                            <th>Relation</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($certificats as $cert)
                                        <tr>
                                            <td>
                                                <a href="{{ route('certificats-hebergement.show', $cert->id) }}">
                                                    {{ $cert->numero_certificat }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $cert->hebergeur_nom }} {{ $cert->hebergeur_prenom }}</strong><br>
                                                <small>{{ $cert->hebergeur_numero_document }}</small>
                                            </td>
                                            <td>
                                                {{ $cert->hebergeur_avenue_rue }}, N° {{ $cert->hebergeur_numero_adresse }}<br>
                                                <small class="text-muted">{{ $cert->quartierHebergeur->lib_quartier }}</small>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $cert->date_arrivee_prevue->format('d/m/Y') }}<br>
                                                    au {{ $cert->date_depart_prevue->format('d/m/Y') }}
                                                </small>
                                            </td>
                                            <td>{{ $cert->duree_sejour_jours }} jours</td>
                                            <td>
                                                {{ $cert->type_relation }}
                                                @if($cert->precision_relation)
                                                <br><small class="text-muted">{{ $cert->precision_relation }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($cert->statut == 'Validé')
                                                    <span class="badge badge-success">{{ $cert->statut }}</span>
                                                @elseif($cert->statut == 'En attente')
                                                    <span class="badge badge-warning">{{ $cert->statut }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $cert->statut }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('certificats-hebergement.show', $cert->id) }}" class="btn btn-sm btn-info">
                                                    <i class="la la-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun certificat trouvé</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $certificats->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
