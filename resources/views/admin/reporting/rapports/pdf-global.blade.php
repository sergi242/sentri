<page backimg="{{public_path('img/stransmis/stransmis.png')}}" backimgx="center" backimgy="middle" backimgw="100%">

    <!-- En-tête officiel avec Times -->
    <table style="margin-left:06mm; margin-right:06mm; font-family: Times;">
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
                                                <td style="text-align: center; font-size: 14px;">
                                                    MINISTERE DE L'INTERIEUR<br>
                                                    ET DE LA DECENTRALISATION<br>
                                                    ----------------------
                                                </td>
                                                <td style="text-align: center; width: 500px; font-size: 14px;">
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
                                                <td style="text-align: center; font-size: 14px;">
                                                    <strong>DEPARTEMENT DES MIGRATIONS ET DU <br>CONTROLE DES ETRANGERS</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center; font-size: 14px;">----------------------</td>
                                                <td style="font-size: 14px;">
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

    <!-- Contenu du rapport -->
    <div style="font-family: Arial, sans-serif; font-size: 11px; margin-left:06mm; margin-right:06mm; margin-top:10px;">

        <!-- TITRE -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
            <tr>
                <td style="background-color:#1a3a6b; color:white; text-align:center; padding:8px; font-size:14px; font-weight:bold;">
                    RAPPORT GLOBAL D'ACTIVITÉS
                </td>
            </tr>
            <tr>
                <td style="background-color:#2c5aa0; color:#ffd700; text-align:center; padding:5px; font-size:11px; font-weight:bold;">
                    Période : {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                    ({{ \Carbon\Carbon::parse($dateDebut)->diffInDays(\Carbon\Carbon::parse($dateFin)) + 1 }} jours)
                </td>
            </tr>
        </table>

        @if($commentaire)
        <!-- COMMENTAIRE -->
        <div style="background:#fff3cd; border-left:4px solid #ffc107; padding:10px; margin-bottom:10px; font-size:10px;">
            <strong>Observations :</strong><br>
            {{ $commentaire }}
        </div>
        @endif

        <!-- STATISTIQUES GLOBALES -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
            <!-- Barre bleue prolongée -->
            <tr>
                <td colspan="2" style="background:#2c5aa0; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #1a3a6b;">
                    Vue d'ensemble
                </td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8faff; width:70%;">Total des demandes</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; font-size:13px; color:#1a3a6b;">{{ $totalDemandes }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fff8f0;">Demandes en contentieux</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; font-size:13px; color:#c0392b;">{{ $totalContentieux }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f0fff4;">Soit-Transmis créés</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; font-size:13px; color:#16a085;">{{ $totalSoitTransmis }}</td>
            </tr>
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8f0ff;">Demandes dans les soit-transmis</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; font-size:13px; color:#7b3fa0;">{{ $totalDemandesDansST }}</td>
            </tr>
        </table>

        <!-- DEMANDES PAR STATUT -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px; font-size:10px;">
            <!-- Barre bleue prolongée -->
            <tr>
                <td colspan="3" style="background:#2c5aa0; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #1a3a6b;">
                    Répartition des demandes par statut
                </td>
            </tr>
            <tr style="background:#3d5a80; color:white;">
                <th style="padding:5px 10px; border:1px solid #2c5aa0; text-align:left;">Statut</th>
                <th style="padding:5px 10px; border:1px solid #2c5aa0; text-align:center; width:15%;">Nombre</th>
                <th style="padding:5px 10px; border:1px solid #2c5aa0; text-align:center; width:15%;">%</th>
            </tr>
            @forelse($demandesParStatut as $statut)
            @php
                $pct = $totalDemandes > 0 ? round(($statut->total / $totalDemandes) * 100) : 0;
                $color = match(true) {
                    str_contains($statut->statut_demande, 'Approuvée') => '#1e6b3a',
                    str_contains($statut->statut_demande, 'contentieux') => '#c0392b',
                    str_contains($statut->statut_demande, 'attente') => '#e67e22',
                    default => '#555'
                };
            @endphp
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fafafa;">
                    <span style="color:{{ $color }}; font-weight:bold;">● {{ $statut->statut_demande }}</span>
                </td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold;">{{ $statut->total }}</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center;">{{ $pct }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="padding:10px; text-align:center; color:#999;">Aucune donnée</td>
            </tr>
            @endforelse
        </table>

        <!-- DEMANDES PAR TYPE -->
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px; font-size:10px;">
            <!-- Barre bleue prolongée -->
            <tr>
                <td colspan="2" style="background:#2c5aa0; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #1a3a6b;">
                    Répartition par type de demande
                </td>
            </tr>
            @forelse($demandesParType as $type)
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f8faff;">{{ $type->type_demande }}</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; width:20%;">{{ $type->total }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding:10px; text-align:center; color:#999;">Aucune donnée</td>
            </tr>
            @endforelse
        </table>

        <!-- CONTENTIEUX PAR MOTIF -->
        @if($contentieuxParMotif->count() > 0)
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px; font-size:10px;">
            <!-- Barre rouge prolongée -->
            <tr>
                <td colspan="2" style="background:#c0392b; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #a93226;">
                    Contentieux par motif
                </td>
            </tr>
            @foreach($contentieuxParMotif as $item)
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#fff0f0;">{{ $item['motif'] }}</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#c0392b; width:20%;">{{ $item['total'] }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        <!-- TOP 5 AGENTS -->
        @if($topAgents->count() > 0)
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px; font-size:10px;">
            <!-- Barre verte prolongée -->
            <tr>
                <td colspan="2" style="background:#16a085; color:white; padding:6px 10px; font-weight:bold; border-bottom:3px solid #138d75;">
                    Top 5 des agents les plus actifs
                </td>
            </tr>
            @foreach($topAgents as $agent)
            <tr>
                <td style="padding:5px 10px; border:1px solid #ddd; background:#f0fff4;">{{ $agent->getNomPrenom() }}</td>
                <td style="padding:5px 10px; border:1px solid #ddd; text-align:center; font-weight:bold; color:#16a085; width:20%;">{{ $agent->demandes_count }} demandes</td>
            </tr>
            @endforeach
        </table>
        @endif

        <!-- SIGNATURE -->
        <div style="margin-top:40px; margin-bottom:80px; text-align:right; font-size:12px; font-family: Times;">
            <u>{{ $signataire->grade->grade ?? '' }} <strong>{{ $signataire->getNomPrenom() }}</strong></u>
        </div>

        <!-- PIED DE PAGE -->
        <div style="border-top:1px solid #c5d5ea; margin-top:10px; padding-top:5px; text-align:center; font-size:9px; color:#999;">
            Document généré le {{ now()->format('d/m/Y à H:i') }} — DMCE
        </div>

    </div>

</page>