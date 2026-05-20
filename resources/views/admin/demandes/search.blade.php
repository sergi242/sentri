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
                    <div class="col-md-6">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <img src="{{asset('img/passeport.png')}}" alt="">
                                        <h3>Recherche par type de document</h3>
                                        <form action="{{ route("recherche.type.docs") }}" class="form" method="GET">
                                            <div class="form-group">
                                                <label for="">Type de document</label>
                                                <select name="type" id="tye" class="form-control">
                                                    <option value="">Selctionner</option>
                                                    <option value="Passeport">Passeport</option>
                                                    <option value="Carte consulaire">Carte Consulaire</option>
                                                    <option value="crt">Carte de résident temporaire</option>
                                                    <option value="Visa">Visa</option>
                                                    <option value="Demande">Numero de la demande</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Numéro du document</label>
                                                <input type="text" name="numero_doc" class="form-control" placeholder="Numéro de la demande">
                                            </div>
                                            <input type="submit" class="btn btn-secondary" value="Recherche">
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <img src="{{asset('img/ididentite.png')}}" alt="">
                                    <h3>Recherche par indentité de l'impétrant</h3>
                                    <form action="{{ route("recherche.impetrant") }}" class="form" method="GET">
                                       <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="numero_passeport">Nom</label>
                                            <input type="text" name="nom" class="form-control" placeholder="Nom impetrant">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="numero_passeport">Prenom</label>
                                            <input type="text" name="prenom" class="form-control" placeholder="Prenom impetrant">
                                        </div>
                                       </div>
                                       <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="numero_passeport">Ages compris entre</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="debut_age" class="form-control" placeholder="Debut">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="fin_age" class="form-control" placeholder="Fin">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="numero_passeport">Nationnalité</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <select class="select2-theme form-control" id="nationalites_id" name="nationalites_id">
                                                        <option value="" {{ empty(old("nationalites_id")) ? "selected" : "" }}>Toutes</option>
                                                        @forelse ($pays as $p)
                                                            <option value="{{ $p->id }}" {{ $p->id == old("nationalites_id") ? "selected" : "" }}>
                                                                {{ $p->lib_pays }}
                                                            </option>
                                                        @empty
                                                        @endforelse
                                                    </select>

                                                    @error('nationalites_id')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                       <input type="submit" class="btn btn-secondary" value="Recherche">
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
