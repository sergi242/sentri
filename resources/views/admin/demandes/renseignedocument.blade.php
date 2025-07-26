@extends('admin.layouts.app')
@section('title')
   Renseigner le document
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
                                    <form class="form form-horizontal" method="POST" action="{{route('demandes.storeremplirformation',$demande->id)}}">
                                        @csrf
                                        @method("PUT")
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Renseigner les informations {{ $demande->type_demande=="Visa" ? " du Visa":" de la " .$demande->type_demande}}  de l'impétrant <strong>{{ $demande->impetrant?->nomcomplet() }}</strong></h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="numero_document">Numéro du document *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="numero_document" class="form-control @error('numero_document') is-invalid @enderror" name="numero_document" required>

                                                        @error('numero_document')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_emission">Date d'emission *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_emission" class="form-control @error('date_emission') is-invalid @enderror" name="date_emission" required>

                                                        @error('date_emission')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_emission">Date d'emission *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_emission" class="form-control @error('date_emission') is-invalid @enderror" name="date_emission" required>

                                                        @error('date_emission')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div> --}}

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('demandes.index')}}" class="btn btn-warning">Retour</a>
                                        </div>
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
