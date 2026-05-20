@extends('admin.layouts.app')

@section('title') Archive des Similarités Écartées @endsection

@section('content')
<style>
    :root {
        --bg-light: #f4f7fa;
        --primary-color: #4834d4;
        --danger-soft: #fff5f5;
        --danger-text: #e53e3e;
    }

    .archive-container {
        padding: 2rem;
        background: var(--bg-light);
        min-height: 100vh;
    }

    .header-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        border-left: 5px solid var(--primary-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .rejection-item {
        background: white;
        border-radius: 12px;
        border: 1px solid #edf2f9;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }

    .rejection-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-color: var(--primary-color);
    }

    .avatar-wrapper {
        width: 55px;
        height: 55px;
        border-radius: 10px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .status-badge {
        font-size: 11px;
        padding: 4px 12px;
        border-radius: 20px;
        background: var(--danger-soft);
        color: var(--danger-text);
        font-weight: 700;
        display: inline-block;
    }

    .btn-restore {
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-restore:hover {
        background: #4338ca;
        color: white;
        box-shadow: 0 4px 10px rgba(67, 56, 202, 0.2);
    }

    .info-group {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 10px;
        text-transform: uppercase;
        color: #a0aec0;
        font-weight: 700;
        margin-bottom: 2px;
    }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="archive-container">
            
            <div class="header-card mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Historique des Similarités Écartées</h3>
                    <p class="text-muted mb-0">
                        Analyse pour : <span class="text-primary font-weight-bold">{{ strtoupper($demande->impetrant->nom) }} {{ $demande->impetrant->prenom }}</span>
                    </p>
                </div>
                <a href="{{ route('demandes.similarities', $demande->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="feather icon-arrow-left"></i> Retour à l'audit
                </a>
            </div>

            <div class="rejection-list">
                @forelse($rejections as $rejection)
                    @php
                        $autre = $rejection->demande_base_id == $demande->id
                            ? $rejection->similaireDemande
                            : $rejection->baseDemande;
                    @endphp

                    @if($autre && $autre->impetrant)
                    <div class="rejection-item p-3 shadow-sm">
                        <div class="row align-items-center">
                            
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="avatar-wrapper mr-3">
                                    <img src="{{ $autre->photo ? asset('app/'.$autre->photo) : asset('img/avatar-default.png') }}" class="avatar-img">
                                </div>
                                <div>
                                    <h5 class="mb-0 font-weight-bold">{{ strtoupper($autre->impetrant->nom) }} {{ $autre->impetrant->prenom }}</h5>
                                    <span class="text-muted small">ID Dossier: #{{ $autre->id }}</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="info-group">
                                    <span class="info-label">Naissance</span>
                                    <span class="info-value font-weight-bold">{{ \Carbon\Carbon::parse($autre->impetrant->date_naissance)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="info-group">
                                    <span class="info-label">Nationalité</span>
                                    <span class="info-value font-weight-bold">{{ $autre->impetrant->nationalite ?? '—' }}</span>
                                </div>
                            </div>

                            <div class="col-md-2 text-center">
                                <div class="status-badge mb-1">Écarté</div>
                                <div class="text-muted" style="font-size: 10px;">Le {{ $rejection->created_at->format('d/m/Y à H:i') }}</div>
                            </div>

                            <div class="col-md-2 text-right">
                                <form action="{{ route('demandes.similarities.restore', ['demande' => $demande->id, 'rejection' => $rejection->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-restore w-100" onclick="return confirm('Souhaitez-vous restaurer ce suspect dans la liste d\'analyse active ?')">
                                        <i class="feather icon-rotate-ccw"></i> Restaurer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                @empty
                    <div class="text-center py-5 bg-white rounded-lg border shadow-sm">
                        <i class="feather icon-folder text-muted font-large-3"></i>
                        <h4 class="mt-2 text-muted">Aucune archive</h4>
                        <p class="text-muted">Vous n'avez écarté aucun suspect pour ce dossier.</p>
                        <a href="{{ route('demandes.similarities', $demande->id) }}" class="btn btn-primary mt-1">Lancer l'audit</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

@endsection