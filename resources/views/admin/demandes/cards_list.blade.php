@extends("admin.layouts.app")
@section("title")
{{ $status }}
@endsection
@section("content")
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Social cards section start -->
            <section id="simple-user-cards" class="row">
                <div class="col-12">
                    <h4 class="text-uppercase">{{ $status }}</h4>
                    <p>Une liste des personnes ayant fait la demande d'un Visa ou une Carte de Résident Temporaire. <br><a href="?layout=list">Afficher sous forme de liste</a></p>
                    {{ $demandes->links('admin.pagination.pagination') }}
                </div>
                @forelse ($demandes as $demande)
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card">
                        <div class="text-center">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <strong>{{ $demande->impetrant?->nom }} {{ $demande->impetrant?->prenom }}</strong>
                                </h4>
                                <img src="{{ asset("app/".$demande->photo) }}" class="rounded-circle  height-150" alt="Card image">
                            </div>
                            <div class="card-body">
                                <h5 class="text-muted">
                                    {{ date("d/m/Y",strtotime($demande->impetrant?->date_naissance)) }} - {{ $demande->impetrant?->sexe == "Masculin" ? "Homme":"Femme" }}
                                </h5>
                                <h6 class="card-subtitle text-muted">{{ $demande->impetrant?->pays?->lib_pays }} <br> ( {{ $demande->type_demande }} )</h6>
                            </div>
                            <div class="text-center">
                                {{-- <a href="#" class="btn btn-social-icon mr-1 mb-1 btn-outline-facebook"><span class="la la-facebook"></span></a> --}}
                                <h6 class="text-muted"><strong>Depuis {{ TechnoDev::timespan($demande->created_at) }}</strong></h6>
                                <a href="{{ route("demandes.show",$demande->id) }}" class="btn btn-secondary btn-sm mb-1">Voir détail</a>
                                {{-- <a href="#" class="btn btn-social-icon mb-1 btn-outline-linkedin"><span class="la la-linkedin font-medium-4"></span></a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @empty

                @endforelse

            </section>
            <!-- // Social cards section end -->
            {{ $demandes->links('admin.pagination.pagination') }}
        </div>
    </div>
</div>
@endsection
