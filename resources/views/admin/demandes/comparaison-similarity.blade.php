@extends("admin.layouts.app")
@section("title")
    Analyse de similarité
@endsection
@section("content")
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Social cards section start -->
            <section id="simple-user-cards" class="row">
                <div class="col-12">
                    <h4 class="text-uppercase">Analyse de similarité</h4>
                    <p>Une analyse de similarité entre <span class="bg-success text-white p-1 rounded-pill">{{ $base->impetrant?->nomcomplet() }}</span> et <span class="bg-success text-white p-1 rounded-pill">{{ $similar->impetrant?->nomcomplet() }}</span></p>
                </div>
                
                <div class="col-xl-5 col-md-6 col-12">
                    <div class="card">
                        <div class="text-center">
                            <div class="card-body">
                                <img src="{{ asset("app/".$base->photo) }}" class="rounded-circle  height-150" alt="Card image">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">
                                    Impétrant de base
                                </h4>
                                
                            </div>
                            <div class="text-center">
                                
                            </div>
                        </div>
                        <div class="text-left">
                            <ul>
                                <li>Nom : {{ $base->impetrant?->nom }}</li>
                                <li>Prenom : {{ $base->impetrant?->prenom }}</li>
                                <li>Date de naissance : {{ $base->impetrant?->date_naissance }}</li>
                                <li>Lieu de naissance : {{ $base->impetrant?->lieu_naissance }}</li>
                                <li>Nationalité : {{ $base->impetrant?->pays?->lib_pays }}</li>
                                <li>Profession : {{ $base->profession }}</li>
                                <li>Adresse : {{ $base->adresseComplete() }}</li>
                                <li>Email : {{ $base->email }}</li>
                                <li>Telephone : {{ $base->telephone_1 }} / {{ $base->telephone_2 }}</li>
                                <li>Nom mère : {{ $base->impetrant?->nom_mere }}</li>
                                <li>Prenom mère : {{ $base->impetrant?->prenom_mere }}</li>
                                <li>Nom père : {{ $base->impetrant?->nom_pere }}</li>
                                <li>Prenom père : {{ $base->impetrant?->prenom_pere }}</li>
                            </ul>
                            
                        </div>
                        <div class="card-footer">
                            <a href="#" class="btn btn-secondary">Définir comme impétrant principal</a>
                        </div>
                    </div>
                    
                </div>
               
                <div class="col-xl-5 col-md-6 col-12">
                    <div class="card">
                        <div class="text-center">
                            <div class="card-body">
                                <img src="{{ asset("app/".$similar->photo) }}" class="rounded-circle  height-150" alt="Card image">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">
                                    Impétrant de comparaison
                                </h4>
                                
                            </div>
                            <div class="text-center">
                                
                            </div>
                        </div>
                        <div class="text-left">
                            <ul>
                                <li>Nom : {{ $similar->impetrant?->nom }}</li>
                                <li>Prenom : {{ $similar->impetrant?->prenom }}</li>
                                <li>Date de naissance : {{ $similar->impetrant?->date_naissance }}</li>
                                <li>Lieu de naissance : {{ $similar->impetrant?->lieu_naissance }}</li>
                                <li>Nationalité : {{ $similar->impetrant?->pays?->lib_pays }}</li>
                                <li>Profession : {{ $similar->profession }}</li>
                                <li>Adresse : {{ $similar->adresseComplete() }}</li>
                                <li>Email : {{ $similar->email }}</li>
                                <li>Telephone : {{ $similar->telephone_1 }} / {{ $similar->telephone_2 }}</li>
                                <li>Nom mère : {{ $similar->impetrant?->nom_mere }}</li>
                                <li>Prenom mère : {{ $similar->impetrant?->prenom_mere }}</li>
                                <li>Nom père : {{ $similar->impetrant?->nom_pere }}</li>
                                <li>Prenom père : {{ $similar->impetrant?->prenom_pere }}</li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="btn btn-secondary">Définir comme impétrant principal</a>
                        </div>
                    </div>
                </div>

            </section>
            <!-- // Social cards section end -->

        </div>
    </div>
</div>
@endsection
