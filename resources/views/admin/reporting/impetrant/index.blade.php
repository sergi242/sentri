@extends('admin.layouts.app')
@section('title')
    Recherche avancée
@endsection
@section('styles')

    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('img/editorial.css')}}" type="text/css">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <img src="{{asset('img/employe.png')}}" alt="" style="width: 6%;">
                                    <h3>Reporting par Impetrant</h3>
                                    <form action="{{ Route('reporting.impetrant.show') }}" class="form" method="GET">
                                        <div class="form-group">
                                            <label for="numero_passeport">Nom du document exporté</label>
                                            <input type="text" name="nom_document" class="form-control" placeholder="Nom Du document">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="genre">Genre</label>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="genre_tous" name="genre" value="all_genre">
                                                        <label class="form-check-label" for="genre_tous">Tous</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="genre_masculin" name="genre" value="Masculin">
                                                        <label class="form-check-label" for="genre_masculin">Masculin</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="genre_feminin" name="genre" value="Feminin">
                                                        <label class="form-check-label" for="genre_feminin">Féminin</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="etat-civil">Etat Civil</label>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="etat-civil" name="etat_civil" value="all_etat_civil">
                                                        <label class="form-check-label" for="etat-civil">Tous</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="" name="etat_civil" value="Célibataire">
                                                        <label class="form-check-label" for="">Célibataire</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="" name="etat_civil" value="Marié(e)">
                                                        <label class="form-check-label" for="">Marié(e)</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="" name="etat_civil" value="Divorcé(e)">
                                                        <label class="form-check-label" for="">Divorcé(e)</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" id="" name="etat_civil" value="Veuf(-ve)">
                                                        <label class="form-check-label" for="">Veuf(-ve)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="demande_de">Demande compris entre</label>
                                                    <input type="date" name="demande_de" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="demande_a">A</label>
                                                    <input type="date" name="demande_a" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="age_de">Ages compris entre</label>
                                                    <input type="text" name="age_de" class="form-control" placeholder="Âge minimum">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="age_a">À</label>
                                                    <input type="text" name="age_a" class="form-control" placeholder="Âge maximum">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="type_demande">Type de demande</label>
                                            <select id="type_demande" class="form-control" name="type_demande">
                                                <option value="all_type_demande">Tous</option>
                                                <option value="visa">Visa</option>
                                                <option value="Carte de résident temporaire">Carte de résident temporaire</option>
                                                {{-- Ajoute ici les options pour les catégories socio-professionnelles --}}
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status_demande">Status demande</label>
                                            <select id="status_demande" class="form-control" name="status_demande">
                                                <option value="all_statut_demande">Tous</option>
                                                <option value="En attente d'approbation">En attente d'approbation</option>
                                                <option value="Approuvée">Approuvée</option>
                                                <option value="Rejetée">Rejetée</option>
                                                <option value="Envoyée au contentieux">Envoyée au contentieux</option>
                                                <option value="Renvoyée à la saisie pour modification">Renvoyée à la saisie pour modification</option>
                                                <option value="Livrée">Livrée</option>
                                                {{-- Ajoute ici les options pour les catégories socio-professionnelles --}}
                                            </select>
                                        </div>
                                        <!-- Exemple pour la catégorie socio-professionnelle -->
                                        <div class="form-group">
                                            <label for="categorie_socioprofessionnelle_id">Catégorie Socio-professionnelle</label>
                                            <select id="categorie_socioprofessionnelle_id" class="form-control @error('categorie_socioprofessionnelle_id') is-invalid @enderror"  value="{{old('categorie_socioprofessionnelle_id')}}" name="categories_id"  required>
                                                <option value="all_categories_id">Tous</option>
                                                @forelse ($categories as $item)
                                                    <option value="{{$item->id}}">{{ $item->categorie }}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                            @error('categorie_socioprofessionnelle_id')
                                                <div class="invalid-feedback">
                                                        {{$message}}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="categorie_socioprofessionnelle_id">Nationnalité</label>
                                            <select class="select2-theme form-control" id="nationalites_id" name="pays_id">
                                                <option value="all_pays_id">Tous</option>
                                                @forelse ($nationnalites as $p)
                                                    <option value="{{$p->id}}" {{$p->id==old("nationalites_id") ? "selected":""}}>{{ $p->lib_pays }}</option>
                                                @empty

                                                @endforelse

                                            </select>

                                            @error('nationalites_id')
                                                <div class="invalid-feedback">
                                                        {{$message}}
                                                </div>
                                            @enderror
                                        </div>

                                        <input type="submit" class="btn btn-secondary" value="Visualiser">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection
