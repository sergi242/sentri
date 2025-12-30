@extends('admin.layouts.app')
@section('title')
    Activité des Utilisateurs (Aujourd'hui)
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Repporting activité des Utilisateurs</strong></h3>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!-- Formulaire de sélection de période -->
                                    <form action="{{ route('users.report.pdf') }}" method="GET" target="_blank">
                                        <div class="row align-items-center ">
                                            <!-- Sélection de l'entête -->
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label for="entete">Entête du Document</label>
                                                    <select name="entete" id="entete" class="form-control">
                                                        <option value="1" selected>MINISTERE DE L’INTERIEUR, DE LA DECENTRALISATION ET DU DEVELOPPEMENT LOCAL</option>
                                                        <option value="3">DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row align-items-center">
                                            <!-- Titre du rapport -->
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label for="title">Titre du Rapport</label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Entrez un titre personnalisé">
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="row align-items-center mb-2">
                                            <!-- Date de début -->
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="start_date">Date de début</label>
                                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', now()->startOfYear()->toDateString()) }}">
                                                </div>
                                            </div>
                                    
                                            <!-- Date de fin -->
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="end_date">Date de fin</label>
                                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', now()->endOfYear()->toDateString()) }}">
                                                </div>
                                            </div>
                                    
                                            <!-- Sélection de la section -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="section">Section</label>
                                                    <select name="section" id="section" class="form-control">
                                                        <option value="" disabled selected>Choisir une section</option>
                                                        @foreach(config('sections.sections') as $division)
                                                            <optgroup label="{{ $division['division'] }}">
                                                                @foreach($division['sections'] as $section)
                                                                    <option value="{{ $section['name'] }}">{{ $section['section'] }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                    
                                            <!-- Sélection du signataire -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="signataire">Signataire</label>
                                                    <select name="signataire" id="signataire" class="form-control">
                                                        <option value="" disabled selected>Choisir un signataire</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->getNomPrenom() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                    
                                            <!-- Bouton d'exportation -->
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-secondary" style="margin-top: 30px;">
                                                        Exporter la fiche <i class="la la-folder-open"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <!-- Commentaire -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="commentaire">Commentaires</label>
                                                    <textarea name="commentaire" id="commentaire" class="form-control" rows="3" placeholder="Ajoutez un commentaire ici..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    
                                    <style>
                                        textarea {
                                            border-radius: 4px;
                                            font-size: 14px;
                                            resize: none;
                                        }
                                    </style>
                                    
                                    
                                    <!-- Tableau des activités des utilisateurs -->
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nom Complet</th>
                                                <th>Grade</th>
                                                <th>Demandes Réalisées</th>
                                                <th>Soit Transmis Réalisés</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->getNomPrenom() }}</td>
                                                <td>{{ $user->grade?->grade ?? 'Non défini' }}</td>
                                                <td>{{ $user->demandes_creees_today ?? 0 }}</td>
                                                <td>{{ $user->soit_transmis_today ?? 0 }}</td>
                                                <td>
                                                    <div class="btn-group btn-block">
                                                        <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                        <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent"></button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
                                                            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">Voir Détails</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nom Complet</th>
                                                <th>Grade</th>
                                                <th>Demandes Réalisées</th>
                                                <th>Soit Transmis Réalisés</th>
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.zero-configuration').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
            }
        });
    });
</script>
@endsection
