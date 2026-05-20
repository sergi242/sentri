@extends('admin.layouts.app')
@section('title')
    Modifier ce rôle
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/plugins/forms/checkboxes-radios.css')}}">
<style>
.module-section {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.module-title {
    color: #007bff;
    font-weight: bold;
    margin-bottom: 15px;
}

.permission-item {
    padding: 5px 10px;
    margin-bottom: 8px;
    background: white;
    border-radius: 4px;
    border: 1px solid #e3e6f0;
    transition: all 0.2s;
}

.permission-item:hover {
    background: #f0f7ff;
    border-color: #007bff;
}

.permission-item input[type="checkbox"]:checked + label {
    color: #28a745;
    font-weight: 600;
}

.select-all-btn {
    margin-bottom: 10px;
}

.stats-badge {
    background: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-left: 10px;
}
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="ft-edit"></i> Modification du rôle : <strong class="text-primary">{{ $role->lib_role }}</strong>
                                </h4>
                                <div class="heading-elements">
                                    <span class="stats-badge" id="permissionCount">
                                        {{ $role->fonctionnalites->count() }} permission(s) activée(s)
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('role.update',$role->id)}}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <!-- Nom du rôle -->
                                        <div class="form-body mb-4">
                                            <h4 class="form-section"><i class="ft-folder"></i> Information du rôle</h4>
                                            <div class="form-group row">
                                                <div class="col-md-6 mx-auto">
                                                    <input type="text" id="lib_role" class="form-control @error('lib_role') is-invalid @enderror" 
                                                           placeholder="Nom du rôle" name="lib_role" value="{{$role->lib_role}}" required>
                                                    @error('lib_role')
                                                        <div class="invalid-feedback">{{$message}}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Boutons de sélection globale -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-sm btn-success" onclick="selectAll()">
                                                    <i class="la la-check-square"></i> Tout sélectionner
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">
                                                    <i class="la la-square-o"></i> Tout désélectionner
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Liste des permissions par module -->
                                        @foreach($modules as $module)
                                            @if(isset($fonctionnalitesByModule[$module->id]) && $fonctionnalitesByModule[$module->id]->count() > 0)
                                            
                                            <div class="module-section" id="module-{{ $module->id }}">
                                                <!-- En-tête du module -->
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="module-title mb-0">
                                                        <i class="la la-folder-open"></i> {{ $module->lib_module }}
                                                        <span class="badge badge-primary">{{ $fonctionnalitesByModule[$module->id]->count() }}</span>
                                                    </h5>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                onclick="selectModule({{ $module->id }})">
                                                            <i class="la la-check"></i> Tout
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                                onclick="deselectModule({{ $module->id }})">
                                                            <i class="la la-times"></i> Aucun
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Permissions du module -->
                                                <div class="row">
                                                    @foreach($fonctionnalitesByModule[$module->id] as $fonctionnalite)
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="permission-item">
                                                                <fieldset>
                                                                    <input type="checkbox" 
                                                                           id="perm_{{ $fonctionnalite->id }}" 
                                                                           class="permission-checkbox module-{{ $module->id }}-checkbox"
                                                                           value="{{ $fonctionnalite->id }}" 
                                                                           name="fonctionnalites[]" 
                                                                           {{ $role->fonctionnalites->contains($fonctionnalite->id) ? 'checked' : '' }}>
                                                                    <label for="perm_{{ $fonctionnalite->id }}">
                                                                        {{ $fonctionnalite->lib_fonctionnalite }}
                                                                    </label>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            @endif
                                        @endforeach

                                        <!-- Actions -->
                                        <div class="form-actions mt-4">
                                            <button type="submit" class="btn btn-success">
                                                <i class="la la-check-square-o"></i> Sauvegarder les modifications
                                            </button>
                                            <a href="{{route('role.index')}}" class="btn btn-danger">
                                                <i class="la la-arrow-left"></i> Retour
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
<script>
// Mettre à jour le compteur de permissions
function updatePermissionCount() {
    const count = document.querySelectorAll('.permission-checkbox:checked').length;
    document.getElementById('permissionCount').textContent = count + ' permission(s) activée(s)';
}

// Sélectionner toutes les permissions
function selectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updatePermissionCount();
}

// Désélectionner toutes les permissions
function deselectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updatePermissionCount();
}

// Sélectionner toutes les permissions d'un module
function selectModule(moduleId) {
    document.querySelectorAll('.module-' + moduleId + '-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updatePermissionCount();
}

// Désélectionner toutes les permissions d'un module
function deselectModule(moduleId) {
    document.querySelectorAll('.module-' + moduleId + '-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updatePermissionCount();
}

// Mettre à jour le compteur lors du changement
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updatePermissionCount);
    });
});
</script>
@endsection