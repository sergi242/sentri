@extends('admin.layouts.app')
@section('title')
    Gestion des quartiers
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">

                <div class="col-xl-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Ajouter un quartier</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <form class="form form-horizontal" action="{{route('quartiers.store')}}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-folder"></i> Information du quartier</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="lib_quartier">Quartier</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="text" value="{{old('lib_quartier')}}" id="lib_quartier" class="form-control @error('lib_quartier') is-invalid @enderror" placeholder="Quartier" name="lib_quartier" required>
                                                @error('lib_quartier')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="lib_quartier">Arrondissement</label>
                                            <div class="col-md-9 mx-auto">
                                                <select class="select2-theme form-control" id="select2-theme" name="arrondissements_id">
                                                    <option value="">Selectionner</option>
                                                    @forelse ($arrondissements as $q)
                                                            <option value="{{$q->id}}" {{$q->id==old("arrondissements_id") ? "selected":""}}>{{ $q->lib_arrondissement }} ( {{ $q->departement?->lib_departement }} )</option>
                                                    @empty

                                                    @endforelse

                                                </select>
                                                @error('lib_quartier')
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
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Affichage des quartiers</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Quartier</th>
                                                <th>Arrondissement</th>
                                                <th>Département</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @forelse ($quartiers as $quartier)
                                                        <tr>
                                                            <td>{{$quartier->lib_quartier}}</td>
                                                            <td>{{$quartier->arrondissement?->lib_arrondissement}}</td>
                                                            <td>{{$quartier->arrondissement?->departement?->lib_departement}}</td>
                                                            <td>
                                                                <div class="btn-group btn-block">
                                                                    <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">


                                                                      <a class="dropdown-item" href="{{route('quartiers.edit',$quartier->id)}}">Modifier</a>

                                                                      <a class="dropdown-item a-del" href="{{route('quartiers.destroy',$quartier->id)}}">Supprimer</a>

                                                                    </div>
                                                                 </div>
                                                            </td>
                                                        </tr>
                                                @empty

                                                @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Quartier</th>
                                                <th>Arrondissement</th>
                                                <th>Département</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Revenue, Hit Rate & Deals -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('res/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('res/app-assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script>
    $(function(){
        $('.zero-configuration').DataTable();
    });
</script>
@endsection
