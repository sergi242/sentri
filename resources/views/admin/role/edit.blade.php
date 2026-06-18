@extends('admin.layouts.app')
@section('title')
    Modifier le rôle : {{ $role->lib_role }}
@endsection

@section('styles')
<style>
/* ─── Layout général ──────────────────────────────────────────── */
.role-header-bar {
    background: linear-gradient(135deg, #1E9FF2 0%, #0d6ebd 100%);
    border-radius: 8px;
    padding: 18px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.role-header-bar .role-subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 0.82rem;
    margin: 0;
}
.perm-badge-total {
    background: rgba(255,255,255,0.25);
    color: #fff;
    border: 1.5px solid rgba(255,255,255,0.5);
    border-radius: 20px;
    padding: 6px 16px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
}
.global-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #f4f8ff;
    border: 1px solid #dbe8f8;
    border-radius: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.global-actions span.label {
    font-size: 0.82rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-right: 4px;
}
.module-card {
    border: 1px solid #e2eaf4;
    border-radius: 8px;
    margin-bottom: 14px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.module-card:hover { box-shadow: 0 2px 12px rgba(30,159,242,0.10); }
.module-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 16px;
    cursor: pointer;
    user-select: none;
    background: #f8fafd;
    border-bottom: 1px solid #e2eaf4;
    transition: background 0.15s;
}
.module-card-header:hover { background: #edf4fe; }
.module-card-header .mod-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.module-icon {
    width: 32px; height: 32px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.module-name { font-weight: 700; font-size: 0.9rem; color: #2d3a4a; }
.module-perm-badge {
    font-size: 0.75rem; border-radius: 12px;
    padding: 2px 9px; font-weight: 600;
}
.mod-right { display: flex; align-items: center; gap: 6px; }
.mod-toggle-icon { color: #aab4c0; font-size: 1rem; transition: transform 0.2s; }
.module-card-body { padding: 14px 16px; background: #fff; }

/* ─── Module verrouillé ───────────────────────────────────────── */
.module-card.module-locked {
    border-color: #e8c88a;
    opacity: 0.88;
}
.module-card.module-locked .module-card-header {
    background: #fffbf0;
    border-bottom-color: #f0d89a;
    cursor: default;
}
.module-card.module-locked .module-card-header:hover { background: #fffbf0; }
.module-locked-notice {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #fffbf0;
    border-top: 1px dashed #f0d89a;
    color: #856404;
    font-size: 0.81rem;
    margin-top: 8px;
    border-radius: 0 0 6px 6px;
}
.lock-badge {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #f0d89a;
    border-radius: 12px;
    padding: 2px 10px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.05em;
}

/* ─── Items permission ────────────────────────────────────────── */
.perm-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
@media (max-width: 991px) { .perm-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px)  { .perm-grid { grid-template-columns: 1fr; } }
.perm-item {
    display: flex; align-items: center; gap: 9px;
    padding: 8px 12px;
    border: 1px solid #e8edf4; border-radius: 6px;
    background: #fafbfc; cursor: pointer;
    transition: all 0.15s;
}
.perm-item:hover { background: #f0f7ff; border-color: #1E9FF2; }
.perm-item.is-checked { background: #f0fff4; border-color: #28D094; }
.perm-item.is-locked {
    background: #fafaf7;
    border-color: #e0d8c0;
    cursor: not-allowed;
    opacity: 0.72;
}
.perm-item input[type="checkbox"] {
    width: 16px; height: 16px;
    flex-shrink: 0; accent-color: #28D094; cursor: pointer;
}
.perm-item.is-locked input[type="checkbox"] { cursor: not-allowed; }
.perm-item label { font-size: 0.82rem; color: #444; cursor: pointer; margin: 0; line-height: 1.3; }
.perm-item.is-checked label { color: #1a7a50; font-weight: 600; }
.perm-key { font-size: 0.70rem; color: #aaa; font-family: monospace; display: block; margin-top: 1px; }

/* ─── Couleurs modules ────────────────────────────────────────── */
.mod-color-1  { background:#e8f4ff; color:#1E9FF2; }
.mod-color-2  { background:#fff3e0; color:#FF9149; }
.mod-color-3  { background:#e8f8f1; color:#28D094; }
.mod-color-4  { background:#fce8ff; color:#9c27b0; }
.mod-color-5  { background:#fff8e1; color:#f0ad00; }
.mod-color-6  { background:#e8eeff; color:#5c6bc0; }
.mod-color-7  { background:#e0f7fa; color:#00838f; }
.mod-color-8  { background:#fde8e8; color:#FF4961; }
.mod-color-9  { background:#f3e8ff; color:#8e24aa; }
.mod-color-10 { background:#e8f5e9; color:#2e7d32; }
.mod-color-11 { background:#fff3e0; color:#e65100; }
.mod-color-12 { background:#e8eaf6; color:#3949ab; }
.mod-color-13 { background:#e0f2f1; color:#00695c; }
.mod-color-14 { background:#fce4ec; color:#c2185b; }
.badge-mod-1  { background:#e8f4ff; color:#1E9FF2; border:1px solid #bee3ff; }
.badge-mod-2  { background:#fff3e0; color:#FF9149; border:1px solid #ffd9b5; }
.badge-mod-3  { background:#e8f8f1; color:#28D094; border:1px solid #b3ecd4; }
.badge-mod-4  { background:#fce8ff; color:#9c27b0; border:1px solid #e7b5f5; }
.badge-mod-5  { background:#fff8e1; color:#f0ad00; border:1px solid #ffe8a0; }
.badge-mod-6  { background:#e8eeff; color:#5c6bc0; border:1px solid #c5cdf8; }
.badge-mod-7  { background:#e0f7fa; color:#00838f; border:1px solid #a0e5eb; }
.badge-mod-8  { background:#fde8e8; color:#FF4961; border:1px solid #f5b5bb; }
.badge-mod-9  { background:#f3e8ff; color:#8e24aa; border:1px solid #d9b5f5; }
.badge-mod-10 { background:#e8f5e9; color:#2e7d32; border:1px solid #b3d9b6; }
.badge-mod-11 { background:#fff3e0; color:#e65100; border:1px solid #ffd0a0; }
.badge-mod-12 { background:#e8eaf6; color:#3949ab; border:1px solid #c5caf5; }
.badge-mod-13 { background:#e0f2f1; color:#00695c; border:1px solid #a0d9d4; }
.badge-mod-14 { background:#fce4ec; color:#c2185b; border:1px solid #f5b5c8; }

/* ─── Barre de sauvegarde fixe ────────────────────────────────── */
.save-bar {
    position: sticky; bottom: 0;
    background: #fff;
    border-top: 2px solid #e2eaf4;
    padding: 14px 24px;
    display: flex; align-items: center; justify-content: space-between;
    z-index: 100;
    margin: 0 -15px;
    box-shadow: 0 -4px 16px rgba(0,0,0,0.06);
}
.save-bar-info { font-size: 0.83rem; color: #6c757d; }
.save-bar-info strong { color: #1E9FF2; font-size: 1rem; }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="role-permissions">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body" style="padding-bottom:80px;">

                                    @php
                                        $totalPerms = $modules->sum(fn($m) =>
                                            isset($fonctionnalitesByModule[$m->id])
                                                ? $fonctionnalitesByModule[$m->id]->count() : 0
                                        );
                                    @endphp

                                    <form method="POST" action="{{ route('role.update', $role->id) }}" id="roleForm">
                                        @csrf
                                        @method('PUT')

                                        {{-- ── En-tête ── --}}
                                        <div class="role-header-bar">
                                            <div>
                                                <p class="role-subtitle mb-2">
                                                    <i class="la la-shield"></i> Gestion des permissions
                                                    @if(!$isSuperAdmin)
                                                        &nbsp;—&nbsp;
                                                        <span style="background:rgba(255,193,7,0.22); border-radius:10px; padding:2px 10px; font-size:0.78rem;">
                                                            <i class="la la-lock"></i> Modules système réservés au SuperAdmin
                                                        </span>
                                                    @endif
                                                </p>
                                                <div style="display:flex; align-items:center; gap:10px;">
                                                    <input type="text"
                                                           name="lib_role"
                                                           id="lib_role"
                                                           class="form-control @error('lib_role') is-invalid @enderror"
                                                           value="{{ $role->lib_role }}"
                                                           placeholder="Nom du rôle"
                                                           required
                                                           style="max-width:320px; font-weight:700; font-size:1rem;">
                                                    @error('lib_role')
                                                        <div class="invalid-feedback d-block" style="color:#ffe082;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="perm-badge-total">
                                                    <i class="la la-check-circle"></i>
                                                    <span id="totalCount">{{ $role->fonctionnalites->count() }}</span> / {{ $totalPerms }} permissions
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── Actions globales ── --}}
                                        <div class="global-actions">
                                            <span class="label">Sélection :</span>
                                            <button type="button" class="btn btn-sm btn-success" onclick="selectAll()">
                                                <i class="la la-check-square-o"></i> Tout activer
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">
                                                <i class="la la-square-o"></i> Tout désactiver
                                            </button>
                                            <div style="display:flex; gap:6px; margin-left:auto;">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="expandAll()">
                                                    <i class="la la-expand"></i> Tout déplier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="collapseAll()">
                                                    <i class="la la-compress"></i> Tout replier
                                                </button>
                                            </div>
                                        </div>

                                        {{-- ── Modules ── --}}
                                        @php
                                            $moduleIcons = [
                                                1  => 'la-users',
                                                2  => 'la-file-text',
                                                3  => 'la-exchange',
                                                4  => 'la-bar-chart',
                                                5  => 'la-cog',
                                                6  => 'la-paper-plane',
                                                7  => 'la-id-card',
                                                8  => 'la-gavel',
                                                9  => 'la-key',
                                                10 => 'la-archive',
                                                11 => 'la-eye',
                                                12 => 'la-desktop',
                                                13 => 'la-pie-chart',
                                                14 => 'la-print',
                                            ];
                                        @endphp

                                        @foreach($modules as $module)
                                            @php
                                                $perms = $fonctionnalitesByModule[$module->id] ?? collect();
                                                if ($perms->isEmpty()) continue;

                                                $checkedCount = $perms->filter(
                                                    fn($f) => $role->fonctionnalites->contains($f->id)
                                                )->count();

                                                $icon       = $moduleIcons[$module->id] ?? 'la-folder';
                                                $colorClass = 'mod-color-' . $module->id;
                                                $badgeClass = 'badge-mod-' . $module->id;
                                                $isLocked   = !$isSuperAdmin && in_array($module->id, $superAdminOnlyModules);
                                            @endphp

                                            <div class="module-card {{ $isLocked ? 'module-locked' : '' }}"
                                                 id="module-card-{{ $module->id }}">

                                                <div class="module-card-header"
                                                     id="module-header-{{ $module->id }}"
                                                     @if(!$isLocked) onclick="toggleModule({{ $module->id }})" @endif>
                                                    <div class="mod-left">
                                                        <div class="module-icon {{ $colorClass }}">
                                                            <i class="la {{ $icon }}"></i>
                                                        </div>
                                                        <div class="module-name">{{ $module->lib_module }}</div>
                                                        <span class="module-perm-badge {{ $badgeClass }}"
                                                              id="badge-mod-{{ $module->id }}">
                                                            {{ $checkedCount }}/{{ $perms->count() }}
                                                        </span>
                                                        @if($isLocked)
                                                            <span class="lock-badge">
                                                                <i class="la la-lock"></i> SUPERADMIN
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="mod-right">
                                                        @if(!$isLocked)
                                                            <button type="button"
                                                                    class="btn btn-xs btn-outline-success"
                                                                    style="padding:2px 8px; font-size:0.75rem;"
                                                                    onclick="event.stopPropagation(); selectModule({{ $module->id }})">
                                                                <i class="la la-check"></i> Tout
                                                            </button>
                                                            <button type="button"
                                                                    class="btn btn-xs btn-outline-secondary"
                                                                    style="padding:2px 8px; font-size:0.75rem;"
                                                                    onclick="event.stopPropagation(); deselectModule({{ $module->id }})">
                                                                <i class="la la-times"></i> Aucun
                                                            </button>
                                                            <i class="la la-angle-down mod-toggle-icon"
                                                               id="toggle-icon-{{ $module->id }}"></i>
                                                        @else
                                                            <i class="la la-lock" style="color:#e6a817; font-size:1.1rem;"></i>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="module-card-body" id="module-body-{{ $module->id }}">
                                                    <div class="perm-grid">
                                                        @foreach($perms as $fonctionnalite)
                                                            @php $isChecked = $role->fonctionnalites->contains($fonctionnalite->id); @endphp
                                                            <label class="perm-item {{ $isChecked ? 'is-checked' : '' }} {{ $isLocked ? 'is-locked' : '' }}"
                                                                   id="perm-wrap-{{ $fonctionnalite->id }}"
                                                                   for="perm_{{ $fonctionnalite->id }}">
                                                                <input type="checkbox"
                                                                       id="perm_{{ $fonctionnalite->id }}"
                                                                       class="permission-checkbox module-{{ $module->id }}-checkbox"
                                                                       name="fonctionnalites[]"
                                                                       value="{{ $fonctionnalite->id }}"
                                                                       {{ $isChecked ? 'checked' : '' }}
                                                                       {{ $isLocked ? 'disabled' : '' }}
                                                                       @if(!$isLocked) onchange="onPermChange(this, {{ $module->id }})" @endif>
                                                                <div>
                                                                    <span style="font-size:0.82rem; color:{{ $isChecked ? '#1a7a50' : '#444' }}; {{ $isChecked ? 'font-weight:600;' : '' }}">
                                                                        {{ $fonctionnalite->lib_fonctionnalite }}
                                                                    </span>
                                                                    <span class="perm-key">{{ $fonctionnalite->unique_key_string }}</span>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>

                                                    @if($isLocked)
                                                        <div class="module-locked-notice">
                                                            <i class="la la-info-circle" style="color:#e6a817; font-size:1.1rem;"></i>
                                                            Ces permissions sont <strong>réservées au SuperAdmin</strong> et ne peuvent pas être modifiées ici.
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        @endforeach

                                        {{-- ── Barre de sauvegarde fixe ── --}}
                                        <div class="save-bar">
                                            <div class="save-bar-info">
                                                <i class="la la-check-circle text-success"></i>
                                                <strong id="saveBarCount">{{ $role->fonctionnalites->count() }}</strong>
                                                permissions activées sur {{ $totalPerms }}
                                                @if(!$isSuperAdmin)
                                                    &nbsp;<span style="color:#856404; font-size:0.78rem;">
                                                        <i class="la la-lock"></i> modules système exclus
                                                    </span>
                                                @endif
                                            </div>
                                            <div style="display:flex; gap:10px;">
                                                <a href="{{ route('role.index') }}" class="btn btn-secondary">
                                                    <i class="la la-arrow-left"></i> Retour
                                                </a>
                                                <button type="submit" class="btn btn-success" id="saveBtn">
                                                    <i class="la la-save"></i> Sauvegarder
                                                </button>
                                            </div>
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

@push('scripts')
<script>
var lockedModules = @json($superAdminOnlyModules);
var isSuperAdmin  = @json($isSuperAdmin);
var collapsedModules = {};

function isLocked(moduleId) {
    return !isSuperAdmin && lockedModules.indexOf(parseInt(moduleId)) !== -1;
}

// ─── Plier / déplier ──────────────────────────────────────────────────
function toggleModule(moduleId) {
    if (isLocked(moduleId)) return;
    var body   = document.getElementById('module-body-' + moduleId);
    var icon   = document.getElementById('toggle-icon-' + moduleId);
    var header = document.getElementById('module-header-' + moduleId);
    if (collapsedModules[moduleId]) {
        body.style.display = '';
        if (icon) icon.style.transform = '';
        header.classList.remove('collapsed');
        collapsedModules[moduleId] = false;
    } else {
        body.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(-90deg)';
        header.classList.add('collapsed');
        collapsedModules[moduleId] = true;
    }
}

function expandAll() {
    document.querySelectorAll('[id^="module-body-"]').forEach(function(body) {
        body.style.display = '';
    });
    document.querySelectorAll('[id^="toggle-icon-"]').forEach(function(icon) {
        icon.style.transform = '';
    });
    document.querySelectorAll('.module-card-header').forEach(function(h) {
        h.classList.remove('collapsed');
    });
    collapsedModules = {};
}

function collapseAll() {
    document.querySelectorAll('[id^="module-body-"]').forEach(function(body) {
        var id = body.id.replace('module-body-', '');
        if (isLocked(id)) return;
        body.style.display = 'none';
        collapsedModules[id] = true;
    });
    document.querySelectorAll('[id^="toggle-icon-"]').forEach(function(icon) {
        icon.style.transform = 'rotate(-90deg)';
    });
}

// ─── Mise à jour visuelle d'un item ──────────────────────────────────
function onPermChange(checkbox, moduleId) {
    if (isLocked(moduleId)) return;
    var wrap      = document.getElementById('perm-wrap-' + checkbox.value);
    var labelSpan = wrap ? wrap.querySelector('div > span:first-child') : null;
    if (checkbox.checked) {
        if (wrap) wrap.classList.add('is-checked');
        if (labelSpan) { labelSpan.style.fontWeight = '600'; labelSpan.style.color = '#1a7a50'; }
    } else {
        if (wrap) wrap.classList.remove('is-checked');
        if (labelSpan) { labelSpan.style.fontWeight = ''; labelSpan.style.color = '#444'; }
    }
    updateCounts(moduleId);
}

// ─── Compteurs ────────────────────────────────────────────────────────
function updateCounts(moduleId) {
    if (moduleId) {
        var allInModule  = document.querySelectorAll('.module-' + moduleId + '-checkbox');
        var checkedInMod = document.querySelectorAll('.module-' + moduleId + '-checkbox:checked');
        var badge = document.getElementById('badge-mod-' + moduleId);
        if (badge) badge.textContent = checkedInMod.length + '/' + allInModule.length;
    }
    var total  = document.querySelectorAll('.permission-checkbox:checked').length;
    var totalEl = document.getElementById('totalCount');
    var saveEl  = document.getElementById('saveBarCount');
    if (totalEl) totalEl.textContent = total;
    if (saveEl)  saveEl.textContent  = total;
}

// ─── Sélection globale (ignore les modules verrouillés) ───────────────
function selectAll() {
    document.querySelectorAll('.permission-checkbox:not([disabled])').forEach(function(cb) {
        cb.checked = true;
        var wrap = document.getElementById('perm-wrap-' + cb.value);
        if (wrap) { wrap.classList.add('is-checked'); wrap.classList.remove('is-checked'); wrap.classList.add('is-checked'); }
    });
    refreshAllBadges();
}

function deselectAll() {
    document.querySelectorAll('.permission-checkbox:not([disabled])').forEach(function(cb) {
        cb.checked = false;
        var wrap = document.getElementById('perm-wrap-' + cb.value);
        if (wrap) wrap.classList.remove('is-checked');
    });
    refreshAllBadges();
}

function refreshAllBadges() {
    @foreach($modules as $module)
    updateCounts({{ $module->id }});
    @endforeach
}

// ─── Sélection par module ─────────────────────────────────────────────
function selectModule(moduleId) {
    if (isLocked(moduleId)) return;
    document.querySelectorAll('.module-' + moduleId + '-checkbox').forEach(function(cb) {
        cb.checked = true;
        var wrap = document.getElementById('perm-wrap-' + cb.value);
        if (wrap) wrap.classList.add('is-checked');
    });
    updateCounts(moduleId);
}

function deselectModule(moduleId) {
    if (isLocked(moduleId)) return;
    document.querySelectorAll('.module-' + moduleId + '-checkbox').forEach(function(cb) {
        cb.checked = false;
        var wrap = document.getElementById('perm-wrap-' + cb.value);
        if (wrap) wrap.classList.remove('is-checked');
    });
    updateCounts(moduleId);
}

// ─── Spinner sauvegarde ───────────────────────────────────────────────
document.getElementById('roleForm').addEventListener('submit', function() {
    var btn = document.getElementById('saveBtn');
    btn.innerHTML = '<i class="la la-spinner la-spin"></i> Sauvegarde...';
    btn.disabled = true;
});
</script>
@endpush