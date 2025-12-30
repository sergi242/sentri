@extends('admin.layouts.app')
@section('title')
    Modifier mon mot de passe
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
                                    <form class="form form-horizontal" method="POST" action="{{ route("users.changepassword") }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i>Changement du mot de passe</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="oldpass">Ancien mot de passe *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="password" id="oldpass" class="form-control @error('oldpass') is-invalid @enderror" placeholder="Ancien mot de passe" autocomplete="off"  name="oldpass">
                                                    @error('oldpass')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="password">Nouveau mot de passe *</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nouveau mot de passe" autocomplete="off"  name="password">
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                            {{$message}}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="password_confirmation">Confirmer nouveau mot de passe *</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirmer le nouveau mot de passe" autocomplete="off"  name="password_confirmation">
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">
                                                            {{$message}}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('users.dashboard')}}" class="btn btn-warning">Retour</a>
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
