<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item mr-auto"><a class="navbar-brand" href="">
                        <h3 class="brand-text">DMCE</h3>
                    </a></li>
                <li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
                <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left"></ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="mr-1 user-devise text-bold-700">
                                @auth
                                    {{auth()->user()->prenom}} {{auth()->user()->nom}} ({{ auth()->user()->role?->lib_role ?? ""}})
                                </span>
                                <span class="avatar avatar-online">
                                    <img src="{{ asset("img/grades/".auth()?->user()?->grade?->grade.'.png') }}" alt="avatar"><i></i>
                                </span>
                            @endauth
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href=""><i class="ft-user"></i> Mon profil</a>
                            <a class="dropdown-item" href="{{ route("users.changepassword") }}"><i class="ft-check-square"></i> Modifier mot de passe</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item f-logout" href=""><i class="ft-power"></i> Déconnexion</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form class="d-none form-logout" action="{{route('logout')}}" method="POST">
        @csrf
        <input type="submit" value="logout">
    </form>
</nav>
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            {{-- Accueil --}}
            <li class="nav-item {{ request()->routeIs('users.home') ? 'active' : '' }}">
                <a href="{{route('users.home')}}">
                    <i class="la la-home"></i>
                    <span class="menu-title" data-i18n="Dashboard">Accueil</span>
                </a>
            </li>

            {{-- Tableau de bord --}}
            @can("dashboard.view")
            <li class="nav-item {{ request()->routeIs('users.dashboard') ? 'active' : '' }}">
                <a href="{{route('users.dashboard')}}">
                    <i class="la la-dashboard"></i>
                    <span class="menu-title" data-i18n="Dashboard">Tableau de bord</span>
                </a>
            </li>
            @endcan

            {{-- Utilisateurs --}}
            @can("users.view")
            <li class="nav-item {{ request()->routeIs('users.*', 'role.*', 'grade.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-users"></i>
                    <span class="menu-title" data-i18n="Templates">Utilisateurs</span>
                </a>
                <ul class="menu-content">
                    @can("users.view")
                    <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('users.index') }}">
                            <i class="la la-list"></i>
                            <span data-i18n="Vertical">Liste des Utilisateurs</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('users.activites') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('users.activites') }}">
                            <i class="la la-bar-chart"></i>
                            <span data-i18n="Vertical">Activité des Utilisateurs</span>
                        </a>
                    </li>
                    @endcan
                    @can("roles.view")
                    <li class="{{ request()->routeIs('role.*') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('role.index') }}">
                            <i class="la la-briefcase"></i>
                            <span data-i18n="Vertical">Rôles</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('grade.*') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('grade.index') }}">
                            <i class="la la-certificate"></i>
                            <span data-i18n="Vertical">Grades</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
{{-- ── IMPÉTRANTS ──────────────────────────────────────────────────────── --}}
@can("demandes.view.all")
<li class="nav-item {{ request()->routeIs('impetrants.*', 'casier.*', 'archives.*') ? 'open' : '' }}">
    <a href="#">
        <i class="la la-id-card"></i>
        <span class="menu-title">Impétrants</span>
    </a>
    <ul class="menu-content">
        <li class="{{ request()->routeIs('impetrants.create') ? 'active' : '' }}">
            <a href="{{ route('impetrants.create') }}">
                <i class="la la-user-plus mr-1"></i>
                <span>Enregistrer un impétrant</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('impetrants.index') ? 'active' : '' }}">
            <a href="{{ route('impetrants.index') }}">
                <i class="la la-list mr-1"></i>
                <span>Tous les impétrants</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('casier.global') ? 'active' : '' }}">
            <a href="{{ route('casier.global') }}">
                <i class="la la-book mr-1"></i>
                <span>Casier judiciaire global</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('archives.index') ? 'active' : '' }}">
            <a href="{{ route('archives.index') }}">
                <i class="la la-archive mr-1"></i>
                <span>Archivage</span>
            </a>
        </li>
    </ul>
