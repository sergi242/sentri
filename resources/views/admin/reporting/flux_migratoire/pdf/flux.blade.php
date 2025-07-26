
<page backimg="img/stransmis/stransmis.png" backimgopacity="0.5" backimgw="580">
    <page_header>
        <!-- Contenu de l'en-tête de page -->
    </page_header>
   <table>
    <tbody>
        <tr {{-- style="background-color: aqua; width: 500px;" --}}>
            <td class="center header_1"> 
                {{-- titre centré à droite --}}
                MINISTERE DE L'INTERIEUR ET DE La DECENTRALISATION
                <p class="no-margin">CENTRALE D'INTELLIGENCE ET DE DOCUMENTATION</p>
                <p class="no-margin">DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</p>
                <p class="no-margin">DIVISION DU CONTROLE DES ENTRANGERS</p>
                <p class="no-margin">SECTION DES FLUX MIGRATOIRES ET DES STATISTIQUES</p>
            </td>
            <td style="width: 65px">

            </td>
            <td class="right header_1">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 300px">
                            </td>
                            <td>
                                REPUBLIQUE DU CONGO
                                <p>Unité * Travail * Progrès</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr >
            <td class="center" colspan="3">
                <h4>
                    TABLEAU SYNOPTIQUE DES STATISTIQUES DES ETRANGERS
                    <p class="small-margin">DANS LES DIFFERENTS POSTES FRONTALIERS</p>
                </h4>
            </td>
        </tr>
        <tr>
            <td  class="center" colspan="3">
                <strong><small>PERIODE : {{ date("d/m/Y",strtotime($dtone)) }} à {{ date("d/m/Y",strtotime($dtwo)) }}</small></strong>
            </td>
        </tr>
    </tbody>
   </table>
<table id="table" class="inner-table">
    <thead>
        <tr>
            <th class="th" style="width: 10px">Nationalités</th>
            {{-- Ajout des frontieres --}}
            @foreach ($fronts as $f)
                <th class="center th" colspan="2">{{ $f->lib_frontiere }}</th>
            @endforeach
            <th class="center th">Total Entrée</th>
            <th class="center th">Total Sortie</th>
        </tr>
        <tr>
            <th class="th"></th>
            {{-- Ajout des colonnes pour Entrée et Sortie --}}
            @foreach ($fronts as $f)
                <th class="center th">E</th>
                <th class="center th">S</th>
            @endforeach
            <th class="center th"></th>
            <th class="center th"></th>
        </tr>
    </thead>
    <tbody>
        @php
    $totalEntrées = 0;
    $totalSorties = 0;

    // Initialisation des totaux pour chaque point frontalier
    $totals = [];
    foreach ($fronts as $frontiere) {
        $totals[$frontiere->id] = ['entree' => 0, 'sortie' => 0];
    }
@endphp
@forelse ($pays as $item)
    @php
        $totalEntréePays = 0;
        $totalSortiePays = 0;
    @endphp
    @foreach ($fronts as $frontiere)
        @php
            $entree = TechnoDev::joinFluxData($item->id, $frontiere->id, $dtone, $dtwo)->tentree ?? 0;
            $sortie = TechnoDev::joinFluxData($item->id, $frontiere->id, $dtone, $dtwo)->tsortie ?? 0;
            $totalEntréePays += $entree;
            $totalSortiePays += $sortie;
        @endphp
    @endforeach
    
    @if ($totalEntréePays > 0 || $totalSortiePays > 0)
        <tr>
            <td class="td">
                @php
                    if($item->lib_pays == "République démocratique du Congo" || $item->lib_pays == "Congo"){
                        echo "<strong> $item->lib_pays </strong>";
                    } else {
                        echo $item->lib_pays;
                    }
                @endphp
            </td>
            @foreach ($fronts as $frontiere)
                @php
                    $entree = TechnoDev::joinFluxData($item->id, $frontiere->id, $dtone, $dtwo)->tentree ?? 0;
                    $sortie = TechnoDev::joinFluxData($item->id, $frontiere->id, $dtone, $dtwo)->tsortie ?? 0;
                    $totalEntrées += $entree;
                    $totalSorties += $sortie;
                    // Mise à jour des totaux pour chaque point frontalier
                    $totals[$frontiere->id]['entree'] += $entree;
                    $totals[$frontiere->id]['sortie'] += $sortie;
                    if($entree == 0){
                        $entree = "";
                    }
                    if($sortie == 0){
                        $sortie = "";
                    }
                @endphp

                <td class="td">
                    @php
                        if($item->lib_pays == "République démocratique du Congo" || $item->lib_pays == "Congo"){
                            echo "<strong> $entree </strong>";
                        } else {
                            echo $entree;
                        }
                    @endphp
                </td>
                <td class="td">
                    @php
                        if($item->lib_pays == "République démocratique du Congo" || $item->lib_pays == "Congo"){
                            echo "<strong> $sortie </strong>";
                        } else {
                            echo $sortie;
                        }
                    @endphp
                </td>
            @endforeach
            <td class="td" style="background-color: #333; color: white;">{{ $totalEntréePays }}</td>
            <td class="td" style="background-color: #333; color: white;">{{ $totalSortiePays }}</td>
        </tr>
    @endif
@empty
    <tr style="background-color: #333; color: white;">
        <td class="td" colspan="{{ 2 * $fronts->count() + 2 }}">Aucune donnée disponible</td>
    </tr>
@endforelse
<tr style="background-color: #b6b6b6">
    <td class="td"><strong>TOTAL</strong></td>
    @foreach ($fronts as $frontiere)
        <td class="td"><strong>{{ $totals[$frontiere->id]['entree'] }} E</strong></td>
        <td class="td"><strong>{{ $totals[$frontiere->id]['sortie'] }} S</strong></td>
    @endforeach
    <td class="td"><strong>{{ $totalEntrées }}</strong></td>
    <td class="td"><strong>{{ $totalSorties }}</strong></td>
</tr>

    </tbody>
</table>

    <page_footer>
        <!-- Contenu du pied de page -->
    </page_footer>
</page>
<style>
    .center {
        text-align: center;
    }
    .right{
        text-align: right;
    }
    .header_1 {
        font-size: 12px;
        font-weight: bold;
        width: 500px;
    }
    .left-margin {
        margin-left: 6000px; /* Ajustez cette valeur selon vos besoins */
    }
    .no-margin {
        margin: 0;
    }
    .small-margin{
        margin: 2px;
    }
    table {
      border-collapse: collapse;
      margin: auto;
      width: 10%;
      font-size: 10pt;
      table-layout: fixed;
    }
    .th, .td {
      border: 1px solid black;
      padding: 10px;
      text-align: center;
      width: 25%;
    }
    th {
        background-color: #dddddd; /* Ajouter une couleur de fond à la première ligne (en-tête) */
    }
</style>