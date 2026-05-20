{{--
    ══════════════════════════════════════════════════════════════
    PARTIAL : Bloc quittance avec toggle Sans/Avec
    Variables disponibles :
      $valeurQuittance : valeur pré-remplie (optionnel)
      $colLabel        : largeur colonne label (défaut: col-md-4)
      $colField        : largeur colonne champ (défaut: col-md-8)
    ══════════════════════════════════════════════════════════════
--}}
@php
    $colLabel        = $colLabel        ?? 'col-md-4';
    $colField        = $colField        ?? 'col-md-8';
    $valeurQuittance = $valeurQuittance ?? old('numero_quittance', '');
    $modeSansQuit    = old('sans_quittance', '0') === '1'
                       || $valeurQuittance === 'GRATIS';
@endphp

{{-- Toggle Avec / Sans --}}
<div class="form-group row mb-2">
    <div class="{{ $colField }} offset-{{ $colLabel }}">
        <div class="d-flex align-items-center" style="gap:20px;">
            <label class="mb-0 d-flex align-items-center" style="cursor:pointer; font-weight:600; gap:6px;">
                <input type="radio" name="mode_quittance" id="avec_quittance" value="avec"
                       {{ !$modeSansQuit ? 'checked' : '' }}
                       onchange="toggleQuittance('avec')">
                <span class="text-success"><i class="la la-receipt"></i> Avec quittance</span>
            </label>
            <label class="mb-0 d-flex align-items-center" style="cursor:pointer; font-weight:600; gap:6px;">
                <input type="radio" name="mode_quittance" id="sans_quittance" value="sans"
                       {{ $modeSansQuit ? 'checked' : '' }}
                       onchange="toggleQuittance('sans')">
                <span class="text-warning"><i class="la la-ban"></i> Sans quittance (GRATIS)</span>
            </label>
        </div>
    </div>
</div>
<input type="hidden" name="sans_quittance" id="hidden_sans_quittance" value="{{ $modeSansQuit ? '1' : '0' }}">

<div class="form-group row" id="bloc_quittance"
     style="{{ $modeSansQuit ? 'opacity:0.5; pointer-events:none;' : '' }}">
    <label class="{{ $colLabel }} col-form-label" for="numero_quittance">Numéro de quittance *</label>
    <div class="{{ $colField }} mx-auto">
        <input type="text"
               id="numero_quittance"
               name="numero_quittance"
               class="form-control"
               value="{{ $modeSansQuit ? 'GRATIS' : ($valeurQuittance !== 'GRATIS' ? $valeurQuittance : '') }}"
               {{ !$modeSansQuit ? 'required' : '' }}>
        <small id="quittanceValidMsg" class="text-success d-none">✔ Quittance valide</small>
        <small id="quittanceLockedMsg" class="text-warning d-none">⚠ Quittance confirmée – champ verrouillé</small>
    </div>
</div>

{{-- Badge GRATIS visible quand sans quittance --}}
<div id="bloc_gratis" style="{{ !$modeSansQuit ? 'display:none;' : '' }}" class="form-group row">
    <div class="{{ $colField }} offset-{{ $colLabel }}">
        <span class="badge badge-warning" style="font-size:14px; padding:6px 16px;">
            <i class="la la-gift"></i> GRATIS — Pas de quittance
        </span>
    </div>
</div>

{{-- JS toggle --}}
<script>
function toggleQuittance(mode) {
    var bloc    = document.getElementById('bloc_quittance');
    var gratis  = document.getElementById('bloc_gratis');
    var hidden  = document.getElementById('hidden_sans_quittance');
    var input   = document.getElementById('numero_quittance');
    var msgV    = document.getElementById('quittanceValidMsg');
    var msgL    = document.getElementById('quittanceLockedMsg');

    if (mode === 'sans') {
        bloc.style.opacity       = '0.5';
        bloc.style.pointerEvents = 'none';
        gratis.style.display     = '';
        hidden.value             = '1';
        if (input) { input.removeAttribute('required'); input.value = 'GRATIS'; }
        if (msgV) msgV.classList.add('d-none');
        if (msgL) msgL.classList.add('d-none');
    } else {
        bloc.style.opacity       = '1';
        bloc.style.pointerEvents = 'auto';
        gratis.style.display     = 'none';
        hidden.value             = '0';
        if (input && input.value === 'GRATIS') input.value = '';
    }
}
</script>