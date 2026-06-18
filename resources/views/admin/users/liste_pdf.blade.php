<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Times New Roman, serif; font-size:11px; color:#222; }
.ht { width:100%; margin-bottom:10px; }
.cl { width:33%; font-size:9px; line-height:1.6; vertical-align:top; }
.cm { width:34%; text-align:center; vertical-align:top; }
.cr { width:33%; text-align:right; font-size:9px; line-height:1.6; vertical-align:top; }
.cm img { height:55px; }
.republic { text-align:center; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; margin-top:4px; }
.ministry  { text-align:center; font-size:9px; color:#555; margin-top:2px; }
hr.thick   { border:none; border-top:2px solid #1E9FF2; margin:8px 0; }
.doc-title    { text-align:center; font-size:14px; font-weight:bold; text-transform:uppercase; color:#1E9FF2; margin:10px 0 3px; letter-spacing:1px; }
.doc-subtitle { text-align:center; font-size:10px; color:#888; margin-bottom:10px; }
.meta-box { background:#f4f7fb; border-left:4px solid #1E9FF2; padding:6px 10px; font-size:10px; margin-bottom:12px; }
.meta-box strong { font-weight:bold; }
table.t { width:100%; border-collapse:collapse; }
table.t thead tr { background:#1E9FF2; color:#fff; }
table.t thead th { padding:6px 8px; font-size:9px; text-transform:uppercase; letter-spacing:.4px; border:1px solid #1a8fd1; }
table.t tbody tr:nth-child(even) { background:#f0f7ff; }
table.t tbody td { padding:5px 8px; font-size:10px; border:1px solid #e0e0e0; vertical-align:middle; }
.badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:bold; }
.actif   { background:#28D094; color:#fff; }
.inactif { background:#FF4961; color:#fff; }
.sig-block { margin-top:25px; text-align:right; font-size:10px; }
.footer    { margin-top:12px; text-align:center; font-size:8px; color:#aaa; }
.no-data   { text-align:center; color:#aaa; padding:25px; font-style:italic; }
</style>
</head>
<body>
<table class="ht">
<tr>
    <td class="cl">REPUBLIQUE DU CONGO<br><strong>Ministere de l'Interieur</strong><br>Direction Generale des Services<br>Dept. des Migrations et du Controle<br>des Etrangers (DMCE)</td>
    <td class="cm"><img src="{{ public_path('img/congo.png') }}" alt=""></td>
    <td class="cr">Brazzaville, le {{ \Carbon\Carbon::now()->setTimezone('Africa/Brazzaville')->isoFormat('D MMMM YYYY') }}<br><strong>Document interne</strong><br>Ref. DMCE / RH</td>
</tr>
</table>
<div class="republic">Republique du Congo &mdash; Ministere de l'Interieur</div>
<div class="ministry">Departement des Migrations et du Controle des Etrangers (DMCE)</div>
<hr class="thick">
<div class="doc-title">Liste des agents</div>
<div class="doc-subtitle">Genere le {{ $dateGen }}</div>
<div class="meta-box">
    <strong>Selection :</strong> {{ $filtreLabel }} &nbsp;|&nbsp;
    <strong>Total :</strong> {{ $users->count() }} &nbsp;|&nbsp;
    <strong>Actifs :</strong> {{ $users->where('active',1)->count() }} &nbsp;|&nbsp;
    <strong>Inactifs :</strong> {{ $users->where('active',0)->count() }}
</div>
@if($users->isEmpty())
    <div class="no-data">Aucun agent ne correspond aux criteres selectionnes.</div>
@else
<table class="t">
    <thead><tr>
        <th style="width:22px">#</th>
        <th>Nom complet</th>
        <th>E-mail</th>
        <th>Grade</th>
        <th>Role</th>
        <th style="width:52px;text-align:center">Etat</th>
    </tr></thead>
    <tbody>
        @foreach($users as $i => $user)
        <tr>
            <td style="text-align:center;color:#999;font-size:9px">{{ $i + 1 }}</td>
            <td><strong>{{ strtoupper($user->nom) }} {{ $user->prenom }}</strong></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->grade?->grade ?? '&mdash;' }}</td>
            <td>{{ $user->role?->lib_role ?? '&mdash;' }}</td>
            <td style="text-align:center">
                <span class="badge {{ $user->active ? 'actif' : 'inactif' }}">{{ $user->active ? 'Actif' : 'Inactif' }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
<div class="sig-block"><div><strong>Le Responsable DMCE</strong></div><br><br><br><div>Lieutenant <strong>Sergi ONDELE</strong></div></div>
<div class="footer">Document strictement confidentiel &mdash; Usage interne DMCE uniquement</div>
</body>
</html>
