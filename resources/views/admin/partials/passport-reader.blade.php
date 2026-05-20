{{--
    ============================================================
    DMCE — Partiel : Lecteur de passeport NFC/MRZ
    Usage :
        @include('admin.partials.passport-reader', [
            'target'  => 'heberge',    // préfixe des champs à remplir
            'title'   => 'hébergé',    // label affiché dans le bouton
        ])
    ============================================================
--}}

@php
    $target = $target ?? 'personne';
    $title  = $title  ?? 'la personne';
    $btnId  = 'btn-read-passport-' . $target;
    $resetId = 'btn-reset-' . $target;
@endphp

<div class="passport-reader-bar d-flex align-items-center gap-2 mb-3"
     style="background:#f4f7fb; border:1px solid #d0dff0; border-radius:8px; padding:10px 16px;">

    {{-- Icône puce NFC --}}
    <span class="passport-reader-icon" style="font-size:1.5rem; color:#1E9FF2;">
        <i class="la la-id-card"></i>
    </span>

    <div class="flex-grow-1">
        <strong style="font-size:.82rem; color:#34495e; letter-spacing:.03em;">
            LECTURE PASSEPORT — {{ strtoupper($title) }}
        </strong>
        <div style="font-size:.75rem; color:#7f8c8d;">
            Insérez le passeport dans le lecteur puis cliquez sur <em>Lire</em>.
        </div>
    </div>

    {{-- Bouton Lire --}}
    <button type="button"
            id="{{ $btnId }}"
            class="btn btn-primary btn-sm passport-read-btn"
            data-target="{{ $target }}"
            style="min-width:130px;">
        <i class="la la-wifi mr-1"></i> Lire le passeport
    </button>

    {{-- Bouton Réinitialiser --}}
    <button type="button"
            id="{{ $resetId }}"
            class="btn btn-outline-secondary btn-sm passport-reset-btn"
            data-target="{{ $target }}"
            style="min-width:110px;">
        <i class="la la-undo mr-1"></i> Réinitialiser
    </button>

    {{-- Indicateur de statut --}}
    <span id="passport-status-{{ $target }}"
          class="badge badge-light passport-status-badge ml-1"
          style="font-size:.72rem; display:none;">
    </span>
</div>
