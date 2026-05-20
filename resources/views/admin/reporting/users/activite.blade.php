<page
    backimg="img/stransmis/stransmis.png"
    backimgopacity="0.5"
    backimgw="580"
>

    <!-- En-tête principal -->
    <table style="margin-left:06mm; margin-right:06mm; font-family:Times;">
        <tbody>
            <tr>
                <td style="text-align: center;">
                    <table style="margin-top: 7%">
                        <tbody>
                            <tr>
                                <td style="text-align: center;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center; font-size:14px;">
                                                    MINISTERE DE L'INTERIEUR<br>
                                                    ET DE LA DECENTRALISATION<br>
                                                    ----------------------
                                                </td>
                                                <td style="text-align: center; width: 500px; font-size:14px;">
                                                    REPUBLIQUE DU CONGO <br>
                                                    <strong>Unité – Travail - Progrès</strong> <br>
                                                    ----------------------
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">
                                                    CENTRALE D'INTELLIGENCE<br>
                                                    ET DE LA DOCUMENTATION
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">----------------------</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center; font-size:14px;">
                                                    <strong>DEPARTEMENT DES MIGRATIONS ET DU <br>CONTROLE DES ETRANGERS</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center; font-size:14px;">----------------------</td>
                                                <td style="font-size:14px;">
                                                    Brazzaville, le <strong style="color: #FF0000">{{ now()->format('d/m/Y') }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Contenu principal -->
    <div style="font-family: Arial, sans-serif; font-size: 11px; margin-top: 8px; margin-left:06mm; margin-right:06mm;">

        <!-- TITRE DU RAPPORT -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td style="background-color:#1a3a6b; color:white; text-align:center; padding:7px; font-size:14px; font-weight:bold; letter-spacing:1px;">
                    RAPPORT D'ACTIVITÉ INDIVIDUEL
                </td>
            </tr>
            <tr>
                <td style="background-color:#2c5aa0; color:#ffd700; text-align:center; padding:5px; font-size:11px; font-weight:bold;">
                    Période :
                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                    &nbsp;→&nbsp;
                    {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} jour(s)
                </td>
            </tr>
        </table>

        <!-- INFORMATIONS AGENT -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td colspan="2" style="background:#2c5aa0; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #1a3a6b;">
                    📋 Informations de l'Agent
                </td>
            </tr>
            <tr>
                <td style="width:68%; vertical-align:top; padding-right:10px; padding-top:6px;">
                    <table style="width:100%; border-collapse:collapse; font-size:11px;">
                        <tr>
                            <th style="background:#eaf0fb; color:#1a3a6b; padding:5px 10px; border:1px solid #c5d5ea; width:40%; text-align:left;">Nom Complet</th>
                            <td style="padding:5px 10px; border:1px solid #c5d5ea;"><strong>{{ $user->getNomPrenom() }}</strong></td>
                        </tr>
                        <tr>
                            <th style="background:#eaf0fb; color:#1a3a6b; padding:5px 10px; border:1px solid #c5d5ea; text-align:left;">Rôle</th>
                            <td style="padding:5px 10px; border:1px solid #c5d5ea;">{{ $user->role?->lib_role ?? 'Non défini' }}</td>
                        </tr>
                        <tr>
                            <th style="background:#eaf0fb; color:#1a3a6b; padding:5px 10px; border:1px solid #c5d5ea; text-align:left;">Grade</th>
                            <td style="padding:5px 10px; border:1px solid #c5d5ea;">{{ $user->grade?->grade ?? 'Non défini' }}</td>
                        </tr>
                        @if($user->email)
                        <tr>
                            <th style="background:#eaf0fb; color:#1a3a6b; padding:5px 10px; border:1px solid #c5d5ea; text-align:left;">Email</th>
                            <td style="padding:5px 10px; border:1px solid #c5d5ea;">{{ $user->email }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th style="background:#eaf0fb; color:#1a3a6b; padding:5px 10px; border:1px solid #c5d5ea; text-align:left;">Statut</th>
                            <td style="padding:5px 10px; border:1px solid #c5d5ea;">
                                @if($user->active)
                                    <span style="color:#1e6b3a; font-weight:bold;">● ACTIF</span>
                                @else
                                    <span style="color:#c0392b; font-weight:bold;">● INACTIF</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th style="background:#eaf0fb; color:#1a3a6b; padding:5px 10px; border:1px solid #c5d5ea; text-align:left;">Membre depuis</th>
                            <td style="padding:5px 10px; border:1px solid #c5d5ea;">{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:32%; vertical-align:top; text-align:center; padding-top:6px;">
                    <table style="margin-top:0; margin-left: 111px; margin-right:auto; border-collapse:collapse;">
                        <tr>
                            <td style="width:110px; height:140px; border:2px solid #2c5aa0; text-align:center; vertical-align:middle; background-color:#f0f4f8;">
                                @if($user->photo && file_exists(public_path('uploads/users/'.$user->photo)))
                                    <img src="{{ public_path('uploads/users/'.$user->photo) }}" style="width:110px; height:140px;">
                                @else
                                    <span style="color:#aaa; font-size:10px;">Pas de photo</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @php
            $totalDemandes    = $user->demandes->count();
            $totalApprouvees  = $user->demandes->where('statut_demande', 'Approuvée')->count();
            $totalContentieux = $user->demandes->where('statut_demande', 'Envoyée au contentieux')->count();
            $totalAttente     = $user->demandes->where("statut_demande", "En attente d'approbation")->count();
            $totalAttribuees  = $user->dossiers_attribues_count ?? 0;
            $totalST          = $user->soitTransmis->count();
            $totalFlux        = $user->fluxMigratoires->count();
            $totalEntrees     = $user->fluxMigratoires->sum('total_entree');
            $totalSorties     = $user->fluxMigratoires->sum('total_sortie');
            $totalCRT         = $user->demandes->where('type_demande', 'Carte de résident temporaire')->count();
            $totalVisa        = $user->demandes->where('type_demande', 'Visa')->count();
        @endphp

        <!-- STATISTIQUES GLOBALES -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:5px; font-size:11px;">
            <tr>
                <th colspan="2" style="background:#2c5aa0; color:white; padding:6px 10px; text-align:left; font-size:12px; border-bottom:3px solid #1a3a6b;">📊 Statistiques Globales</th>
            </tr>
            <tr>
                <th style="background:#3d5a80; color:white; padding:5px 10px; border:1px solid #2c5aa0; text-align:left;">Indicateur</th>
                <th style="background:#3d5a80; color:white; padding:5px 10px; border:1px solid #2c5aa0; text-align:center; width:15%;">Valeur</th>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8faff;">Demandes créées (total)</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#1a3a6b; background:white; font-size:12px;">{{ $totalDemandes }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f0fff4;">&#x2713; Demandes approuvées</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#1e6b3a; background:white; font-size:12px;">{{ $totalApprouvees }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fff8f0;">En attente d'approbation</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#e67e22; background:white; font-size:12px;">{{ $totalAttente }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fff0f0;">Envoyées au contentieux</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#c0392b; background:white; font-size:12px;">{{ $totalContentieux }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8f0ff;">Dossiers attribués</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#7b3fa0; background:white; font-size:12px;">{{ $totalAttribuees }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8faff;">CRT</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#2980b9; background:white; font-size:12px;">{{ $totalCRT }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8faff;">Visa</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#8e44ad; background:white; font-size:12px;">{{ $totalVisa }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fff8f0;">Soit-Transmis créés</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#e67e22; background:white; font-size:12px;">{{ $totalST }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f0fff4;">Flux migratoires</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#16a085; background:white; font-size:12px;">{{ $totalFlux }}</td>
            </tr>
            @if($totalFlux > 0)
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f0fff4; padding-left:25px;">↳ Entrées</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#1e6b3a; background:white; font-size:12px;">{{ number_format($totalEntrees, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fff0f0; padding-left:25px;">↳ Sorties</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#c0392b; background:white; font-size:12px;">{{ number_format($totalSorties, 0, ',', ' ') }}</td>
            </tr>
            @endif
        </table>

        <!-- RÉPARTITION PAR STATUT -->
        <table style="width:100%; border-collapse:collapse; font-size:11px; margin-bottom:5px;">
            <tr>
                <th colspan="3" style="background:#2c5aa0; color:white; padding:6px 10px; text-align:left; font-size:12px; border-bottom:3px solid #1a3a6b;">📈 Répartition par Statut</th>
            </tr>
            @if($user->demandes->isEmpty())
            <tr>
                <td colspan="3" style="text-align:center; font-size:11px; color:#888; padding:10px;">Aucune demande créée.</td>
            </tr>
            @else
            <tr>
                <th style="background:#3d5a80; color:white; padding:5px 10px; border:1px solid #2c5aa0; text-align:left; width:60%;">Statut</th>
                <th style="background:#3d5a80; color:white; padding:5px 10px; border:1px solid #2c5aa0; text-align:center; width:20%;">Nombre</th>
                <th style="background:#3d5a80; color:white; padding:5px 10px; border:1px solid #2c5aa0; text-align:center; width:20%;">%</th>
            </tr>
            @foreach($user->demandes->groupBy('statut_demande') as $statut => $items)
            @php
                $pct = $totalDemandes > 0 ? round($items->count() / $totalDemandes * 100) : 0;
                $color = match(true) {
                    str_contains($statut, 'Approuvée')   => '#1e6b3a',
                    str_contains($statut, 'contentieux') => '#c0392b',
                    str_contains($statut, 'attente')     => '#e67e22',
                    str_contains($statut, 'attribu')     => '#7b3fa0',
                    default                              => '#555'
                };
            @endphp
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fafafa;">
                    <span style="color:{{ $color }}; font-weight:bold;">● {{ $statut ?: 'Non défini' }}</span>
                </td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; background:white; font-size:12px;">{{ $items->count() }}</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; background:white; font-size:12px;">{{ $pct }}%</td>
            </tr>
            @endforeach
            @endif
        </table>

        <!-- ✅ ANALYSE ET COMMENTAIRES (NOUVELLE PAGE) -->
        @if(isset($analysis) && $analysis)
        </div>
        </page>

        <!-- NOUVELLE PAGE POUR L'ANALYSE -->
        <page
            backimg="img/stransmis/stransmis.png"
            backimgopacity="0.5"
            backimgw="580"
        >
        <div style="font-family: Arial, sans-serif; font-size: 11px; margin-top: 20mm; margin-left:06mm; margin-right:06mm;">

        <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
            <tr>
                <td colspan="1" style="background:#2c5aa0; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #1a3a6b;">
                    📊 Analyse de Performance et Tendances
                </td>
            </tr>
            <tr>
                <td style="padding:12px 15px; border:1px solid #ddd; background:#f8faff; font-size:10px; line-height:1.8; text-align:justify;">
                    {!! nl2br(e($analysis)) !!}
                </td>
            </tr>
        </table>

        <!-- TABLEAU DES VARIATIONS -->
        @if(isset($variations) && $variations)
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px; font-size:10px;">
            <tr>
                <td colspan="5" style="background:#2c5aa0; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #1a3a6b;">
                    📈 Comparaison avec la Période Précédente
                </td>
            </tr>
            <tr>
                <th style="background:#3d5a80; color:white; padding:5px 8px; border:1px solid #2c5aa0; text-align:left;">Indicateur</th>
                <th style="background:#3d5a80; color:white; padding:5px 8px; border:1px solid #2c5aa0; text-align:center; width:15%;">Actuelle</th>
                <th style="background:#3d5a80; color:white; padding:5px 8px; border:1px solid #2c5aa0; text-align:center; width:15%;">Précédente</th>
                <th style="background:#3d5a80; color:white; padding:5px 8px; border:1px solid #2c5aa0; text-align:center; width:12%;">Écart</th>
                <th style="background:#3d5a80; color:white; padding:5px 8px; border:1px solid #2c5aa0; text-align:center; width:12%;">Tendance</th>
            </tr>
            @foreach($variations as $label => $data)
            @php
                $labelText = match($label) {
                    'demandes' => 'Demandes créées',
                    'approuvees' => 'Demandes approuvées',
                    'contentieux' => 'Dossiers contentieux',
                    'attente' => 'Dossiers en attente',
                    'soit_transmis' => 'Soit-Transmis',
                    default => $label
                };
                $trendColor = match($data['trend']) {
                    'hausse' => '#1e6b3a',
                    'baisse' => '#c0392b',
                    default => '#555'
                };
                $trendIcon = match($data['trend']) {
                    'hausse' => '↗',
                    'baisse' => '↘',
                    default => '→'
                };
            @endphp
            <tr>
                <td style="padding:5px 8px; border:1px solid #ddd; background:#fafafa;">{{ $labelText }}</td>
                <td style="padding:5px 8px; border:1px solid #ddd; text-align:center; font-weight:bold;">{{ $data['current'] }}</td>
                <td style="padding:5px 8px; border:1px solid #ddd; text-align:center;">{{ $data['previous'] }}</td>
                <td style="padding:5px 8px; border:1px solid #ddd; text-align:center; font-weight:bold; color:{{ $trendColor }};">
                    {{ $data['diff'] > 0 ? '+' : '' }}{{ $data['diff'] }}
                </td>
                <td style="padding:5px 8px; border:1px solid #ddd; text-align:center; font-weight:bold; color:{{ $trendColor }};">
                    {{ $trendIcon }} {{ $data['pct'] }}%
                </td>
            </tr>
            @endforeach
        </table>
        @endif

        <!-- SIGNATURE SUR PAGE ANALYSE -->
        <div style="margin-top:400px; margin-bottom:100px; text-align:right; padding-right:20px; font-size:12px; font-family:Times;">
            <u>Lieutenant de Police <strong>ONDELE Sergi Prince</strong></u>
        </div>

        <!-- PIED DE PAGE -->
        <div style="border-top:1px solid #c5d5ea; margin-top:10px; padding-top:5px; text-align:center; font-size:9px; color:#999;">
            Document généré le {{ now()->format('d/m/Y à H:i') }} — DMCE
        </div>

        </div>
        </page>

        <page
            backimg="img/stransmis/stransmis.png"
            backimgopacity="0.5"
            backimgw="580"
        >
        <div style="font-family: Arial, sans-serif; font-size: 11px; margin-top: 20mm; margin-left:06mm; margin-right:06mm;">
        @endif

    </div>

</page>