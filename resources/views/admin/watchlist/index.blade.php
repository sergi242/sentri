@extends('admin.layouts.app')

@section('title') Terminal de Visualisation Watchlist @endsection

@section('styles')
<style>
    /* RESET & BASE */
    .main-content-wrapper {
        margin-left: 260px;
        background: #f8fafc;
        height: 100vh;
        overflow: hidden;
    }

    .terminal-container {
        display: flex;
        height: 100%;
    }

    /* COLONNE GAUCHE : LISTE */
    .target-list-panel {
        width: 400px;
        background: white;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        box-shadow: 10px 0 15px -10px rgba(0,0,0,0.05);
        z-index: 10;
    }

    .panel-header { padding: 25px; border-bottom: 1px solid #f1f5f9; }

    .add-trigger-btn {
        width: 100%;
        background: #1e293b;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
        transition: 0.3s;
        text-decoration: none;
    }
    .add-trigger-btn:hover { background: #0f172a; color: white; }

    .search-wrapper { position: relative; }
    .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-input {
        width: 100%;
        padding: 10px 10px 10px 40px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }

    .scroll-list { overflow-y: auto; flex: 1; }
    .target-item {
        padding: 15px 25px;
        border-bottom: 1px solid #f8fafc;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: 0.2s;
    }
    .target-item:hover { background: #f8fafc; }
    .target-item.active { background: #f0f7ff; border-right: 4px solid #3b82f6; }

    .mini-pic { width: 45px; height: 45px; border-radius: 10px; object-fit: cover; background: #e2e8f0; }

    /* COLONNE DROITE : VISUALISATION */
    .viewer-panel {
        flex: 1;
        background: #f1f5f9;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px;
        overflow-y: auto;
    }

    .empty-state { align-self: center; }
</style>
@endsection

@section('content')
<div class="main-content-wrapper">
    <div class="terminal-container">
        
        <div class="target-list-panel">
            <div class="panel-header">
                <a href="{{ route('watchlist.create') }}" class="add-trigger-btn">
                    <i class="fas fa-plus-circle"></i> INSCRIRE UN PROFIL
                </a>
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-input" placeholder="Rechercher nom ou ID...">
                </div>
            </div>

            <div class="scroll-list">
                @foreach($alerts as $alert)
                    @php
                        $nom = $alert->impetrant ? $alert->impetrant->nom : $alert->nom;
                        $photo = $alert->impetrant ? ($alert->impetrant->demandes()->latest()->first()->photo ?? null) : ($alert->photo_profil ?? null);
                    @endphp
                    <div class="target-item" data-id="{{ $alert->id }}">
                        @if($photo)
                            <img src="{{ $alert->impetrant ? asset('app/'.$photo) : asset('storage/'.$photo) }}" class="mini-pic">
                            
                        @else
                            <div class="mini-pic d-flex align-items-center justify-content-center bg-dark text-white fw-bold">
                                {{ substr($nom, 0, 1) }}
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark text-uppercase small text-truncate">{{ $nom }}</div>
                            <div class="text-muted extra-small" style="font-size: 0.65rem;">ID: {{ $alert->numero_document ?: 'N/A' }}</div>
                        </div>
                        <div class="ms-auto">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $alert->niveau_risque == 3 ? '#ef4444' : ($alert->niveau_risque == 2 ? '#f59e0b' : '#3b82f6') }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="viewer-panel" id="intel-viewer">
            <div class="text-center empty-state">
                <div class="mb-4"><i class="fas fa-search-location fa-5x text-muted opacity-25"></i></div>
                <h4 class="text-muted">Sélectionnez un profil</h4>
                <p class="text-muted small">Cliquez sur une cible à gauche pour visualiser son dossier</p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const profileItems = document.querySelectorAll('.target-item');
    const viewerPanel = document.getElementById('intel-viewer');

    // 1. RECHERCHE FILTRÉE SANS RECHARGEMENT
    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        profileItems.forEach(item => {
            const name = item.querySelector('.fw-bold').textContent.toLowerCase();
            const doc = item.querySelector('.extra-small').textContent.toLowerCase();
            item.style.display = (name.includes(term) || doc.includes(term)) ? 'flex' : 'none';
        });
    });

    // 2. CHARGEMENT AJAX DE LA FICHE
    profileItems.forEach(item => {
        item.addEventListener('click', function() {
            const profileId = this.dataset.id;

            // UI : Activer l'item
            profileItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // UI : Loader
            viewerPanel.innerHTML = `
                <div class="align-self-center text-center">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-3 fw-bold text-muted text-uppercase" style="letter-spacing:1px">Accès au registre sécurisé...</p>
                </div>`;

            // REQUÊTE
            // REQUÊTE (Mise à jour pour correspondre à la route corrigée)
fetch(`/admin/watchlist/${profileId}/details`)
    .then(response => {
        if (!response.ok) {
            console.error("Erreur HTTP: " + response.status); // Pour voir l'erreur exacte en console
            throw new Error('Erreur réseau');
        }
        return response.text();
    })
    .then(html => {
        viewerPanel.innerHTML = html;
    })
    .catch(error => {
        console.error(error);
        viewerPanel.innerHTML = `<div class="alert alert-danger align-self-center">Impossible de charger les données (Erreur: ${error.message}).</div>`;
    });
        });
    });
});
</script>
@endsection