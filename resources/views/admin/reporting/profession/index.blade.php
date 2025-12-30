<!DOCTYPE html>
<html>
<head>
	<title>Exemple de texte sur les extrémités</title>
</head>
<body>
  <div class="left">
    MINISTERE DE L’INTERIEUR DE LA
    DECENTRALISATION ET DU DEVELOPPEMENT LOCAL
    <br style="white-space: nowrap;" />
    CENTRALE D’INTELIGENCE ET DE
    DOCUMMENTATION
    <br style="white-space: nowrap;" />
    DEPARTEMENT DES MIGRATIONS
    ET DU CONTROLE DES ETRANGERS
  </div>
  
  <div class="center">
    <img src="{{url('website/assets/img/congo.png')}}" id="carte" alt="">
  </div>
  
  <div class="right" style="text-align: center;">
    REPUBLIQUE DU CONGO <br style="white-space: nowrap;" /> Unité*travail*progrès
  </div>

  <div class="titre">
    Statistiques Profession - {{$profession}}
  </div>

  <table>
		<thead>
			<tr>
				<th>Nationalite</th>
				<th>Homme</th>
				<th>Femme</th>
				<th>TOTAL</th>
			</tr>
		</thead>
		<tbody>
			<!-- Le contenu des cellules sera généré avec une boucle PHP -->
      @foreach ($resultats as $resultat)
      <tr>
          <td style="word-wrap: break-word">{{$resultat->pays}}</td>
          <td>{{$resultat->homme}}</td>
          <td>{{$resultat->femme}}</td>
          <td>{{$resultat->total}}</td>
      </tr>
      @endforeach
      <tr id="total_cel" style="background-color: #333; color: white;">
        <td style="word-wrap: break-word"><strong>TOTAL </strong></td>
        <td><strong>{{$somme['masculin']}}</strong></td>
        <td><strong>{{$somme['feminin']}}</strong></td>
        <td><strong>{{$somme['total_par_nationalite']}}</strong></td>
      </tr>
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
