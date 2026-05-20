@extends('admin.layouts.app')

@section('title', 'Fusion d’impétrants')

@section('styles')
<style>
    /* Animation pour attirer l'attention sur les différences */
@keyframes pulse-border {
    0% { border-color: #eaecf4; }
    50% { border-color: #f6c23e; box-shadow: 0 0 8px rgba(246, 194, 62, 0.2); }
    100% { border-color: #eaecf4; }
}

.merge-block.has-conflict {
    border-left: 5px solid #f6c23e !important; /* Jaune/Orange pour le conflit */
    background: #fffcf5;
}

.merge-block.has-conflict .merge-label {
    color: #856404;
}

.badge-conflict {
    font-size: 0.7rem;
    background: #fff3cd;
    color: #856404;
    padding: 2px 8px;
    border-radius: 5px;
    margin-left: 10px;
    border: 1px solid #ffeeba;
}

/* Style pour barrer visuellement la valeur non sélectionnée (optionnel) */
.merge-option:not(.active) span {
    opacity: 0.6;
}

.merge-option.active {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(28, 200, 138, 0.15);
}
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --bg-light: #f8f9fc;
    }

    .merge-container { max-width: 1000px; margin: 2rem auto; font-family: 'Inter', sans-serif; }
    
    /* En-tête des profils */
    .profiles-comparison {
        display: grid;
        grid-template-columns: 1fr 60px 1fr;
        gap: 20px;
        align-items: center;
        margin-bottom: 40px;
    }
    
    .profile-mini-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        text-align: center;
        border: 2px solid transparent;
        transition: 0.3s;
    }
    .profile-mini-card.source-a { border-bottom: 5px solid var(--primary); }
    .profile-mini-card.source-b { border-bottom: 5px solid #f6c23e; }

    .merge-photo {
        width: 100px; height: 100px;
        border-radius: 20px;
        object-fit: cover;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Lignes de fusion */
    .merge-section-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #858796;
        margin: 30px 0 15px;
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 5px;
    }

    .merge-block {
        background: white;
        margin-bottom: 12px;
        padding: 15px;
        border-radius: 15px;
        transition: 0.2s;
        border: 1px solid #e3e6f0;
    }
    .merge-block:hover { border-color: var(--primary); background: #fdfdff; }

    .merge-label {
        font-weight: 700;
        font-size: 0.9rem;
        color: #4e73df;
        margin-bottom: 10px;
        display: block;
    }

    .merge-row {
        display: grid;
        grid-template-columns: 1fr 1fr 50px;
        gap: 15px;
    }

    .merge-option {
        position: relative;
        padding: 12px 15px;
        background: #f8f9fc;
        border: 2px solid #eaecf4;
        border-radius: 12px;
        cursor: pointer;
        font-size: 0.95rem;
        color: #5a5c69;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .merge-option:hover { border-color: #d1d3e2; background: #fff; }

    .merge-option.active {
        border-color: var(--success);
        background: #f0fff4;
        color: #155724;
        font-weight: 600;
    }

    .merge-option.active::after {
        content: '✓';
        background: var(--success);
        color: white;
        width: 20px; height: 20px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px;
    }

    .btn-edit-custom {
        background: #f8f9fc;
        border: 2px solid #eaecf4;
        border-radius: 12px;
        color: #4e73df;
        transition: 0.3s;
    }
    .btn-edit-custom:hover { background: var(--primary); color: white; }

    /* Footer collant */
    .merge-sticky-footer {
        position: sticky;
        bottom: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
    }

    /* Badge identique */
    .badge-match {
        font-size: 0.7rem;
        background: #e9f8ef;
        color: #28a745;
        padding: 2px 8px;
        border-radius: 5px;
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="merge-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-weight-bold"><i class="la la-copy text-primary"></i> Fusion des données</h3>
        <span class="badge badge-primary px-3 py-2 round">Mode Comparaison</span>
    </div>

    <div class="profiles-comparison">
        <div class="profile-mini-card source-a">
            <img src="{{ asset('app/'.$base->photo) }}" class="merge-photo">
            <h5 class="mb-0 font-weight-bold">{{ $base->impetrant?->nom }}</h5>
            <small class="text-muted">Profil A (Principal)</small>
        </div>

        <div class="text-center">
            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; margin: auto;">
                <i class="la la-exchange-alt la-2x text-muted"></i>
            </div>
        </div>

        <div class="profile-mini-card source-b">
            <img src="{{ asset('app/'.$similar->photo) }}" class="merge-photo">
            <h5 class="mb-0 font-weight-bold">{{ $similar->impetrant?->nom }}</h5>
            <small class="text-muted">Profil B (Doublon)</small>
        </div>
    </div>

    <form method="POST" action="{{ route('demandes.merge') }}" id="mergeForm">
        @csrf
        <input type="hidden" name="base_id" value="{{ $base->id }}">
        <input type="hidden" name="similar_id" value="{{ $similar->id }}">

        @php
        $sections = [
            'État Civil' => [
                'nom' => 'Nom',
                'prenom' => 'Prénom',
                'sexe' => 'Sexe',
                'date_naissance' => 'Date de naissance',
                'lieu_naissance' => 'Lieu de naissance',
            ],
            'Filiation' => [
                'nom_pere' => 'Nom du père',
                'prenom_pere' => 'Prénom du père',
                'nom_mere' => 'Nom de la mère',
                'prenom_mere' => 'Prénom de la mère',
            ],
            'Contact' => [
                'profession' => 'Profession',
                'telephone' => 'Téléphone',
                'email' => 'Email',
            ]
        ];
        @endphp

        @foreach($sections as $sectionName => $fields)
            <div class="merge-section-title">{{ $sectionName }}</div>
            
            @foreach($fields as $key => $label)
    @php
        $source = in_array($key, ['profession', 'telephone', 'email']) ? 'demande' : 'impetrant';
        $valA = trim(($source === 'impetrant') ? $base->impetrant?->{$key} : $base->{$key});
        $valB = trim(($source === 'impetrant') ? $similar->impetrant?->{$key} : $similar->{$key});
        
        // On considère un conflit si les deux existent et sont différents
        $hasConflict = (!empty($valA) && !empty($valB) && strtolower($valA) !== strtolower($valB));
        $isMatch = (!empty($valA) && !empty($valB) && strtolower($valA) === strtolower($valB));
    @endphp

    <div class="merge-block {{ $hasConflict ? 'has-conflict' : '' }} {{ $isMatch ? 'border-success' : '' }}">
        <label class="merge-label">
            {{ $label }}
            @if($isMatch) 
                <span class="badge-match"><i class="la la-check"></i> Identique</span> 
            @elseif($hasConflict)
                <span class="badge-conflict"><i class="la la-exclamation-triangle"></i> Valeurs différentes</span>
            @endif
        </label>

        <div class="merge-row">
            {{-- Option A --}}
            <div class="merge-option @if(!empty($valA)) active @endif" 
                 data-field="{{ $key }}" data-value="{{ $valA }}" onclick="selectValue(this)">
                <span>{{ $valA ?: '—' }}</span>
                @if($hasConflict && !empty($valA)) <small class="text-primary font-weight-bold">A</small> @endif
            </div>

            {{-- Option B --}}
            <div class="merge-option @if(empty($valA) && !empty($valB)) active @endif" 
                 data-field="{{ $key }}" data-value="{{ $valB }}" onclick="selectValue(this)">
                <span>{{ $valB ?: '—' }}</span>
                @if($hasConflict && !empty($valB)) <small class="text-warning font-weight-bold">B</small> @endif
            </div>

            <button type="button" class="btn btn-edit-custom" onclick="editField('{{ $key }}','{{ $label }}')">
                <i class="la la-pen"></i>
            </button>
        </div>

        <input type="hidden" name="merge[{{ $key }}]" id="merge_{{ $key }}" value="{{ !empty($valA) ? $valA : $valB }}">
    </div>
@endforeach
        @endforeach

        <div class="merge-sticky-footer">
    <div id="conflict-summary" class="text-warning font-weight-bold small">
        {{-- Sera rempli par JS --}}
    </div>
    <div>
        <a href="{{ url()->previous() }}" class="btn btn-light round mr-2">Annuler</a>
        <button type="submit" class="btn btn-success btn-lg round shadow">
            Finaliser la Fusion <i class="la la-check-circle"></i>
        </button>
    </div>
</div>
    </form>
</div>

{{-- MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold" id="editLabel"></h5>
            </div>
            <div class="modal-body p-4">
                <label class="small text-muted">Corriger la valeur finale :</label>
                <input type="text" id="editValue" class="form-control form-control-lg round">
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-light round" data-dismiss="modal">Fermer</button>
                <button class="btn btn-primary round px-4" onclick="saveEdit()">Appliquer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentField = null;

function selectValue(el) {
    const field = el.dataset.field;
    const value = el.dataset.value;

    // UI Toggle
    document.querySelectorAll(`[data-field="${field}"]`).forEach(e => e.classList.remove('active'));
    el.classList.add('active');

    // Update Hidden Input
    document.getElementById('merge_'+field).value = value;
}

function editField(field, label) {
    currentField = field;
    document.getElementById('editLabel').innerText = "Édition : " + label;
    document.getElementById('editValue').value = document.getElementById('merge_'+field).value;
    $('#editModal').modal('show');
}

function saveEdit() {
    const val = document.getElementById('editValue').value.trim();
    if(currentField) {
        document.getElementById('merge_'+currentField).value = val;
        
        // On enlève le focus des deux options existantes pour montrer qu'on a une valeur personnalisée
        document.querySelectorAll(`[data-field="${currentField}"]`).forEach(e => e.classList.remove('active'));
        
        // Feedback visuel sur le bouton edit (optionnel)
        let block = document.getElementById('merge_'+currentField).closest('.merge-block');
        block.style.borderLeft = "4px solid #4e73df";
        
        $('#editModal').modal('hide');
    }
}
function updateSummary() {
    const conflicts = document.querySelectorAll('.has-conflict').length;
    const summary = document.getElementById('conflict-summary');
    if(conflicts > 0) {
        summary.innerHTML = `<i class="la la-exclamation-circle"></i> Attention : ${conflicts} champ(s) présentent des différences à vérifier.`;
    } else {
        summary.innerHTML = `<i class="la la-check-circle text-success"></i> Toutes les données sont cohérentes.`;
        summary.classList.replace('text-warning', 'text-success');
    }
}

// Appeler au chargement
$(document).ready(function() {
    updateSummary();
});
</script>
@endsection