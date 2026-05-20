@extends('admin.layouts.app')

@section('title') Audit de Similarités – Détection de doublons @endsection

@section('content')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<style>
    :root {
        --primary-soft: #4834d4;
        --bg-light: #f4f7fa;
        --success-color: #27ae60;
        --danger-color: #eb4d4b;
    }

    .similarity-wrapper {
        padding: 1.5rem;
        background: var(--bg-light);
        border-radius: 15px;
    }

    .audit-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-start;
    }

    .side-panel {
        flex: 0 0 300px;
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #edf2f9;
    }

    .stack-zone {
        flex: 1;
        min-width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .swiper {
        width: 360px;
        height: 580px;
        margin: 20px 0;
    }

    .stacked-card {
        background: white;
        width: 100%;
        height: 100%;
        border-radius: 20px;
        padding: 25px;
        display: flex;
        flex-direction: column;
        box-shadow: none !important;
        border: 1px solid #eee;
        position: relative;
    }

    /* Supprime uniquement les éléments d'ombre de Swiper */
    .swiper-slide-shadow,
    .swiper-slide-shadow-left,
    .swiper-slide-shadow-right,
    .swiper-slide-shadow-top,
    .swiper-slide-shadow-bottom {
        display: none !important;
    }

    .swiper-slide {
        box-shadow: none !important;
    }

    .nav-arrow {
        position: absolute;
        top: 55%;
        transform: translateY(-50%);
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        cursor: pointer;
        z-index: 10;
        color: var(--primary-soft);
        transition: 0.3s;
        border: 1px solid #eee;
    }
    .nav-arrow:hover { background: var(--primary-soft); color: white; }
    .arrow-left { left: -10px; }
    .arrow-right { right: -10px; }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        background: #f8f9fb;
        padding: 15px;
        border-radius: 12px;
        margin-top: 15px;
    }

    .detail-item label {
        font-size: 9px;
        text-transform: uppercase;
        color: #a0aec0;
        font-weight: 700;
        margin-bottom: 2px;
        display: block;
    }

    .detail-item span {
        font-size: 11px;
        font-weight: 600;
        color: #2d3748;
        display: block;
        line-height: 1.2;
    }

    .score-tag {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--danger-color);
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 10px;
        z-index: 5;
    }

    .ref-avatar-circle {
        width: 70px; height: 70px;
        border-radius: 50%;
        border: 3px solid var(--primary-soft);
        object-fit: cover;
    }

    .btn-approve-success {
        background-color: var(--success-color);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }
    .btn-approve-success:hover { background-color: #219150; color: white; }

    @keyframes pulse {
        0%   { transform: scale(1);   opacity: 1; }
        50%  { transform: scale(1.4); opacity: 0.6; }
        100% { transform: scale(1);   opacity: 1; }
    }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">

            <div class="similarity-wrapper">
                <div class="audit-grid">

                    {{-- PANNEAU GAUCHE : Dossier de référence --}}
                    <div class="side-panel">
                        <div class="text-center border-bottom pb-2 mb-2">
                            <h6 class="text-uppercase text-muted small font-weight-bold">Dossier de Référence</h6>
                            <img src="{{ $demande->photo ? asset('app/'.$demande->photo) : asset('img/avatar-default.png') }}" class="ref-avatar-circle my-1 shadow-sm">

                            {{-- Nom avec point rouge animé si contentieux --}}
                            @php
                                $enContentieux = $demande->impetrant->demandes()
                                    ->where('statut_demande', 'Envoyée au contentieux')
                                    ->exists();
                            @endphp

                            <div class="d-flex align-items-center justify-content-center">
                                <h5 class="mb-0 font-weight-bold text-primary text-uppercase">{{ $demande->impetrant->nom }}</h5>
                                @if($enContentieux)
                                    <span title="Ce dossier est au contentieux" style="
                                        width: 10px; height: 10px;
                                        background: #eb4d4b;
                                        border-radius: 50%;
                                        display: inline-block;
                                        margin-left: 6px;
                                        animation: pulse 1.5s infinite;
                                        flex-shrink: 0;
                                    "></span>
                                @endif
                            </div>

                            <small class="text-dark">{{ $demande->impetrant->prenom }}</small>

                            @if($enContentieux)
                                <div class="mt-2">
                                    <span class="badge badge-danger px-2 py-1" style="font-size: 10px; border-radius: 8px;">
                                        <i class="feather icon-alert-triangle"></i> Dossier au contentieux
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="small">
                            <div class="mb-1">
                                <label class="text-muted small mb-0 d-block">Profession & Nationalité</label>
                                <span class="font-weight-bold text-dark">{{ $demande->profession ?? 'Non renseigné' }}</span><br>
                                <span class="badge badge-light-primary mt-1">{{ $demande->impetrant->nationalite ?? '—' }}</span>
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Né(e) le:</span>
                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($demande->impetrant->date_naissance)->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Lieu:</span>
                                <span class="font-weight-bold text-truncate ml-1">{{ $demande->impetrant->lieu_naissance }}</span>
                            </div>
                        </div>

                        <div class="alert alert-warning py-1 mt-3 text-center" style="font-size: 11px;">
                            <i class="feather icon-alert-circle"></i> Comparez les dossiers suspects avec ces informations.
                        </div>
                    </div>

                    {{-- ZONE CENTRALE : La pile de cartes --}}
                    <div class="stack-zone">
                        <div class="text-center">
                            <h4 class="font-weight-bold mb-0">Analyse des Suspects</h4>
                            <p class="text-muted small">Glissez à gauche pour écarter, ou traitez chaque carte</p>
                        </div>

                        @if(count($sims) > 1)
                            <div class="nav-arrow arrow-left"><i class="feather icon-chevron-left"></i></div>
                            <div class="nav-arrow arrow-right"><i class="feather icon-chevron-right"></i></div>
                        @endif

                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @forelse ($sims as $item)
                                    @php
                                        $d = $item['demande'];
                                        $i = $d->impetrant;
                                        $score = (int)$item['score'];
                                        $suspectContentieux = $i->demandes()
                                            ->where('statut_demande', 'Envoyée au contentieux')
                                            ->exists();
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="stacked-card">
                                            <span class="score-tag">{{ $score }}% Similarité</span>

                                            <div class="text-center mb-1">
                                                <img src="{{ $d->photo ? asset('app/'.$d->photo) : asset('img/avatar-default.png') }}"
                                                     style="width: 110px; height: 110px; border-radius: 15px; object-fit: cover;" class="shadow-sm border">

                                                <div class="d-flex align-items-center justify-content-center mt-1">
                                                    <h5 class="font-weight-bold mb-0 text-primary text-uppercase">{{ $i->nom }}</h5>
                                                    @if($suspectContentieux)
                                                        <span title="Dossier au contentieux" style="
                                                            width: 9px; height: 9px;
                                                            background: #eb4d4b;
                                                            border-radius: 50%;
                                                            display: inline-block;
                                                            margin-left: 6px;
                                                            animation: pulse 1.5s infinite;
                                                            flex-shrink: 0;
                                                        "></span>
                                                    @endif
                                                </div>

                                                <p class="text-muted small mb-0">{{ $i->prenom }}</p>

                                                @if($suspectContentieux)
                                                    <span class="badge badge-danger mt-1" style="font-size: 9px; border-radius: 6px;">
                                                        <i class="feather icon-alert-triangle"></i> Au contentieux
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="detail-grid">
                                                <div class="detail-item">
                                                    <label>Naissance</label>
                                                    <span>{{ \Carbon\Carbon::parse($i->date_naissance)->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Lieu</label>
                                                    <span class="text-truncate">{{ $i->lieu_naissance ?? '—' }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Nationalité</label>
                                                    <span>{{ $i->nationalite ?? '—' }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Profession</label>
                                                    <span class="text-truncate">{{ $d->profession ?? '—' }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Sexe</label>
                                                    <span>{{ $i->sexe }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <label>Dossier #</label>
                                                    <span>{{ $d->id }}</span>
                                                </div>
                                            </div>

                                            <div class="mt-auto">
                                                <a href="{{ route('demandes.compareSimilarity', ['base' => $demande->id, 'similar' => $d->id]) }}"
                                                   class="btn btn-primary btn-block shadow-sm py-1 font-weight-bold">
                                                    <i class="feather icon-search"></i> Comparer
                                                </a>
                                                <form method="POST" action="{{ route('demandes.similarities.reject', ['demande' => $demande->id, 'similaire' => $d->id]) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link btn-block text-danger btn-sm mt-1">
                                                        Écarter ce suspect
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="swiper-slide">
                                        <div class="stacked-card justify-content-center align-items-center text-center">
                                            <div class="bg-light rounded-circle p-2 mb-2">
                                                <i class="feather icon-shield text-success display-4"></i>
                                            </div>
                                            <h4 class="font-weight-bold">Ménage terminé</h4>
                                            <p class="text-muted small">Toutes les similarités ont été traitées. Vous pouvez maintenant approuver le dossier.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- PANNEAU DROIT : Statut & Actions --}}
                    <div class="side-panel">
                        <h6 class="text-uppercase text-muted small font-weight-bold mb-2">Statut de l'Audit</h6>

                        <div class="text-center py-2 bg-light rounded mb-3 border">
                            <span id="suspect-counter" class="h3 font-weight-bold {{ count($sims) > 0 ? 'text-warning' : 'text-success' }}">
                                {{ count($sims) }}
                            </span>
                            <p class="small text-muted mb-0 font-weight-bold">Suspects restants</p>
                        </div>

                        <div id="approve-zone">
                            @if (count($sims) === 0)
                                @if ($demande->statut_demande == "En attente d'approbation")
                                    <div class="text-center mb-2">
                                        <span class="badge badge-pill badge-light-success">Dossier prêt</span>
                                    </div>
                                    <form action="{{ route('approuver.simple', $demande->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="statut_demande" value="Approuvée">
                                        <button type="submit" class="btn btn-approve-success btn-block font-weight-bold py-1">
                                            <i class="feather icon-check-circle"></i> Approuver
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-info small text-center">
                                        Dossier (Statut : {{ $demande->statut_demande }})
                                    </div>
                                @endif
                            @else
                                <div id="locked-zone" class="p-2 border rounded bg-light mb-2 text-center">
                                    <i class="feather icon-lock text-muted"></i>
                                    <p class="small text-muted mb-0 mt-1">Approbation bloquée jusqu'au traitement total des suspects.</p>
                                </div>
                                <button id="disabled-approve-btn"
                                        class="btn btn-secondary btn-block disabled opacity-50"
                                        data-approve-url="{{ route('demandes.changestate', $demande->id) }}"
                                        data-csrf="{{ csrf_token() }}"
                                        data-statut="{{ $demande->statut_demande }}"
                                        disabled>
                                    Approuver
                                </button>
                            @endif
                        </div>

                        <hr>

                        <a href="{{ route('demandes.similarities.rejected', $demande->id) }}"
                           class="btn btn-archive btn-block btn-sm py-1 mb-2 shadow-sm">
                            <i class="feather icon-eye"></i> Voir les dossiers écartés
                        </a>
                        <button onclick="window.location.reload()" class="btn btn-outline-secondary btn-block btn-sm mb-1">
                            <i class="feather icon-refresh-cw"></i> Actualiser
                        </button>
                        <a href="{{ route('demandes.index') }}" class="btn btn-link btn-block text-muted small">
                            <i class="feather icon-arrow-left"></i> Retour aux dossiers
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const THRESHOLD = 100;

    var swiper = new Swiper(".mySwiper", {
        effect: "cards",
        grabCursor: true,
        rotate: true,
        simulateTouch: false,
        allowTouchMove: false,
        cardsEffect: {
            slideShadows: false,
        },
        navigation: {
            nextEl: ".arrow-right",
            prevEl: ".arrow-left",
        },
    });

    function getActiveCard() {
        const slide = swiper.slides[swiper.activeIndex];
        return slide ? slide.querySelector('.stacked-card') : null;
    }

    function getRejectForm() {
        const slide = swiper.slides[swiper.activeIndex];
        return slide ? slide.querySelector('form[action*="reject"]') : null;
    }

    let startX = 0;
    let isDragging = false;
    let movedEnough = false;
    const swiperEl = document.querySelector('.mySwiper');

    // ── MOUSE ──────────────────────────────────────────────
    swiperEl.addEventListener('mousedown', (e) => {
        if (e.target.closest('a, button, form')) return;
        startX = e.clientX;
        isDragging = true;
        movedEnough = false;
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const deltaX = e.clientX - startX;
        if (Math.abs(deltaX) > 5) movedEnough = true;
        applyDrag(deltaX);
    });

    window.addEventListener('mouseup', (e) => {
        if (!isDragging) return;
        isDragging = false;
        if (!movedEnough) return;
        const deltaX = e.clientX - startX;
        releaseDrag(deltaX);
    });

    // ── TOUCH ──────────────────────────────────────────────
    swiperEl.addEventListener('touchstart', (e) => {
        if (e.target.closest('a, button, form')) return;
        startX = e.touches[0].clientX;
        movedEnough = false;
    }, { passive: true });

    swiperEl.addEventListener('touchmove', (e) => {
        const deltaX = e.touches[0].clientX - startX;
        if (Math.abs(deltaX) > 5) movedEnough = true;
        applyDrag(deltaX);
    }, { passive: true });

    swiperEl.addEventListener('touchend', (e) => {
        if (!movedEnough) return;
        const deltaX = e.changedTouches[0].clientX - startX;
        releaseDrag(deltaX);
    });

    // ── APPLIQUER LE DRAG ──────────────────────────────────
    function applyDrag(deltaX) {
        const card = getActiveCard();
        if (!card) return;

        if (deltaX >= 0) {
            card.style.transition = 'none';
            card.style.transform = '';
            card.style.opacity = '1';
            const badge = card.querySelector('.swipe-badge');
            if (badge) badge.remove();
            animateBackCards(0);
            return;
        }

        const rotate = deltaX / 20;
        const opacity = Math.max(0.4, 1 + deltaX / 300);

        card.style.transition = 'none';
        card.style.transform = `translateX(${deltaX}px) rotate(${rotate}deg)`;
        card.style.opacity = opacity;

        const progress = Math.min(1, Math.abs(deltaX) / THRESHOLD);
        animateBackCards(progress);

        let badge = card.querySelector('.swipe-badge');
        if (!badge) {
            badge = document.createElement('div');
            badge.className = 'swipe-badge';
            badge.innerHTML = '✕ ÉCARTÉ';
            badge.style.cssText = `
                position: absolute; top: 20px; left: 20px;
                background: #eb4d4b; color: white;
                padding: 6px 14px; border-radius: 8px;
                font-weight: bold; font-size: 13px;
                border: 2px solid white;
                pointer-events: none; z-index: 10;
                opacity: 0;
            `;
            card.appendChild(badge);
        }
        badge.style.opacity = Math.min(1, Math.abs(deltaX) / THRESHOLD);
    }

    // ── ANIMER LES CARTES DERRIÈRE ─────────────────────────
    function animateBackCards(progress) {
        const slides = swiper.slides;
        const activeIndex = swiper.activeIndex;

        slides.forEach((slide, index) => {
            if (index <= activeIndex) return;

            const depth = index - activeIndex;
            const card = slide.querySelector('.stacked-card');
            if (!card) return;

            const baseScale   = 1 - depth * 0.05;
            const baseOffsetY = depth * 15;

            const targetScale   = baseScale + (1 - baseScale) * progress * 0.6;
            const targetOffsetY = baseOffsetY - baseOffsetY   * progress * 0.6;

            card.style.transition = 'none';
            card.style.transform  = `translateY(${-targetOffsetY}px) scale(${targetScale})`;
        });
    }

    // ── RELÂCHER ──────────────────────────────────────────
    function releaseDrag(deltaX) {
        const card = getActiveCard();
        if (!card) return;
        const badge = card.querySelector('.swipe-badge');

        if (deltaX < -THRESHOLD) {
            card.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
            card.style.transform = 'translateX(-700px) rotate(-30deg)';
            card.style.opacity = '0';

            animateBackCards(1);

            setTimeout(() => {
                const form = getRejectForm();
                if (!form) return;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                })
                .then(() => {
                    swiper.removeSlide(swiper.activeIndex);

                    setTimeout(() => {
                        swiper.slides.forEach(slide => {
                            const c = slide.querySelector('.stacked-card');
                            if (c) { c.style.transform = ''; c.style.transition = ''; }
                        });
                    }, 50);

                    const counter = document.getElementById('suspect-counter');
                    if (counter) {
                        const newCount = Math.max(0, parseInt(counter.textContent) - 1);
                        counter.textContent = newCount;
                        counter.classList.remove('text-warning', 'text-success');
                        counter.classList.add(newCount > 0 ? 'text-warning' : 'text-success');
                        if (newCount === 0) showApproveButton();
                    }
                })
                .catch(err => console.error('Erreur reject:', err));

            }, 420);

        } else {
            card.style.transition = 'transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease';
            card.style.transform = '';
            card.style.opacity = '1';
            if (badge) badge.remove();

            swiper.slides.forEach((slide, index) => {
                if (index <= swiper.activeIndex) return;
                const c = slide.querySelector('.stacked-card');
                if (c) {
                    c.style.transition = 'transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    c.style.transform = '';
                    setTimeout(() => { c.style.transition = ''; }, 500);
                }
            });
        }
    }

    // ── AFFICHER LE BOUTON APPROUVER ──────────────────────
    function showApproveButton() {
        const disabledBtn = document.getElementById('disabled-approve-btn');
        const lockedZone  = document.getElementById('locked-zone');
        if (!disabledBtn) return;

        const statut = disabledBtn.dataset.statut;
        const url    = disabledBtn.dataset.approveUrl;
        const csrf   = disabledBtn.dataset.csrf;

        if (lockedZone) lockedZone.remove();

        if (statut === "En attente d'approbation") {
            disabledBtn.outerHTML = `
                <div class="text-center mb-2">
                    <span class="badge badge-pill badge-light-success">Dossier prêt</span>
                </div>
                <form action="${url}" method="POST">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="statut_demande" value="Approuvée">
                    <button type="submit" class="btn btn-approve-success btn-block font-weight-bold py-1">
                        <i class="feather icon-check-circle"></i> Approuver
                    </button>
                </form>
            `;
        } else {
            disabledBtn.outerHTML = `
                <div class="alert alert-info small text-center">
                    Dossier (Statut : ${statut})
                </div>
            `;
        }
    }

});
</script>

@endsection