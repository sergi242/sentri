<!DOCTYPE html>
<html>
<head>
	<title>Exemple de texte sur les extrémités</title>
</head>
<body>
  <div class="left">
    MINISTERE DE L’INTERIEUR 
    ET DE LA DECENTRALISATION
    <br style="white-space: nowrap;" />
    CENTRALE D’INTELIGENCE ET DE
    DOCUMMENTATION
    <br style="white-space: nowrap;" />
    DEPARTEMENT DES MIGRATIONS
    ET DU CONTROLE DES ETRANGERS
  </div>

  <div class="center">
    <img src="{{url('img/congo.png')}}" id="carte" alt="">
  </div>

  <div class="right" style="text-align: center;">
    REPUBLIQUE DU CONGO <br style="white-space: nowrap;" /> Unité*travail*progrès
  </div>

  <div class="titre">
    {{-- {{ $nomDocument }} --}}
  </div>
    <table>
        <thead>
            <tr>
                <th>
                   @isset($demandes["mois"])
                     Mois
                   @endisset
                   @isset($demandes["jours"])
                     Jours
                   @endisset
                </th>
                <th>Total demandes</th>
                <th>Total approuvées</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sommesJoursDemandes = 0;
                $sommesJoursApprouvees = 0;
                $sommesMoisDemandes = 0;
                $sommesMoisApprouvees = 0;
            @endphp
            <!-- Le contenu des cellules sera généré avec une boucle PHP -->
    @isset($demandes["mois"])
        @php
            $mois = ["","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];
        @endphp
        @for ($i=1; $i < count($demandes["mois"]); $i++)
            <tr>
                <td style="word-wrap: break-word">{{$mois[$i]}}</td>
                <td>{{ $demandes["totals"][$i] }}</td>
                <td>{{ $demandes["approuvees"][$i] }}</td>
            </tr>
            @php
                $sommesMoisDemandes += intval($demandes["totals"][$i]);
                $sommesMoisApprouvees += intval($demandes["approuvees"][$i]);
            @endphp
        @endfor
    @endisset

    @isset($demandes["jours"])
        @for ($i=1; $i < count($demandes["jours"]); $i++)
            <tr>
                <td style="word-wrap: break-word">{{$demandes["jours"][$i]}}</td>
                <td>{{ $demandes["totals"][$i] }}</td>
                <td>{{ $demandes["approuvees"][$i] }}</td>
            </tr>
            @php
                $sommesJoursDemandes += intval($demandes["totals"][$i]);
                $sommesJoursApprouvees += intval($demandes["approuvees"][$i]);
            @endphp
        @endfor
    @endisset
    @isset($demandes["mois"])
    <tr id="total_cel" style="background-color: #333; color: white;">
        <td style="word-wrap: break-word"><strong>TOTAL </strong></td>
        <td><strong>{{$sommesMoisDemandes}}</strong></td>
        <td><strong>{{$sommesMoisApprouvees}}</strong></td>
    </tr>
    @endisset
    @isset($demandes["jours"])
    <tr id="total_cel" style="background-color: #333; color: white;">
        <td style="word-wrap: break-word"><strong>TOTAL </strong></td>
        <td><strong>{{$sommesJoursDemandes}}</strong></td>
        <td><strong>{{$sommesJoursApprouvees}}</strong></td>
    </tr>
    @endisset
        </tbody>
    </table>



  <style type="text/css">
    #total_cel{

    }
		.left {
      float: left;
      margin-left: 20px;
      font-size: 8pt; /* Définir la taille de la police */
      width: 30%; /* Définir la largeur de la div */
      text-align: center;
		}
		.right {
			position: absolute;
			right: 0;
			text-align: right;
      width: 40%; /* Définir la largeur de la div */
		}
    .titre{
      margin-top: 10px;
      text-align: center;
      text-decoration: underline;
      font-size: 15pt;
      padding: 5px;
    }


    table {
      border-collapse: collapse;
      margin: auto;
      width: 75%;
      font-size: 10pt;
      table-layout: fixed;
    }

    th, td {
      border: 1px solid black;
      padding: 10px;
      text-align: center;
      width: 25%;
    }
    th {
        background-color: #dddddd; /* Ajouter une couleur de fond à la première ligne (en-tête) */
    }
    .center {
      max-width: 20%;
      height: auto;
      text-align: center;
    }
	</style>
</body>
</html>
