@extends('adminviews.layouts.app')
@section('title')
    Détail rôle
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/plugins/forms/checkboxes-radios.css')}}">
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
                                        
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-edit"></i> Information du rôle</h4>

                                            <div class="form-group row">
                                                <div class="col-md-12 mx-auto">
                                                    <input type="text" id="projectinput4" class="form-control @error('role') is-invalid @enderror" placeholder="Rôle" name="role" value="{{$role->role}}">
                                                    @error('role')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @forelse (\App\Models\Permission::orderBy('module','asc')->get()->flatten()->pluck('module')->unique() as $per)
                                            <h3 class="p-1">{{$per}}</h3>
                                            @forelse (\App\Models\Permission::whereModule($per)->get() as $permission)
                                                <div class="col-md-6 col-sm-12 {{$permission->display_name=='' ? 'd-none':' '}}">
                                                    <fieldset>
                                                        <input type="checkbox" id="input-11" name="permissions[]" {{ $role->permissions_strings()->contains($permission->permission) ? 'checked':'' }} disabled>
                                                        <label for="input-11">{{$permission->display_name}}</label>
                                                    </fieldset>
                                                </div>
                                            @empty
                                                
                                            @endforelse
                                        @empty
                                            
                                        @endforelse
                                        

                                        <div class="form-actions">
                                            <a href="{{route('admin.role.index')}}" class="btn btn-danger">
                                                <i class="la la-back"></i> Retour
                                            </a>
                                        </div>
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