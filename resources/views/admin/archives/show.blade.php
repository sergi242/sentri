@extends('admin.layouts.app')
@section('title') Archives – {{ $impetrant->nom }} {{ $impetrant->prenom }} @endsection

@section('content')
<style>
    .arch-wrapper { padding: 1.5rem; background: #f4f7fa; }
    .bloc-card { background: white; border-radius: 14px; border: 1px solid #edf2f9; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 16px; overflow: visible; }
    .bloc-card-header { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
    .doc-card { border-radius: 12px; border: 1px solid #edf2f9; overflow: hidden; transition: 0.2s; background: white; height: 100%; }
    .doc-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .doc-thumb { width: 100%; height: 160px; object-fit: cover; background: #f8fafc; }
    .doc-thumb-pdf { width: 100%; height: 160px; background: #fef2f2; display: flex; align-items: center; justify-content: center; }
    .type-badge { font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
    .avatar-sm { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 3px solid #4834d4; }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <div class="arch-wrapper">

                {{-- HEADER --}}
                <div class="bloc-card mb-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap p-3" style="gap:15px;">
                        <div class="d-flex align-items-center" style="gap:14px;">
                            @php $photo = $impetrant->demandes->first()?->photo; @endphp
                            @if($photo)
                                <img src="{{ asset('app/'.$photo) }}" class="avatar-sm shadow-sm">
                            @else
                                <div class="avatar-sm d-flex align-items-center justify-content-center" style="background:#e2e8f0;">
                                    <i class="feather icon-user" style="font-size:1.5rem;color:#94a3b8;"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="font-weight-bold mb-0 text-uppercase">{{ $impetrant->nom }} {{ $impetrant->prenom }}</h5>
                                <small class="text-muted">{{ $impetrant->pays?->lib_pays ?? '—' }}</small>
                                @if($impetrant->date_naissance)
                                <br><small class="text-muted">Né(e) le {{ \Carbon\Carbon::parse($impetrant->date_naissance)->format('d/m/Y') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center" style="gap:10px;">
                            <span class="badge badge-primary" style="font-size:13px;padding:8px 14px;border-radius:20px;">
                                {{ $archives->count() }} document(s) archivé(s)
                            </span>
                            <a href="{{ route('archives.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                                <i class="feather icon-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">

                    {{-- COLONNE GAUCHE : formulaire --}}
                    <div class="col-md-4">
                        <div class="bloc-card" style="position:sticky;top:20px;">
                            <div class="bloc-card-header">
                                <strong><i class="feather icon-upload-cloud mr-1 text-primary"></i> Ajouter un document</strong>
                            </div>
                            <div class="p-3">
                                <form action="{{ route('archives.store', $impetrant->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Type de document *</label>
                                        <select name="type_document" id="type_document" class="form-control form-control-sm"
                                                style="border-radius:8px;" required
                                                onchange="document.getElementById('libelle-wrap').style.display = this.value === 'autre' ? 'block' : 'none'">
                                            <option value="">-- Sélectionner --</option>
                                            @foreach($typesDisponibles as $val => $lab)
                                            <option value="{{ $val }}">{{ $lab }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2" id="libelle-wrap" style="display:none;">
                                        <label class="small font-weight-bold text-muted">Préciser le type *</label>
                                        <input type="text" name="libelle" class="form-control form-control-sm"
                                               style="border-radius:8px;" placeholder="Ex: Acte de naissance...">
                                    </div>

                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Numéro du document</label>
                                        <input type="text" name="numero_document" class="form-control form-control-sm"
                                               style="border-radius:8px;" placeholder="Ex: AB123456">
                                    </div>

                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Date d'émission</label>
                                        <input type="date" name="date_emission" class="form-control form-control-sm" style="border-radius:8px;">
                                    </div>

                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Date d'expiration</label>
                                        <input type="date" name="date_expiration" class="form-control form-control-sm" style="border-radius:8px;">
                                    </div>

                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted">Notes</label>
                                        <textarea name="notes" class="form-control form-control-sm" rows="2"
                                                  style="border-radius:8px;" placeholder="Observations..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="small font-weight-bold text-muted">Fichier (image ou PDF) *</label>
                                        <div id="drop-zone"
                                             style="border:2px dashed #4834d4;border-radius:10px;padding:20px;text-align:center;cursor:pointer;transition:0.2s;background:#fafbff;"
                                             onclick="document.getElementById('fichier-input').click()"
                                             ondragover="event.preventDefault();this.style.background='#eff6ff'"
                                             ondragleave="this.style.background='#fafbff'"
                                             ondrop="handleDrop(event)">
                                            <i class="feather icon-upload-cloud" style="font-size:2rem;color:#4834d4;"></i>
                                            <p style="font-size:12px;color:#64748b;margin:6px 0 0;" id="drop-text">
                                                Cliquez ou glissez votre fichier ici<br>
                                                <small style="color:#94a3b8;">JPG, PNG, WEBP, PDF — max 8Mo</small>
                                            </p>
                                        </div>
                                        <input type="file" id="fichier-input" name="fichier"
                                               accept="image/*,.pdf" style="display:none;" required
                                               onchange="previewFile(this)">
                                        <div id="file-preview" style="display:none;margin-top:10px;text-align:center;">
                                            <img id="preview-img" src="" style="max-height:120px;border-radius:8px;border:1px solid #edf2f9;">
                                            <div id="preview-pdf" style="display:none;padding:12px;background:#fef2f2;border-radius:8px;">
                                                <i class="feather icon-file-text" style="color:#dc2626;font-size:2rem;"></i>
                                                <p id="preview-pdf-name" style="font-size:11px;color:#64748b;margin:4px 0 0;"></p>
                                            </div>
                                            <button type="button" onclick="clearFile()"
                                                    style="font-size:11px;color:#94a3b8;background:none;border:none;cursor:pointer;margin-top:4px;">
                                                × Changer de fichier
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block" style="border-radius:8px;">
                                        <i class="feather icon-save mr-1"></i> Archiver le document
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- COLONNE DROITE : documents archivés --}}
                    <div class="col-md-8">
                        @if($archives->count() === 0)
                        <div class="text-center py-5 text-muted">
                            <i class="feather icon-archive" style="font-size:3rem;color:#cbd5e1;"></i>
                            <p class="mt-3">Aucun document archivé pour cet impétrant</p>
                        </div>
                        @else

                        {{-- Filtres rapides par type --}}
                        @php $parType = $archives->groupBy('type_document'); @endphp
                        <div class="d-flex flex-wrap mb-3" style="gap:6px;">
                            <button class="btn btn-sm btn-primary filter-btn active" style="border-radius:20px;" data-filter="all">
                                Tous ({{ $archives->count() }})
                            </button>
                            @foreach($parType as $type => $docs)
                            <button class="btn btn-sm btn-outline-primary filter-btn" style="border-radius:20px;" data-filter="{{ $type }}">
                                {{ $docs->first()->typeLabel() }} ({{ $docs->count() }})
                            </button>
                            @endforeach
                        </div>

                        <div class="row" id="docs-grid">
                            @foreach($archives as $arch)
                            @php
                                $isPdf   = str_ends_with(strtolower($arch->chemin_fichier), '.pdf');
                                $expired = $arch->estExpire();
                            @endphp
                            <div class="col-md-6 col-12 mb-3 doc-item" data-type="{{ $arch->type_document }}">
                                <div class="doc-card">
                                    {{-- Aperçu --}}
                                    @if($isPdf)
                                    <div class="doc-thumb-pdf">
                                        <div class="text-center">
                                            <i class="feather icon-file-text" style="font-size:3rem;color:#dc2626;"></i>
                                            <p style="font-size:11px;color:#64748b;margin:4px 0 0;">Document PDF</p>
                                        </div>
                                    </div>
                                    @else
                                    <img src="{{ asset('storage/'.$arch->chemin_fichier) }}"
                                         class="doc-thumb"
                                         onclick="ouvrirLightbox('{{ asset('storage/'.$arch->chemin_fichier) }}', '{{ $arch->typeLabel() }}')"
                                         style="cursor:pointer;">
                                    @endif

                                    {{-- Infos --}}
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="type-badge" style="background:#eff6ff;color:#4834d4;border:1px solid #bfdbfe;">
                                                {{ $arch->typeLabel() }}
                                            </span>
                                            @if($expired)
                                            <span class="type-badge" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
                                                EXPIRÉ
                                            </span>
                                            @endif
                                        </div>

                                        @if($arch->numero_document)
                                        <div style="font-size:12px;color:#64748b;">
                                            <i class="feather icon-hash" style="font-size:11px;"></i>
                                            {{ $arch->numero_document }}
                                        </div>
                                        @endif

                                        <div class="d-flex" style="gap:12px;font-size:11px;color:#94a3b8;margin-top:6px;">
                                            @if($arch->date_emission)
                                            <span><i class="feather icon-calendar" style="font-size:10px;"></i> Émis {{ $arch->date_emission->format('d/m/Y') }}</span>
                                            @endif
                                            @if($arch->date_expiration)
                                            <span class="{{ $expired ? 'text-danger font-weight-bold' : '' }}">
                                                <i class="feather icon-clock" style="font-size:10px;"></i>
                                                Exp. {{ $arch->date_expiration->format('d/m/Y') }}
                                            </span>
                                            @endif
                                        </div>

                                        @if($arch->notes)
                                        <p style="font-size:11px;color:#64748b;margin-top:6px;margin-bottom:0;">
                                            {{ $arch->notes }}
                                        </p>
                                        @endif

                                        <div style="font-size:10px;color:#94a3b8;margin-top:6px;">
                                            Archivé par {{ $arch->user?->prenom }} {{ $arch->user?->nom }}
                                            le {{ $arch->created_at->format('d/m/Y') }}
                                        </div>

                                        {{-- Actions --}}
                                        <div class="d-flex mt-3" style="gap:6px;">
                                            <a href="{{ route('archives.print', $arch->id) }}" target="_blank"
                                               class="btn btn-primary btn-sm flex-fill" style="border-radius:8px;font-size:11px;">
                                                <i class="feather icon-printer"></i> Imprimer
                                            </a>
                                            <a href="{{ asset('storage/'.$arch->chemin_fichier) }}" target="_blank"
                                               class="btn btn-outline-primary btn-sm" style="border-radius:8px;font-size:11px;" title="Voir">
                                                <i class="feather icon-eye"></i>
                                            </a>
                                            <a href="{{ asset('storage/'.$arch->chemin_fichier) }}" download="{{ $arch->nom_original }}"
                                               class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-size:11px;" title="Télécharger">
                                                <i class="feather icon-download"></i>
                                            </a>
                                            <form action="{{ route('archives.destroy', $arch->id) }}" method="POST"
                                                  onsubmit="return confirm('Supprimer ce document ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        style="border-radius:8px;font-size:11px;" title="Supprimer">
                                                    <i class="feather icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- LIGHTBOX --}}
<div id="lightbox"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:10000;align-items:center;justify-content:center;flex-direction:column;"
     onclick="if(event.target===this) fermerLightbox()">
    <button onclick="fermerLightbox()"
            style="position:absolute;top:20px;right:24px;background:none;border:none;color:white;font-size:2rem;cursor:pointer;">×</button>
    <img id="lightbox-img" src="" style="max-width:90vw;max-height:85vh;border-radius:12px;object-fit:contain;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
    <p id="lightbox-caption" style="color:rgba(255,255,255,0.7);margin-top:12px;font-size:13px;"></p>
</div>

<script>
// Lightbox
function ouvrirLightbox(src, caption) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-caption').textContent = caption;
    document.getElementById('lightbox').style.display = 'flex';
}
function fermerLightbox() {
    document.getElementById('lightbox').style.display = 'none';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') fermerLightbox(); });

// Preview fichier
function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('drop-text').innerHTML = '✓ ' + file.name;
    document.getElementById('file-preview').style.display = 'block';
    if (file.type === 'application/pdf') {
        document.getElementById('preview-img').style.display = 'none';
        document.getElementById('preview-pdf').style.display = 'block';
        document.getElementById('preview-pdf-name').textContent = file.name;
    } else {
        document.getElementById('preview-pdf').style.display = 'none';
        document.getElementById('preview-img').style.display = 'block';
        const reader = new FileReader();
        reader.onload = e => document.getElementById('preview-img').src = e.target.result;
        reader.readAsDataURL(file);
    }
}
function clearFile() {
    document.getElementById('fichier-input').value = '';
    document.getElementById('file-preview').style.display = 'none';
    document.getElementById('drop-text').innerHTML = 'Cliquez ou glissez votre fichier ici<br><small style="color:#94a3b8;">JPG, PNG, WEBP, PDF — max 8Mo</small>';
}
function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.style.background = '#fafbff';
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('fichier-input');
        input.files = dt.files;
        previewFile(input);
    }
}

// Filtres par type
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-primary');
        });
        this.classList.add('btn-primary', 'active');
        this.classList.remove('btn-outline-primary');
        const filter = this.dataset.filter;
        document.querySelectorAll('.doc-item').forEach(item => {
            item.style.display = (filter === 'all' || item.dataset.type === filter) ? '' : 'none';
        });
    });
});
</script>
@endsection