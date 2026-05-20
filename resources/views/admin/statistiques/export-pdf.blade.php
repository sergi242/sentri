<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistiques DMCE - {{ $periode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1a3a6b;
        }
        
        .header h1 {
            font-size: 20pt;
            color: #1a3a6b;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 14pt;
            color: #666;
            font-weight: normal;
        }
        
        .periode {
            text-align: center;
            font-size: 13pt;
            color: #1a3a6b;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .stat-card h3 {
            font-size: 10pt;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 24pt;
            font-weight: bold;
            color: #1a3a6b;
        }
        
        .section-title {
            font-size: 14pt;
            color: #1a3a6b;
            margin: 30px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #1a3a6b;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th {
            background: #1a3a6b;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10pt;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10pt;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            color: #999;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>RÉPUBLIQUE DU CONGO</h1>
        <h2>DÉPARTEMENT DES MIGRATIONS ET DU CONTRÔLE DES ÉTRANGERS</h2>
    </div>
    
    <div class="periode">
        Rapport Statistique - {{ $periode }}
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Demandes</h3>
            <div class="value">{{ $stats['demandes_total'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Approuvées</h3>
            <div class="value">{{ $stats['demandes_approuvees'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Flux Entrées</h3>
            <div class="value">{{ $stats['flux_entrees'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Flux Sorties</h3>
            <div class="value">{{ $stats['flux_sorties'] }}</div>
        </div>
    </div>
    
    <div class="section-title">Synthèse</div>
    
    <table>
        <tr>
            <th>Indicateur</th>
            <th style="text-align: right;">Valeur</th>
        </tr>
        <tr>
            <td>Total demandes du mois</td>
            <td style="text-align: right;"><strong>{{ $stats['demandes_total'] }}</strong></td>
        </tr>
        <tr>
            <td>Demandes approuvées</td>
            <td style="text-align: right;"><strong>{{ $stats['demandes_approuvees'] }}</strong></td>
        </tr>
        <tr>
            <td>Taux d'approbation</td>
            <td style="text-align: right;">
                <strong>
                    {{ $stats['demandes_total'] > 0 ? round(($stats['demandes_approuvees'] / $stats['demandes_total']) * 100, 1) : 0 }}%
                </strong>
            </td>
        </tr>
        <tr>
            <td>Total flux migratoires</td>
            <td style="text-align: right;"><strong>{{ $stats['flux_entrees'] + $stats['flux_sorties'] }}</strong></td>
        </tr>
        <tr>
            <td>Balance migratoire</td>
            <td style="text-align: right;"><strong>{{ $stats['flux_entrees'] - $stats['flux_sorties'] }}</strong></td>
        </tr>
    </table>
    
    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} - DMCE - Document confidentiel
    </div>
    
</body>
</html>
