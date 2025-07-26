@extends('admin.layouts.app')
@section('title')
    Modifier le motif
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/plugins/forms/checkboxes-radios.css')}}">
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
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('motifs.contentieux.update',$motif->id)}}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-edit"></i> Information du motif</h4>

                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                    <label for="lib_motif">Nom de l'employeur</label>
                                                    <input type="text" id="lib_motif" class="form-control @error('lib_motif') is-invalid @enderror" placeholder="Motif" name="lib_motif" value="{{$motif->lib_motif}}">
                                                    @error('lib_motif')
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
                                            <a href="{{route('motifs.contentieux.index')}}" class="btn btn-danger">
                                                <i class="la la-back"></i> Retour
                                            </a>
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
<script src="{{asset('res/app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
@endsection
