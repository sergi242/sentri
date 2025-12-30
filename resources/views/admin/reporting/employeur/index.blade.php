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
                                    <img src="{{asset('img/batiment.png')}}" alt="" style="width: 6%;">
                                    <h3>Reporting par Employeur</h3>
                                    <form action="{{ Route('reporting.employeur.show') }}" class="form" method="GET">
                                        <div class="form-group">
                                            <label for="numero_passeport">Nom du document exporté</label>
                                            <input type="text" name="nomDocument" class="form-control" placeholder="Nom Du document">
                                        </div>
                                        <!-- Section Employeur - Durée de travail à domicile -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="duree_travail_domicile_de">Date de début de travail à domicile</label>
                                                    <input type="date" name="duree_travail_domicile_de" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="duree_travail_domicile_a">Date de fin de travail à domicile</label>
                                                    <input type="date" name="duree_travail_domicile_a" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Exemple pour Type Employeur -->
                                        <div class="form-group">
                                            <label for="type_employeur">Type employeur</label>
                                            <select id="type_employeur" class="form-control" name="type_employeur">
                                                <option value="all_type">Tous</option>
                                                <option value="Personne morale">Personne morale</option>
                                                <option value="Personne physique">Personne physique</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="type_employeur">Employeurs</label>
                                            <select class="select2-theme form-control" id="employeur_id" name="employeur_id">
                                                <option value="all">Tous</option>
                                                @forelse ($employeurs as $employeur)
                                                    <option value="{{$employeur->id}}" {{ $employeur->id==old("employeur_id") ? "selected":"" }}>{{ $employeur->nom_employeur }}</option>
                                                @empty

                                                @endforelse

                                            </select>
                                            @error('employeur_id')
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

        $(".nc").hide();

        $("#etat_civil").on("change",function(){
            var me = $(this).val();
            var sexe = $("#sexe").val();
            if(me =="Marié(e)" && sexe =="Féminin"){
                $(".nc").fadeIn(500);
            }else{
                $(".nc").fadeOut(500);
                $("#nom_conjoint").val("");
            }
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
