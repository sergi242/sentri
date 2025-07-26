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
                                    <img src="{{asset('img/icon/pdf.png')}}" alt="" style="width: 6%;">
                                        <h3>Reporting par categorie socio-professionelle</h3>
                                        <form action="{{ Route('reporting.profession') }}" class="form" method="GET">
                                            <div class="form-group">
                                                    <label for="numero_passeport">Nom du document</label>
                                                    <input type="text" name="nom_document" class="form-control" placeholder="Nom Du document">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Categorie</label>
                                                <select id="categorie_socioprofessionnelle_id" class="form-control @error('categorie_socioprofessionnelle_id') is-invalid @enderror"  value="{{old('categorie_socioprofessionnelle_id')}}" name="categorie_socioprofessionnelle_id"  required>
                                                        <option value="">Toutes</option>
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
                                            <input type="submit" class="btn btn-secondary" value="Exporter">
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <img src="{{asset('img/icon/pdf.png')}}" alt="" style="width: 6%;">
                                        <h3>Reporting par Nationnalite</h3>
                                        <form action="{{ route("reporting.nationnalite") }}" class="form" method="GET">
                                            <div class="form-group">
                                                    <label for="numero_passeport">Nom du document</label>
                                                    <input type="text" name="nom_document" class="form-control" placeholder="Nom Du document">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nationnalite</label>
                                                    <select class="select2-theme form-control" id="nationalites_id" name="nationalites_id">
                                                        <option value="Tous">Tous</option>
                                                        @forelse ($pays as $p)
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
                                            <input type="submit" class="btn btn-secondary" value="Exporter">
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <img src="{{asset('img/icon/pdf.png')}}" alt="" style="width: 6%;">
                                        <h3>Reporting par Employeurs</h3>
                                        <form action="{{ Route('reporting.employeur') }}" class="form" method="GET">
                                            <div class="form-group">
                                                <label for="numero_passeport">Nom du document</label>
                                                <input type="text" name="nom_document" class="form-control" placeholder="Nom Du document">
                                            </div>
                                            <div class="form-group">
                                                <select class="select2-theme form-control" id="employeur_id" name="employeur_id">
                                                    <option value="Tous">Tous</option>
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
                                            <input type="submit" class="btn btn-secondary" value="Exporter">
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
