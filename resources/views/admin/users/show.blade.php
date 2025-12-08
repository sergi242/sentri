@extends('admin.layouts.app')

@section('title')
    Détails de l'Utilisateur
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title" style="font-weight: bold; color: #3f73ac;">
                    <i class="la la-user"></i> Détails de l'Utilisateur
                </h3>
            </div>
            <div class="col-md-6 text-right">
                <form action="{{ route('users.activities.pdf', $user->id) }}" method="GET" target="_blank">
                    <button type="submit" class="btn" style="color: #3f73ac; border: 1px solid black">
                        Exporter la fiche <i class="la la-folder-open"></i>
                    </button>
                </form>
            </div>
        </div>        
        <div class="content-body">
            <div class="row">
                <!-- Carte Informations Générales -->
                <div class="col-xl-6 col-md-12">
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header d-flex align-items-center" style="background-color: #5888a6;">
                            <h4 class="card-title" style="color: white; font-weight: bold; margin: 0;">
                                <i class="la la-info-circle"></i> Informations Générales
                            </h4>
                        </div>
                        <div class="card-content" style="padding: 15px;">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nom Complet</th>
                                    <td>{{ $user->getNomPrenom() }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Rôle</th>
                                    <td>{{ $user->role?->lib_role }}</td>
                                </tr>
                                <tr>
                                    <th>Grade</th>
                                    <td>{{ $user->grade?->grade ?? 'Non défini' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Carte Statistiques -->
                <div class="col-xl-6 col-md-12">
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header" style="background-color: #3f73ac; padding: 10px 15px;">
                            <h4 class="card-title" style="color: white; font-weight: bold; margin: 0;">
                                <i class="la la-bar-chart"></i> Statistiques
                            </h4>
                        </div>
                        <div class="card-content" style="padding: 15px;">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Demandes Créées</th>
                                    <td><span class="badge" style="background-color: #5888a6; color: white; font-weight: bold;">{{ $user->demandes_creees_count ?? 0 }}</span></td>
                                </tr>
                                <tr>
                                    <th>Soit Transmis</th>
                                    <td><span class="badge" style="background-color: #3f73ac; color: white; font-weight: bold;">{{ $user->soit_transmis_count ?? 0 }}</span></td>
                                </tr>
                                <tr>
                                    <th>Flux Migratoire</th>
                                    <td><span class="badge" style="background-color: #3f73ac; color: white; font-weight: bold;">{{ $user->flux_migratoires_count ?? 0 }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Carte Activité Récente -->
                <div class="col-xl-6 col-md-12">
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header" style="background-color: #3f73ac; padding: 10px 15px;">
                            <h4 class="card-title" style="color: white; font-weight: bold; margin: 0;">
                                <i class="la la-clock-o"></i> Activité Récente
                            </h4>
                        </div>
                        <div class="card-content" style="padding: 15px;">
                            @if($user->demandes->isEmpty())
                            <p class="text-center" style="font-weight: bold; color: #3f73ac;">Aucune activité récente.</p>
                            @else
                            <ul class="list-group">
                                @foreach($user->demandes->take(5) as $demande)
                                <li class="list-group-item">
                                    <a href="{{ route('demandes.show', $demande->id) }}" style="color: #3f73ac; font-weight: bold;">
                                        {{ $demande->created_at->format('d/m/Y H:i') }} - {{ $demande->statut_demande }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Nouveau Bloc (par exemple : Tâches assignées) -->
                <div class="col-xl-6 col-md-12">
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header" style="background-color: #5888a6; padding: 10px 15px;">
                            <h4 class="card-title" style="color: white; font-weight: bold; margin: 0;">
                                <i class="la la-tasks"></i> Connexion
                            </h4>
                        </div>
                        <div class="card-content" style="padding: 15px;">
                            <p>Activités utilisateur</p>
                            <ul class="list-group">
                                <li class="list-group-item">IP</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="row mt-2">
                <div class="col-12 text-right">
                    <a href="{{ url()->previous() }}" class="btn" style="background-color: #5888a6; color: white; font-weight: bold;">
                        <i class="la la-arrow-left"></i> Retour à la Liste
                    </a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn" style="background-color: #3f73ac; color: white; font-weight: bold;">
                        <i class="la la-edit"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
