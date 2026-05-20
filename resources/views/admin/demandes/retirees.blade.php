@extends('admin.layouts.app')

@section('title') Corbeille - Visuel des dossiers @endsection

@section('styles')
<style>
    .trash-container { background: #f0f2f5; padding: 30px; border-radius: 15px; min-height: 85vh; }
    .trash-title { font-weight: 800; color: #1a202c; letter-spacing: -1px; margin-bottom: 5px; }
    
    /* La Carte Dossier */
    .folder-card {
        background: white; border: none; border-radius: 20px;
        transition: all 0.3s ease; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        display: flex; flex-direction: column; height: 100%; position: relative;
    }
    .folder-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }

    /* Style du Lien Photo */
    .photo-link { display: block; position: relative; transition: all 0.3s ease; }
    .photo-link:hover { transform: scale(1.05); }
    .photo-link::after {
        content: "\f06e"; font-family: "FontAwesome"; position: absolute;
        top: 50%; left: 50%; transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.4); color: white; padding: 10px;
        border-radius: 50%; opacity: 0; transition: 0.3s;
    }
    .photo-link:hover::after { opacity: 1; }

    .photo-wrapper {
        width: 100px; height: 100px; margin: 0 auto 15px;
        position: relative; padding: 5px; background: #fff;
        border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .user-img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .user-init {
        width: 100%; height: 100%; border-radius: 50%; display: flex;
        align-items: center; justify-content: center; color: white;
        font-size: 2rem; font-weight: 800;
    }

    .card-user-header { padding: 20px; text-align: center; border-bottom: 1px dashed #edf2f7; }
    .card-body-content { padding: 15px 20px; flex-grow: 1; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
    .info-label { color: #a0aec0; font-weight: 600; }
    .info-value { color: #2d3748; font-weight: 700; }
    .type-badge { background: #ebf4ff; color: #3182ce; padding: 4px 12px; border-radius: 10px; font-size: 11px; font-weight: 800; }

    /* Boutons */
    .card-footer-actions { padding: 15px; display: flex; gap: 10px; background: #f8fafc; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; }
    .btn-pro { flex: 1; border-radius: 12px; padding: 10px; font-weight: 700; font-size: 12px; border: none; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .btn-restore-pro { background: #48bb78; color: white; }
    .btn-delete-pro { background: #f56565; color: white; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="trash-container">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h1 class="trash-title text-uppercase">Corbeille <span class="text-danger">Archives</span></h1>
                    <p class="text-muted"><i class="fa fa-mouse-pointer"></i> Cliquez sur la photo pour voir les détails du dossier.</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="p-2 bg-white rounded shadow-sm d-inline-block">
                        <span class="h4 font-weight-bold text-primary">{{ $demandes->total() }} Dossiers</span>
                    </div>
                </div>
            </div>

            @if($demandes->isEmpty())
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <h3 class="text-muted">La corbeille est vide</h3>
                </div>
            @else
                <div class="row">
                    @foreach($demandes as $demande)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="folder-card">
                            
                            <div class="card-user-header">
                                <a href="{{ route('demandes.show', $demande->id) }}" class="photo-link" title="Voir le détail">
                                    <div class="photo-wrapper">
                                     @php
                                            $photoName = trim($demande->photo ?? '');
                                            $color = '#' . substr(md5($demande->impetrant->nom ?? 'D'), 0, 6);
                                        @endphp

                                        @if($photoName)
                                            <img src="{{ asset('storage/'.$photoName) }}" class="user-img" alt="Photo">
                                        @else
                                            <div class="user-init" style="background: {{ $color }}">
                                                {{ strtoupper(substr($demande->impetrant->nom ?? 'D', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                
                                <h6 class="font-weight-bold text-dark mb-1">{{ $demande->impetrant->nom }}</h6>
                                <p class="text-muted small mb-2">{{ $demande->impetrant->prenom }}</p>
                                <span class="type-badge">{{ $demande->type_demande }}</span>
                            </div>

                            <div class="card-body-content">
                                <div class="info-row">
                                    <span class="info-label">Dossier ST:</span>
                                    <span class="info-value">#{{ $demande->soitTransmis ? $demande->soitTransmis->numero : 'N/A' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Supprimé le:</span>
                                    <span class="info-value text-danger">{{ \Carbon\Carbon::parse($demande->retire_le)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="card-footer-actions">
                                <form method="POST" action="{{ route('demandes.restaurer', $demande->id) }}" style="flex:1">
                                    @csrf
                                    <button type="submit" class="btn-pro btn-restore-pro w-100">Restaurer</button>
                                </form>
                                
                                <form method="POST" action="{{ route('demandes.forceDelete', $demande->id) }}" style="flex:1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-pro btn-delete-pro w-100" onclick="return confirm('Supprimer ?')">Détruire</button>
                                </form>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">{{ $demandes->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection