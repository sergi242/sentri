header nav

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item mr-auto"><a class="navbar-brand" href="">
                        {{-- <img class="brand-logo" alt="modern admin logo" src="{{asset('res/images/growin.svg')}}" width="200" height="90" > --}}
                            <h3 class="brand-text">DMCE</h3>
                        </a></li>
                    <li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
                    <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        {{-- <h2><strong>DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</strong></h2> --}}
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-devise text-bold-700">

                            @auth
                            {{auth()->user()->prenom}} {{auth()->user()->nom}} ({{ auth()->user()->role?->lib_role ?? ""}})
                        </span><span class="avatar avatar-online"><img src="{{ asset("img/grades/".auth()?->user()?->grade?->grade.'.png') }}" alt="avatar"><i></i></span></a>
                            @endauth


                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="">
                                    <i class="ft-user"></i> Mon profil</a>
                                    <a class="dropdown-item" href="{{ route("users.changepassword")  }}"><i class="ft-check-square">
                                        </i> Modifier mot de passe</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item f-logout" href="">
                                    <i class="ft-power"></i> Déconnexion</a>
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
                <li class=" nav-item"><a href="{{route('users.home')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="Dashboard">Acceuil</span></a>
                </li>
                @can("dashboard.view")
                <li class=" nav-item"><a href="{{route('users.dashboard')}}"><i class="la la-dashboard"></i><span class="menu-title" data-i18n="Dashboard">Tableau de bord</span></a>
                </li>
                @endcan

                @can("users.view")
                <li class="nav-item">
                    <a href="#"><i class="la la-users"></i><span class="menu-title" data-i18n="Templates">Utilisateurs</span></a>
                    <ul class="menu-content">
                        @can("users.view")
                        <li>
                            <a class="menu-item" href="{{ route('users.index') }}">
                                <i class="la la-list"></i><span data-i18n="Vertical">Liste des Utilisateurs</span>
                            </a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('users.activites') }}">
                                <i class="la la-bar-chart"></i><span data-i18n="Vertical">Activité des Utilisateurs</span>
                            </a>
                        </li>
                        @endcan
                        @can("roles.view")
                        <li>
                            <a class="menu-item" href="{{ route('role.index') }}">
                                <i class="la la-briefcase"></i><span data-i18n="Vertical">Rôles</span>
                            </a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('grade.index') }}">
                                <i class="la la-certificate"></i><span data-i18n="Vertical">Grades</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can("demandes.view.all")
                <li class=" nav-item"><a href="#"><i class="la la-bus"></i><span class="menu-title" data-i18n="Templates">Contrôle des étrangers</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route("demandes.proche.expiration")}}"><i></i><span data-i18n="Vertical">Expiration dans 3 mois</span></a></li>
                    </ul>
                </li>
                @endcan
                
                @can("demandes.view.approved")
                <li class=" nav-item"><a href="#"><i class="la la-check-square"></i><span class="menu-title" data-i18n="Templates">Approbations</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route("demandes.attentes")}}"><i></i><span data-i18n="Vertical">Demandes en attente</span></a></li>
                        <li><a class="menu-item" href="{{route("demandes.approuvees")}}"><i></i><span data-i18n="Vertical">Demandes approuvées</span></a></li>
                        <li><a class="menu-item" href="{{route("demandes.contentieux")}}"><i></i><span data-i18n="Vertical">Demandes aux contentieux</span></a>
                        <li><a class="menu-item" href="{{route("demandes.impressioncartes")}}"><i></i><span data-i18n="Vertical">Impression cartes</span></a></li>
                    </ul>
                </li>

                <li class=" nav-item"><a href="#"><i class="la la-folder-open"></i><span class="menu-title" data-i18n="Templates">Soit-Transmis</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route("soit-transmis.index")}}"><i></i><span data-i18n="Vertical">Les Soit-Transmis</span></a></li>
                    </ul>
                </li>

                @endcan
                @can("demandes.print.cards")
                <li class=" nav-item"><a href="#"><i class="la la-credit-card"></i><span class="menu-title" data-i18n="Templates">Impression cartes</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route("demandes.impressioncartes")}}"><i></i><span data-i18n="Vertical">Demandes à imprimer</span></a></li>
                    </ul>
                </li>
                @endcan
                @can("demandes.view.attribue")
                <li class=" nav-item"><a href="#"><i class="la la-server"></i><span class="menu-title" data-i18n="Templates">Attributions</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route("demandes.attente.attributions")}}"><i></i><span data-i18n="Vertical">En attente d'attribution</span></a></li>
                        <li><a class="menu-item" href="{{route("demandes.attribuees")}}"><i></i><span data-i18n="Vertical">Demandes attribuées</span></a></li>
                    </ul>
                </li>
                @endcan


                @can("demandes.view.all")
                <li class=" nav-item"><a href="#"><i class="la la-folder"></i><span class="menu-title" data-i18n="Templates">Dossiers des demandes</span></a>
                    <ul class="menu-content">
                        @can("demandes.create")
                        <li><a class="menu-item" href="{{route("demandes.newdocument")}}"><i></i><span data-i18n="Vertical">Nouvelle demande</span></a></li>
                        @endcan
                        @can("demandes.renew")
                        <li><a class="menu-item" href="{{route("demandes.renouvellement")}}"><i></i><span data-i18n="Vertical">Renouveler demande</span></a></li>
                        @endcan
                        @can("demandes.view.all")
                        <li><a class="menu-item" href="{{route("demandes.index")}}"><i></i><span data-i18n="Vertical">Toutes les demandes</span></a></li>
                        @endcan
                        @can("demandes.view.contentieux")
                        <li><a class="menu-item" href="{{route("demandes.contentieux")}}"><i></i><span data-i18n="Vertical">Demandes au contentieux</span></a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can("flux.view")
                <li class=" nav-item"><a href="#"><i class="la la-trello"></i><span class="menu-title" data-i18n="Templates">Flux migratoire</span></a>
                    <ul class="menu-content">
                        @can("flux.create")
                        <li><a class="menu-item" href="{{route("flux.create")}}"><i></i><span data-i18n="Vertical">Ajouter des donnée</span></a></li>
                        @endcan
                        <li><a class="menu-item" href="{{route("flux.index")}}"><i></i><span data-i18n="Vertical">Toutes les données</span></a></li>
                    </ul>
                </li>
                @endcan
            @can("flux.view")
            <li class=" nav-item"><a href="#"><i class="la la-pie-chart"></i><span class="menu-title" data-i18n="Templates">Statistiques générales</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{ route("graphes.flux-demande") }}"><i></i><span data-i18n="Vertical">Graphes sur les demandes</span></a></li>
                    <li><a class="menu-item" href="{{ route("graphes.flux-migratoire") }}"><i></i><span data-i18n="Vertical">Graphes flux migratoire</span></a></li>
                    <li class=" nav-item"><a href="#"><i class="la la-file"></i><span class="menu-title" data-i18n="Templates">Etats</span></a>
                        <ul class="menu-content">
                            <li><a class="menu-item" href="{{ route("reporting.impetrant") }}"><i></i><span data-i18n="Vertical">Impetrant</span></a></li>
                            <li><a class="menu-item" href="{{ route("reporting.employeur") }}"><i></i><span data-i18n="Vertical">Employeur</span></a></li>
                            {{-- <li><a class="menu-item" href="{{ route("flux.stats.etat") }}"><i></i><span data-i18n="Vertical">Flux migratoire</span></a></li> --}}
                            <li><a class="menu-item" href="{{ route("reporting.flux.migratoire") }}"><i></i><span data-i18n="Vertical">Flux migratoire</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endcan
            @can("system.view")
            <li class=" nav-item"><a href="#"><i class="la la-wrench"></i><span class="menu-title" data-i18n="Templates">Configuration</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{ route("pays.index") }}"><i></i><span data-i18n="Vertical">Les Pays</span></a></li>
                    <li><a class="menu-item" href="{{ route("departements.index") }}"><i></i><span data-i18n="Vertical">Les Departements</span></a></li>
                    <li><a class="menu-item" href="{{ route("arrondissements.index") }}"><i></i><span data-i18n="Vertical">Les Arrondissements</span></a></li>
                    <li><a class="menu-item" href="{{ route("quartiers.index") }}"><i></i><span data-i18n="Vertical">Les Quartiers</span></a></li>
                    <li><a class="menu-item" href="{{ route("frontieres.index") }}"><i></i><span data-i18n="Vertical">Les Frontières</span></a></li>
                    <li><a class="menu-item" href="{{ route("categorie.socio.index") }}"><i></i><span data-i18n="Vertical">Les catégories socio-prof</span></a></li>
                    <li><a class="menu-item" href="{{ route("employeur.index") }}"><i></i><span data-i18n="Vertical">Les employeurs</span></a></li>
                    <li><a class="menu-item" href="{{ route("motifs.contentieux.index") }}"><i></i><span data-i18n="Vertical">Les motifs de contentieux</span></a></li>
                </ul>
            </li>
            @endcan
            {{-- @can("system.view")
            <li class=" nav-item"><a href="#"><i class="la la-list"></i><span class="menu-title" data-i18n="Templates">Liste alerte</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{ route("liste-alerte.index") }}"><i></i><span data-i18n="Vertical">Liste</span></a></li>
                    <li><a class="menu-item" href="{{ route("liste-alerte.create") }}"><i></i><span data-i18n="Vertical">Ajouter</span></a></li>
                </ul>
            </li>
            @endcan --}}
            </ul>
        </div>
    </div>

    <!-- END: Main Menu-->
