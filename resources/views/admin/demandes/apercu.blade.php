<div id="cadre">
  <table cellspacing="10" id="nav_1" >
      <tr>
          <td style="width:5%;">
              <img class="armoiri" src="{{asset('img/armoi_.png')}}" id="carte" alt="">
          </td>
          <td style="width:25%;">
              <p>
                  <h2 id="quitance" class="nomarge" style="text-rendering: optimize-speed;">Q U I T A N C E</h2>
                  <h4 class="nomarge center">République du Congo</h4>
                  <h6 class="nomarge center" style="">Unité * Travail * Progrès</h6>
              </p>
          </td>

          <td style="width:5%;">
              <img class="armoiri_2" src="{{asset('img/congo_.png')}}" id="carte" alt="">
          </td>
          <td style="width:25%;">
              {{-- <img src="{{url('img/congo.svg')}}" id="carte" alt=""> --}}
              <div id="codebar_space">
                {{-- <barcode dimension="1D" type="EAN13" value="{{ str_replace("_","",$demande->uuid) }}" label="label" style="width:30mm; height:12mm; color: #000; font-size: 4mm"></barcode> --}}
              </div>
          </td>
      </tr>
  </table>
  <div style="margin: 2px; position: relative;top: 10px">
      <strong>DEMANDE DE DELIVRANCE DE CARTE DE RESIDENT</strong>
  </div>
  <div id="tete">
  <table cellspacing="3" id="nav_2" >
      <tr >
          <td style="width:3%;">
              <img class="armoiri" src="{{asset('img/armoi_2.png')}}" id="carte" alt="">
          </td>
          <td style="">
                  <h3 class="nomarge tb">DEMANDE DE DELIVRANCE</h3>
                  <h3 class="nomarge tb">DE CARTE DE RESIDENT</h3>
          </td>
          <td style="width:3%;  margin-left: 100%;">
              <img class="armoiri_2" src="{{asset('img/congo_2.png')}}" id="carte" alt="">
          </td>
          <td style="width:auto;" colspan="1">
              <h4 class="nomarge center tb">REPUBLIQUE DU CONGO</h4>
              <h6 class="nomarge center tb">Unité * Travail * Progrès</h6>
          </td>
      </tr>
      <tr>
          <td colspan="4">
              <span class="tb" style="font-size: 50%;">
                  MINISTERE DE L'INTERIEUR ET DE LA DECENTRALISATIONAL - CENTRALE D'INTELIGENCE ET DE DOCUMENTATION-DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS
              </span>
          </td>
      </tr>
  </table>
  </div>
  <table cellspacing="" >
      <tr>
          <td id="part_1" class="" style="width:295px;">
              <div id="codebar_space_2">
                {{-- <barcode dimension="1D" type="EAN13" value="{{ str_replace("_","",$demande->uuid) }}" label="label" style="width:30mm; height:12mm; color: #000; font-size: 4mm"></barcode> --}}
              </div>
              <div style="text-align: center">
                  <strong>Partie réservée à l'administrateur</strong>
              </div>
              <div id="remplir_2">
                  <div id="administrateur_remplir">
                      <table id="administrateur">
                          <tbody>
                              <tr style="text-align: center;">
                                  <td colspan="" class="" style=" text-align: center; width:100%; ">
                                      -----------------Données Biometrique-----------------
                                  </td>
                              </tr>
                              <tr style="" style="text-align: center;">
                                  <td id="" style="">
                                      <img id="empreinte_photo" src="{{asset('img/empt_2.png')}}" alt="">
                                  </td>
                              </tr>
                              <tr>
                                  <td>
                                      -----------------Pieces justificatives-----------------
                                  </td>
                              </tr>
                              <tr>
                                  <td>
                                      <strong style="font-size: 82%;">N° de passeport : </strong>&nbsp;&nbsp;
                                      <div class="textgestion">{{$passeport->numero_document}}</div>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </td>
          <td id="part_2" class="" style="width:439px;">
              <div id="code_impression">
                  <span id="code_impression_texte">code d'impression</span>
              </div>
              <div id="remplir">
                  <div id="demandeur_remplir">
                      <table style="table-layout: auto;height: 200px">
                          <tbody>
                              <tr>
                                  <td><strong>Partie à remplir par le demandeur</strong></td>
                                  <td style="width: 200px;font-size: 70%;" >
                                      <strong>(respecter les miniscule, majuscule et les accents. Remplire une seule lettre par case)</strong>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="2">
                                      <hr>
                                  </td>
                              </tr>
                              <tr>
                                  <td style="width: 200px;font-size: 80%;" >
                                      Si vous possedez deja une carte de résidence, veuillez indiquer le N° de reference
                                  </td>
                                  <td style="width: 200px;font-size: 80%;">
                                      <strong> N° : </strong>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="2" class="center">---------------------------------Information Personelle---------------------------------</td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Nom : </strong>&nbsp;&nbsp;
                                      <div class="textgestion">{{$impetrant?->nom}}</div>
                                  </td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Prenom : </strong>&nbsp;&nbsp;
                                      <div class="textgestion">{{$impetrant?->prenom}}</div>
                                  </td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Nationalité : </strong>{{$impetrant?->pays?->lib_pays}}</td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Date de naissance : </strong>{{$impetrant?->date_naissance}}</td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Lieu de naissance : </strong>{{$impetrant?->lieu_naissance}}</td>
                              </tr>
                              <tr>
                                  <td style="font-size: 82%;">
                                      <strong>Sexe :</strong>
                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                      {{-- @if ($impetrant[0]->sexe == 'MASCULIN')
                                      Masculin <input type="checkbox" checked> | Feminin <input type="checkbox">
                                      @endif
                                      @if ($impetrant[0]->sexe == 'FEMININ')
                                      Masculin <input type="checkbox"> | Feminin <input type="checkbox" checked>
                                      @endif --}}
                                      @if ($impetrant?->sexe == 'Masculin')
                                      Masculin
                                      @endif
                                      @if ($impetrant?->sexe == 'Féminin')
                                      Feminin
                                      @endif

                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="2" class="center">-------------------------------Information sur les parents-------------------------------</td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Nom du père :&nbsp;</strong><div class="textgestion">{{$impetrant?->nom_pere}}</div></td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Prenom du père :&nbsp;</strong><div class="textgestion">{{$impetrant?->prenom_pere}}</div></td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Nom de la mère :&nbsp;</strong><div class="textgestion">{{$impetrant?->nom_mere}}</div></td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Prenom de la mère :&nbsp;</strong><div class="textgestion">{{$impetrant?->prenom_mere}}</div></td>
                              </tr>
                              <tr>
                                  <td colspan="2" class="center">------------------------------Information Complémentaire------------------------------</td>
                              </tr>
                              <tr>
                                  <td  colspan="2" style="font-size: 82%;"><strong>Etat Civil : </strong></td>
                              </tr>
                              <tr>
                                  <td  style="font-size: 82%;" >
                                    <strong>Adresse de résidence au Congo : </strong>
                                  </td>
                                  <td style="font-size: 82%;position : absolute; table-layout: auto; word-wrap: break-word;">

                                          <div class="textgestion">{{$demande->quartier?->arrondissement?->departement?->lib_departement}}</div>
                                          <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Ville</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td></td>
                                  <td style="font-size: 82%;">
                                      <div class="textgestion">{{$demande->quartier?->arrondissement?->lib_arrondissement}}</div>
                                      <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Arrondissement</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td></td>
                                  <td style="font-size: 82%;">
                                      <div class="textgestion">{{$demande->quartier?->lib_quartier}}</div>
                                      <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Quartier</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td  style="font-size: 82%;" >
                                    <strong>Numéro de telephone : </strong>
                                  </td>
                                  <td style="font-size: 82%;position : absolute; table-layout: auto; word-wrap: break-word;">
                                      <div class="textgestion"> {{$demande->telephone}} </div>
                                          <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Telephone 1</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td  style="" >
                                  </td>
                                  <td style="font-size: 82%; position : absolute; table-layout: auto; word-wrap: break-word;">
                                      <div class="textgestion"> {{$demande->telephone}} </div>
                                          <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Telephone 2</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td  style="font-size: 82%;" >
                                    <strong>Profession : </strong>
                                  </td>
                                  <td style="font-size: 82%; position : absolute; table-layout: auto; word-wrap: break-word;">
                                          {{$demande->profession}}
                                  </td>
                                </tr>
                                <tr>
                                  <td  style="font-size: 82%;" >
                                    <strong>Employeur : </strong>
                                  </td>
                                  <td style="font-size: 82%; position : absolute; table-layout: auto; word-wrap: break-word;">
                                          <div class="textgestion">{{$demande->employeur}} </div>
                                          <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Employeur</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td></td>
                                  <td style="font-size: 82%;" >
                                      <div class="textgestion">{{$demande->adresse_employeur}}</div>
                                      <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Adresse</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td></td>
                                  <td style="font-size: 82%;" >
                                      <div class="textgestion"></div>
                                      <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Numéro postal</span></p>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2" class="center">------------------------------------------Signature------------------------------------------</td>
                              </tr>
                              <tr>
                                  <td colspan="2" style="font-size: 80%;; word-break: break-all; width:100px;" class="">
                                      Par la présente signature, je certifie avoir rempli correctement et avec exactitude les données ci-dessous
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="1"  class="">
                                     <strong>Signature du demandeur</strong>
                                     <p class="sousdescription"><span style="width: 200px;font-size: 80%;">Prière de signer à l'intérieur du rectangle</span></p>
                                  </td>
                              </tr>

                          </tbody>
                      </table>
                  </div>
              </div>
          </td>
      </tr>
  </table>