</li>
@endcan
            {{-- Contrôle des étrangers --}}
            @can("demandes.view.all")
            <li class="nav-item {{ request()->routeIs('demandes.proche.expiration') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-bus"></i>
                    <span class="menu-title" data-i18n="Templates">Contrôle des étrangers</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('demandes.proche.expiration') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.proche.expiration")}}">
                            <i class="la la-clock-o"></i>
                            <span data-i18n="Vertical">Expiration dans 3 mois</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Approbations --}}
            @can("demandes.view.approved")
            <li class="nav-item {{ request()->routeIs('demandes.attentes', 'demandes.approuvees', 'demandes.impressioncartes', 'demandes.retirees') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-check-square"></i>
                    <span class="menu-title" data-i18n="Templates">Approbations</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('demandes.attentes') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.attentes")}}">
                            <i class="la la-hourglass-half"></i>
                            <span data-i18n="Vertical">Demandes en attente</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('demandes.approuvees') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.approuvees")}}">
                            <i class="la la-check-circle"></i>
                            <span data-i18n="Vertical">Demandes approuvées</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('demandes.impressioncartes') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.impressioncartes")}}">
                            <i class="la la-print"></i>
                            <span data-i18n="Vertical">Impression cartes</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('demandes.retirees') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.retirees")}}">
                            <i class="la la-trash"></i>
                            <span data-i18n="Vertical">Demandes retirées</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Soit-Transmis --}}
            <li class="nav-item {{ request()->routeIs('soit-transmis.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-folder-open"></i>
                    <span class="menu-title" data-i18n="Templates">Soit-Transmis</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('soit-transmis.create') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("soit-transmis.create")}}">
                            <i class="la la-plus-circle"></i>
                            <span data-i18n="Vertical">Créer un Soit-Transmis</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('soit-transmis.index') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("soit-transmis.index")}}">
                            <i class="la la-list"></i>
                            <span data-i18n="Vertical">Les Soit-Transmis</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('soit-transmis.attribution.masse.form') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('soit-transmis.attribution.masse.form') }}">
                            <i class="la la-check-square-o"></i>
                            <span class="menu-title">Attribution en Masse</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Sécurité --}}
            <li class="nav-item {{ request()->routeIs('watchlist.*', 'demandes.contentieux', 'casier.global', 'demandes.renouvellements-bloques', 'monitor.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-shield"></i>
                    <span class="menu-title" data-i18n="Templates">Sécurité</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('watchlist.*') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('watchlist.index') }}">
                            <i class="la la-exclamation-triangle"></i>
                            <span data-i18n="Vertical">Liste d'Alerte (Watchlist)</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('demandes.contentieux') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('demandes.contentieux') }}">
                            <i class="la la-gavel"></i>
                            <span data-i18n="Vertical">Demandes au contentieux</span>
                        </a>
                    </li>
                    @if(in_array(auth()->user()?->role?->lib_role, ['SuperAdmin', 'Admin']))
                    <li class="{{ request()->routeIs('demandes.renouvellements-bloques') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('demandes.renouvellements-bloques') }}">
                            <i class="la la-ban"></i>
                            <span data-i18n="Vertical">Renouvellements bloqués</span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ request()->routeIs('monitor.*') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('monitor.index') }}">
                            <i class="la la-tv"></i>
                            <span class="menu-title">Moniteur</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Archivage --}}
            <li class="nav-item {{ request()->routeIs('archives.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-archive"></i>
                    <span class="menu-title" data-i18n="Templates">Archivage</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('archives.index') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('archives.index') }}">
                            <i class="la la-folder"></i>
                            <span data-i18n="Vertical">Tous les archives</span>
                        </a>
                    </li>
                </ul>
            </li>

            @endcan

            {{-- Impression cartes --}}
            @can("demandes.print.cards")
            <li class="nav-item {{ request()->routeIs('demandes.impressioncartes') ? 'active' : '' }}">
                <a href="#">
                    <i class="la la-credit-card"></i>
                    <span class="menu-title" data-i18n="Templates">Impression cartes</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{route("demandes.impressioncartes")}}">
                            <i class="la la-print"></i>
                            <span data-i18n="Vertical">Demandes à imprimer</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Attributions --}}
            @can("demandes.view.attribue")
            <li class="nav-item {{ request()->routeIs('demandes.attente.attributions', 'demandes.attribuees') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-server"></i>
                    <span class="menu-title" data-i18n="Templates">Attributions</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('demandes.attente.attributions') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.attente.attributions")}}">
                            <i class="la la-hourglass-half"></i>
                            <span data-i18n="Vertical">En attente d'attribution</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('demandes.attribuees') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.attribuees")}}">
                            <i class="la la-check"></i>
                            <span data-i18n="Vertical">Demandes attribuées</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('soit-transmis.attribution.masse.form') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('soit-transmis.attribution.masse.form') }}">
                            <i class="la la-check-square-o"></i>
                            <span class="menu-title">Attribution en Masse</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Dossiers des demandes --}}
            @can("demandes.view.all")
            <li class="nav-item {{ request()->routeIs('demandes.newdocument', 'demandes.renouvellement', 'demandes.index') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-folder"></i>
                    <span class="menu-title" data-i18n="Templates">Dossiers des demandes</span>
                </a>
                <ul class="menu-content">
                    @can("demandes.create")
                    <li class="{{ request()->routeIs('demandes.newdocument') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.newdocument")}}">
                            <i class="la la-plus-circle"></i>
                            <span data-i18n="Vertical">Nouvelle demande</span>
                        </a>
                    </li>
                    @endcan
                    @can("demandes.renew")
                    <li class="{{ request()->routeIs('demandes.renouvellement') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.renouvellement")}}">
                            <i class="la la-refresh"></i>
                            <span data-i18n="Vertical">Renouveler demande</span>
                        </a>
                    </li>
                    @endcan
                    @can("demandes.view.all")
                    <li class="{{ request()->routeIs('demandes.index') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("demandes.index")}}">
                            <i class="la la-list"></i>
                            <span data-i18n="Vertical">Toutes les demandes</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan

            {{-- Certificats d'Hébergement --}}
            @can("demandes.view.all")
            <li class="nav-item {{ request()->routeIs('certificats-hebergement.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-building"></i>
                    <span class="menu-title">Certificats d'Hébergement</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('certificats-hebergement.create') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('certificats-hebergement.create') }}">
                            <i class="la la-plus-circle"></i>
                            <span>Nouveau certificat</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('certificats-hebergement.index') && !request('statut') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('certificats-hebergement.index') }}">
                            <i class="la la-list"></i>
                            <span>Tous les certificats</span>
                        </a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('certificats-hebergement.index', ['statut' => 'En attente']) }}">
                            <i class="la la-clock-o"></i>
                            <span>En attente</span>
                        </a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('certificats-hebergement.index', ['statut' => 'Validé']) }}">
                            <i class="la la-check-circle"></i>
                            <span>Validés</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('certificats-hebergement.relations') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('certificats-hebergement.relations') }}">
                            <i class="la la-exchange"></i>
                            <span>Relations hébergeur/hébergé</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('certificats-hebergement.statistiques-avancees') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('certificats-hebergement.statistiques-avancees') }}">
                            <i class="la la-bar-chart"></i>
                            <span>Statistiques avancées</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('certificats-hebergement.statistiques') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('certificats-hebergement.statistiques') }}">
                            <i class="la la-bar-chart"></i>
                            <span>Statistiques</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Flux migratoire --}}
            @can("flux.view")
            <li class="nav-item {{ request()->routeIs('flux.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-trello"></i>
                    <span class="menu-title" data-i18n="Templates">Flux migratoire</span>
                </a>
                <ul class="menu-content">
                    @can("flux.create")
                    <li class="{{ request()->routeIs('flux.create') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("flux.create")}}">
                            <i class="la la-plus-circle"></i>
                            <span data-i18n="Vertical">Ajouter des données</span>
                        </a>
                    </li>
                    @endcan
                    <li class="{{ request()->routeIs('flux.index') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route("flux.index")}}">
                            <i class="la la-list"></i>
                            <span data-i18n="Vertical">Toutes les données</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Statistiques générales --}}
            @can("flux.view")
            <li class="nav-item {{ request()->routeIs('graphes.*', 'statistiques.*', 'reporting.*', 'rapports.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-pie-chart"></i>
                    <span class="menu-title" data-i18n="Templates">Statistiques générales</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('graphes.flux-demande') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route("graphes.flux-demande") }}">
                            <i class="la la-bar-chart"></i>
                            <span data-i18n="Vertical">Graphes sur les demandes</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('statistiques.index') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route('statistiques.index') }}">
                            <i class="la la-bar-chart"></i>
                            <span class="menu-title">Statistiques Avancées</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('graphes.flux-migratoire') ? 'active' : '' }}">
                        <a class="menu-item" href="{{ route("graphes.flux-migratoire") }}">
                            <i class="la la-line-chart"></i>
                            <span data-i18n="Vertical">Graphes flux migratoire</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <i class="la la-file-pdf-o"></i>
                            <span class="menu-title" data-i18n="Templates">Etats</span>
                        </a>
                        <ul class="menu-content">
                            <li class="{{ request()->routeIs('reporting.impetrant') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route("reporting.impetrant") }}">
                                    <i></i><span>Impétrant</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('reporting.employeur') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route("reporting.employeur") }}">
                                    <i></i><span>Employeur</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('reporting.flux.migratoire') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route("reporting.flux.migratoire") }}">
                                    <i></i><span>Flux migratoire</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('rapports.global.form') ? 'active' : '' }}">
                                <a class="menu-item" href="{{ route('rapports.global.form') }}">
                                    <i></i><span>Rapport Global</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Configuration --}}
            @can("system.view")
            <li class="nav-item {{ request()->routeIs('pays.*', 'departements.*', 'arrondissements.*', 'quartiers.*', 'frontieres.*', 'categorie.socio.*', 'employeur.*', 'motifs.contentieux.*') ? 'open' : '' }}">
                <a href="#">
                    <i class="la la-wrench"></i>
                    <span class="menu-title" data-i18n="Templates">Configuration</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('pays.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("pays.index") }}"><i></i><span>Les Pays</span></a></li>
                    <li class="{{ request()->routeIs('departements.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("departements.index") }}"><i></i><span>Les Départements</span></a></li>
                    <li class="{{ request()->routeIs('arrondissements.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("arrondissements.index") }}"><i></i><span>Les Arrondissements</span></a></li>
                    <li class="{{ request()->routeIs('quartiers.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("quartiers.index") }}"><i></i><span>Les Quartiers</span></a></li>
                    <li class="{{ request()->routeIs('frontieres.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("frontieres.index") }}"><i></i><span>Les Frontières</span></a></li>
                    <li class="{{ request()->routeIs('categorie.socio.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("categorie.socio.index") }}"><i></i><span>Les catégories socio-prof</span></a></li>
                    <li class="{{ request()->routeIs('employeur.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("employeur.index") }}"><i></i><span>Les employeurs</span></a></li>
                    <li class="{{ request()->routeIs('motifs.contentieux.*') ? 'active' : '' }}"><a class="menu-item" href="{{ route("motifs.contentieux.index") }}"><i></i><span>Les motifs de contentieux</span></a></li>
                </ul>
            </li>
            @endcan

        </ul>
    </div>
</div>
<!-- END: Main Menu-->