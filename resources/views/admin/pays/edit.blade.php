@extends('admin.layouts.app')
@section('title')
    Modifier ce rôle
@endsection
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
                                    <form class="form form-horizontal" method="POST" action="{{route('role.update',$role->id)}}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-edit"></i> Information du rôle</h4>

                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                    <input type="text" id="lib_role" class="form-control @error('lib_role') is-invalid @enderror" placeholder="Rôle" name="lib_role" value="{{$role->lib_role}}">
                                                    @error('lib_role')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @forelse ($fonctionnalites as $fonctionnalite)
                                            <h3 class="p-1"><strong>{{$fonctionnalite->lib_fonctionnalite}}</strong></h3>
                                            @forelse ($fonctionnalite->enfants as $permission)
                                                <div class="col-md-12 col-sm-12 {{$permission->lib_fonctionnalite=='' ? 'd-none':' '}}">
                                                    <fieldset>
                                                        <input id="{{$permission->id}}" type="checkbox" value="{{$permission->id}}" name="fonctionnalites[]" {{ $role->fonctionnalites->contains($permission->id) ? 'checked':'' }}>
                                                        <label for="{{$permission->id}}">{{$permission->lib_fonctionnalite}}</label>
                                                    </fieldset>
                                                </div>
                                            @empty

                                            @endforelse
                                        @empty

                                        @endforelse


                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('role.index')}}" class="btn btn-danger">
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
<script src="{{asset('admin-res/app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
@endsection
