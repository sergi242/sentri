@extends('admin.layouts.app')
@section('title')
    Modifier une frontière
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
                                    <form class="form form-horizontal" method="POST" action="{{route('frontieres.update',$frontiere->id)}}">
                                        @csrf
                                        @method("PUT")
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de la frontière</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="lib_frontiere">Nom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" id="lib_frontiere" class="form-control @error('lib_frontiere') is-invalid @enderror" placeholder="Nom de la frontière" value="{{$frontiere->lib_frontiere}}" name="lib_frontiere" required>
                                                    @error('lib_frontiere')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="terminal">Terminal *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="terminal" class="form-control @error('terminal') is-invalid @enderror"  name="terminal" required>
                                                        <option value="">Selectionner</option>
                                                        @forelse ($terminals as $item)
                                                            <option value="{{$item}}" {{ $item == $frontiere->terminal ? "selected":"" }}>{{ $item }}</option>
                                                        @empty

                                                        @endforelse
                                                    </select>
                                                    @error('terminal')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="departements_id">Département *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select id="departements_id" class="form-control @error('departements_id') is-invalid @enderror"  name="departements_id" required>
                                                        <option value="">Selectionner</option>
                                                        @forelse ($departements as $departement)
                                                        <option value="{{$departement->id}}" {{$departement->id == $frontiere->departements_id ? "selected":""}}>{{$departement->lib_departement}}</option>
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

                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('frontieres.index')}}" class="btn btn-warning">Retour</a>
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

    });
</script>
@endsection
