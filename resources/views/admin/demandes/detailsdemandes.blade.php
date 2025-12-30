@extends("admin.layouts.app")
@section("title")
Situation des demandes de l'impétrant {{ $impetrant->nomcomplet() }}
@endsection
@section("content")
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Social cards section start -->
            <section id="simple-user-cards" class="row">
                <div class="col-12">
                    <h4 class="text-uppercase">Situation des demandes de l'impétrant {{ $impetrant->nomcomplet() }}</h4>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card">
                        <div class="text-center">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <strong>{{ $impetrant?->nom }} {{ $impetrant?->prenom }}</strong>
                                </h4>
                                <img src="{{ asset("app/".$latestDemandePhoto) }}" class="rounded-circle  height-150" alt="Card image">
                            </div>
                            <div class="card-body">
                                <h5 class="text-muted">
                                    {{ date("d/m/Y",strtotime($impetrant?->date_naissance)) }} - {{ $impetrant?->sexe == "Masculin" ? "Homme":"Femme" }}
                                </h5>
                                <h6 class="card-subtitle text-muted">{{ $impetrant?->pays?->lib_pays }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-md-6 col-12">
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Type demande</th>
                                <th>Référence</th>
                                <th>Durée</th>
                                <th>Etat demande</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($impetrant->demandes as $item)
                            <tr>
                                <td>{{ $item->date_demande }}</td>
                                <td>{{ $item->type_demande }}</td>
                                <td>{{ $item->uuid }}</td>
                                <td>{{ $item->validite }} an(s)</td>
                                <td>{{ $item->statut_demande }}</td>
                                <td>
                                    <a href="{{ route('demandes.show', $item->id) }}" class="btn btn-info btn-sm">Voir détails</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucune demande trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>                

            </section>
            <!-- // Social cards section end -->

        </div>
    </div>
</div>
<style>
    .table th, .table td {
        text-align: center; /* Centrer le texte dans les cellules */
    }

    .table td {
        vertical-align: middle; /* Aligner verticalement le texte */
    }

    .table td a {
        text-decoration: none; /* Enlever la décoration du lien */
    }

    .table td a:hover {
        text-decoration: underline; /* Souligner le lien au survol */
    }
    .table tbody tr {
        border-bottom: 2px solid #dee2e6; /* Bordure inférieure pour séparer les lignes */
    }

</style>
@endsection
