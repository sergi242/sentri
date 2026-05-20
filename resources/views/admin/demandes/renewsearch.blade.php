@extends('admin.layouts.app')
@section('title')
    Renouvellement demande
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('demandes.searchdocument')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-search"></i> Recherche via (Passeport, Carte de séjour, Visa)</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="search_type">Type de document *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select  id="search_type" class="form-control @error('search_type') is-invalid @enderror" name="search_type" required>
                                                            <option value="">Selectionner</option>
                                                            <option value="CRT">Carte de résident temporaire</option>
                                                            <option value="VISA">Visa</option>
                                                            <option value="PASSEPORT">Passeport</option>
                                                            <option value="NUM_FICHE">Numero Fiche</option>
                                                    </select>
                                                    @error('search_type')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_document">Numéro du document *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_document" class="form-control @error('numero_document') is-invalid @enderror" placeholder="Numéro document" value="{{old('numero_document')}}" name="numero_document">
                                                    @error('numero_document')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-search"></i> Recherche
                                            </button>
                                        </div>
                                    </form>
                                    @if ($results->count() > 0)
                                        <table class="table table-hovered table-stripped">
                                            <tr>
                                                <th>Photo</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Sexe</th>
                                                <th>Type demande</th>
                                                <th>Numéro document</th>
                                                <th>Action</th>
                                            </tr>

                                                @forelse ($results as $res)
                                                <tr>
                                                    <td class="text-center">
    @php
        $photo = $res->demande?->photo ?? $res->photo;
    @endphp

    @if($photo)
        <img src="{{ asset('app/' . $photo) }}"
             alt="Photo"
             style="width:60px; height:70px; object-fit:cover; border-radius:4px; border:1px solid #ccc;">
    @else
        <span class="text-muted">—</span>
    @endif
</td>

                                                    <td>{{ $res->demande?->impetrant?->nom ?? $res->impetrant?->nom }}</td>
                                                    <td>{{ $res->demande?->impetrant?->prenom ?? $res->impetrant?->prenom }}</td>
                                                    <td>{{ $res->demande?->impetrant?->sexe ?? $res->impetrant?->sexe }}</td>
                                                    <td>{{ $res->demande?->type_demande ?? $res->type_demande }}</td>
                                                    <td>{{ $res->demande?->numero_document ?? $res->numero_document }}</td>
                                                    <th><a href="{{ route("demandes.renouveler",$res->demande?->impetrants_id ?? $res->impetrants_id) }}" class="btn btn-primary">Renouveler</a></th>
                                                </tr>
                                                @empty

                                                @endforelse

                                        </table>
                                    @else

                                    @endif
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
@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('res/app-assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script>
    $(function(){

        $("#departements_id").on("change",function(){
            var id = $(this).val();
            if(id != ""){
                arrondissements(id,"#arrondissements_id");
            }
            return false;
        });

        $("#arrondissements_id").on("change",function(){
            var id = $(this).val();
            if(id != ""){
                quartiers(id,"#quartiers_id");
            }
            return false;
        });

    });

    function arrondissements(id,div){
        var route = "{{route("departements.arrondissements",'id')}}";
        var out = "<option value=''>Selectionner</option>"
        route = route.replace("id",id);
        $.get(route,function(data){
            if(data.length > 0){
                for(var i=0; i < data.length; i++){
                    out += "<option value="+data[i].id+">"+data[i].lib_arrondissement+"</option>";
                }
                $(div).empty().append(out);
            }
        });
    }

    function quartiers(id,div){
        var route = "{{route("arrondissements.quartiers",'id')}}";
        var out = "<option value=''>Selectionner</option>"
        route = route.replace("id",id);
        $.get(route,function(data){
            if(data.length > 0){
                for(var i=0; i < data.length; i++){
                    out += "<option value="+data[i].id+">"+data[i].lib_quartier+"</option>";
                }
                $(div).empty().append(out);
            }
        });
    }
</script>
@endsection
<style>
.table td, .table th {
    white-space: nowrap;
    vertical-align: middle;
}
</style>
