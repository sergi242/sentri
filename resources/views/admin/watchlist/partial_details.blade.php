@php
    $pPhoto = $active->impetrant ? ($active->impetrant->demandes()->latest()->first()->photo ?? null) : ($active->photo_profil ?? null);
    $pNom = $active->impetrant ? $active->impetrant->nom : $active->nom;
    $pPrenom = $active->impetrant ? $active->impetrant->prenom : $active->prenom;
@endphp

<style>
    /* Style pour l'écran */
    .intel-sheet {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Style spécifique pour l'impression */
    @media print {
        .action-bar, .btn, .main-menu, .header-navbar, .footer, .nav-tabs {
            display: none !important;
        }
        .intel-sheet {
            box-shadow: none !important;
            border: 1px solid #eee !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .sheet-top {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            background: #1e293b !important; /* Couleur solide pour l'impression */
            color: white !important;
        }
        body { background: white !important; }
    }
</style>

<div class="intel-sheet animate__animated animate__fadeIn">
    <div class="sheet-top" style="padding: 40px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; display: flex; gap: 30px; align-items: center;">
        <div class="profile-frame shadow-lg" style="width: 160px; height: 160px; border-radius: 20px; border: 4px solid rgba(255,255,255,0.1); overflow: hidden; background: #475569;">
            @if($pPhoto)
                <img src="{{ $active->impetrant ? asset('app/'.$pPhoto) : asset('storage/'.$pPhoto) }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white-50 h1 mb-0">
                    <i class="fas fa-user-secret"></i>
                </div>
            @endif
        </div>

        <div style="flex: 1;">
            <span class="risk-tag-large mb-3" style="padding: 6px 16px; border-radius: 8px; font-size: 0.75rem; font-weight: 900; display: inline-block; background: {{ $active->niveau_risque == 3 ? '#fee2e2' : ($active->niveau_risque == 2 ? '#fffbeb' : '#f0f9ff') }}; color: {{ $active->niveau_risque == 3 ? '#ef4444' : ($active->niveau_risque == 2 ? '#f59e0b' : '#3b82f6') }};">
                <i class="fas fa-shield-alt me-2"></i> 
                NIVEAU {{ $active->niveau_risque == 3 ? 'CRITIQUE' : ($active->niveau_risque == 2 ? 'ÉLEVÉ' : 'MODÉRÉ') }}
            </span>

            <h1 style="font-size: 2.5rem; font-weight: 800; color: #ffffff; text-transform: uppercase; margin: 0; line-height: 1; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                {{ $pNom }}
            </h1>
            <p style="font-size: 1.5rem; font-weight: 300; color: rgba(255,255,255,0.85); margin-top: 5px; margin-bottom: 0;">
                {{ $pPrenom }}
            </p>
        </div>
    </div>

    <div class="sheet-content" style="padding: 40px;">
        <div class="info-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px;">
            
            <div class="info-block" style="padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Âge / Détermination</label>
                <div class="val" style="font-size: 1rem; font-weight: 700; color: #1e293b;">
                    @if($active->date_naissance)
                        {{ \Carbon\Carbon::parse($active->date_naissance)->age }} ans 
                        <small style="font-weight: 400; color: #64748b;">(Né le {{ \Carbon\Carbon::parse($active->date_naissance)->format('d/m/Y') }})</small>
                    @elseif($active->age_min)
                        {{ $active->age_min }} - {{ $active->age_max }} ans <small style="font-weight: 400; color: #f59e0b;">(Estimation)</small>
                    @else
                        NON DÉTERMINÉ
                    @endif
                </div>
            </div>

            <div class="info-block" style="padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Sexe</label>
                <div class="val" style="font-size: 1rem; font-weight: 700; color: #1e293b;">
                    {{ $active->sexe == 'M' ? 'MASCULIN' : ($active->sexe == 'F' ? 'FÉMININ' : 'NON SPÉCIFIÉ') }}
                </div>
            </div>

            <div class="info-block" style="padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Téléphone / Contact</label>
                <div class="val" style="font-size: 1rem; font-weight: 700; color: #1e293b;">{{ $active->telephone ?: 'AUCUN NUMÉRO' }}</div>
            </div>

            <div class="info-block" style="padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Numéro de Document</label>
                <div class="val" style="font-size: 1rem; font-weight: 700; color: #1e293b;">{{ $active->numero_document ?: 'NON IDENTIFIÉ' }}</div>
            </div>

            <div class="info-block" style="padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Filiation (Père / Mère)</label>
                <div class="val" style="font-size: 1rem; font-weight: 700; color: #1e293b;">
                    P: {{ $active->nom_pere ?: '---' }} / M: {{ $active->nom_mere ?: '---' }}
                </div>
            </div>

            <div class="info-block" style="padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Date d'Inscription</label>
                <div class="val" style="font-size: 1rem; font-weight: 700; color: #1e293b;">{{ $active->created_at->format('d/m/Y à H:i') }}</div>
            </div>
        </div>

        <div class="motif-box" style="margin-top: 30px; background: #f8fafc; padding: 25px; border-radius: 15px; border-left: 4px solid #1e293b;">
            <label class="text-uppercase fw-bold mb-2 d-block" style="font-size: 0.65rem; color: #1e293b; letter-spacing: 1px;">Motif de la Surveillance / Observations</label>
            <p style="font-style: italic; color: #475569; margin: 0; line-height: 1.6;">"{{ $active->motif }}"</p>
        </div>
    </div>

    <div class="action-bar" style="padding: 25px 40px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 15px; background: #f8fafc;">
        <button type="button" class="btn btn-secondary fw-bold px-4" onclick="window.print()">
            <i class="fas fa-print me-2"></i>Imprimer la Fiche
        </button>
        <form action="{{ route('watchlist.destroy', $active->id) }}" method="POST" onsubmit="return confirm('Retirer définitivement ce profil ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger fw-bold px-4">
                <i class="fas fa-trash-alt me-2"></i>Retirer du Radar
            </button>
        </form>
    </div>
</div>