@extends('admin.layouts.app')
@section('title')
    Ajouter une donnée
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/selectivity-full.min.css')}}">
    <link rel="stylesheet" href="{{asset('res/app-assets/css/plugins/forms/selectivity/selectivity.css')}}">

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
                                    <form class="form form-horizontal" method="POST" action="{{route('flux.store')}}">
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information du flux</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="departements_id">Département *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="departements_id" class="form-control @error('departements_id') is-invalid @enderror"  name="departements_id" required>
                                                        <option value="">Selectionner</option>
                                                        @forelse ($departements as $departement)
                                                            <option value="{{$departement->id}}" {{ $departement->id == old("departements_id") ? "selected":"" }}>{{ $departement->lib_departement }}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('departements_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="frontieres_id">Frontière *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="frontieres_id" class="form-control @error('frontieres_id') is-invalid @enderror"  name="frontieres_id" required>
                                                        <option value="">Selectionner</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="total_entree">Total entrée *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="total_entree" class="form-control @error('total_entree') is-invalid @enderror" placeholder="Nombre total d'entrée" value="{{old('total_entree')}}" name="total_entree" required>
                                                    @error('total_entree')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="total_sortie">Total sortie *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="total_sortie" class="form-control @error('total_sortie') is-invalid @enderror" placeholder="Nombre total sortie" value="{{old('total_sortie')}}" name="total_sortie" required>
                                                    @error('total_sortie')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="pays_id">Nationalité *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="pays_id" class="form-control @error('pays_id') is-invalid @enderror"  name="pays_id" required>
                                                        <option value="">Selectionner</option>
                                                        @forelse ($pays as $pay)
                                                        <option value="{{$pay->id}}" {{$pay->id == old("pays_id") ? "selected":""}}>{{$pay->lib_pays}}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('pays_id')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="date_movement">Date des données *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="date" id="date_movement" class="form-control @error('date_movement') is-invalid @enderror" placeholder="Date des donnée" value="{{old('date_movement')}}" name="date_movement" required>
                                                    @error('date_movement')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('flux.index')}}" class="btn btn-warning">Retour</a>
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
<script src="{{asset('res/app-assets/vendors/js/forms/select/selectivity-full.min.js')}}"></script>
<script>
    $(function(){
        $("#departements_id").on("change",function(){
            var id = $(this).val();
            if(id != ""){
                getFrontieresByDepartement(id);
            }
            return false;
        });
    });

    function getFrontieresByDepartement(id){
        var route = "{{route('flux.getFrontieresByDepartement', ':id')}}";
        route = route.replace(":id",id);
        $.get(route,function(response){
            if(response.length > 0){
                var out = "<option value=''>Selectionner</option>";
                for(var i=0; i < response.length; i++){
                    out += "<option value="+response[i].id+">"+response[i].lib_frontiere+"</option>";
                }
                $("#frontieres_id").empty().append(out);
            }else{
                $("#frontieres_id").empty().append("<option value=''>Aucune frontière trouvée pour ce département</option>");
            }
        });
    }

</script>
@endsection