</div>

<style>
  #administrateur{
      /* background-color: green; */
      height: 100%;
      width: 100%;
  }
  #empreinte_photo{
      width: 235px;
      height: 265px; /* longeur */
      border: 1px dashed black;
  }
  #photo_demandeur{
      /* border: 1px dashed black; */
      /* text-align: center;  */
      width: 235px;
      height: 500px;
      /* width:100%; */
      overflow: hidden;
      position: absolute;
      top: 1770%;
      left: 400%;
      table-layout: auto;
      word-wrap: break-word;
  }
  #photo_demandeur_2{
      /* background-color: #e22424; */
      border: 1px dashed rgb(228, 125, 21);
      position:absolute;
      padding: 10%;
      width: 130px;
      height: 150px;
      top: 3%;
      left: 3%;
  }
  #contour_photo_demandeur
 .parent {
    width: 100vw;
  }

  p {
    border: 1px dashed black;
    padding: 1em;
    font-size: calc(0.6vw + 0.6em);
    direction: ltr;
    width: 30vw;
    margin:auto;
    text-align:justify;
    word-break: break-word;
    white-space: pre-line;
    overflow-wrap: break-word;
    -ms-word-break: break-word;
    word-break: break-word;
    -ms-hyphens: auto;
    -moz-hyphens: auto;
    -webkit-hyphens: auto;
    hyphens: auto;
  }
  #cadre{
      border: 1px solid black;
      border-radius: 18px;
      padding: 2%;
      height: 99%;
      padding: 5%
  }
  .armoiri{
      height: 70px;
  }
  .armoiri_2{
      height: 65px;
  }
  #nav_1{
      height: 80%;
      margin: 0%;
      padding: 0%;
  }
  #nav_2{
      /* position: fixe; */
      height: 100%;
      width: 100%;
  }
  #tete{
      border: 1px solid black;
      background-color: #000000

  }

  #quitance{

  }
  .nomarge{
      margin: 0%;
      padding: 0 0 4% 0;
  }
  .center{
      text-align: center;
  }
  .tb{
      color: #ffffff;
  }
  .contour{
      border: 1px solid black;
  }




  #codebar_space{
      border: 1px dashed #000000;
      border-radius: 7px;
      padding: 10px;
      height: 10%;
      width: 330px;
      margin-left: 20%;
  }
  #codebar_space_2{
      border: 1px dashed #000000;
      border-radius: 4px;
      margin: 1% 0% 1% 4%;
      padding: 10px;
      height: 10%;
  }
  #code_impression{
      border: 1px dashed #000000;
      margin: 1% 0% 1% 3%;
      height: 8%;
  }
  #code_impression_texte{
      margin: 7% 0% 0% 2%;
  }
  #remplir{
      border: 1px solid #000000;
      border-radius: 20px 7px 7px 7px ;
      width: 100%;
      height: auto;
  }
  #remplir_2{
      width: 100%;
      height: auto;
  }
  #demandeur_remplir{
      margin: 2% 0% 0% 3%
  }
  #administrateur_remplir{
      margin: 2% 0% 0% 3%;

  }
  #part_1{
      /* background-color: #000000; */
      /* text-align: center; */

  }
  #part_2{

  }



  .hr {
  /* centre verticalement les enfants entre eux */
  align-items: center;

  /* active flexbox */
  display: flex;

  /* garde le texte centré s’il passe sur plusieurs lignes ou si flexbox n’est pas supporté */
  text-align: center;
  }

.hr::before,
.hr::after {
/* remplir le fond du trait permet également d’utiliser des images ou dégradés ! */
background: currentColor;

/* nécessaire pour afficher les pseudo-éléments */
content: "";

/* partage le reste de la largeur disponible */
flex: 1;

/* l’unité « em » garantit un ratio constant avec la taille du texte */
height: .025em;

/* espace les traits du texte */
margin: 0 .5em;
}



.hrp {
border: none;
border-top: 3px double rgb(0, 0, 0);
color: rgb(0, 0, 0);
overflow: visible;
text-align: center;
height: 5px;
}

.hrp:after {
background: rgb(14, 14, 14);
content: '0';
padding: 0 4px;
position: relative;
top: -13px;
}

.sousdescription{
  margin: 0%;
}
.textgestion{
  word-break: break-all;
  width:100px;
}
hr{
  margin: 0%;
  padding: 0%;
}
p{
  margin: 0%;
  padding: 0%;
}
</style>
