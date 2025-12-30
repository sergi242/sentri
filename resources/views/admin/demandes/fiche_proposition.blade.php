<page backimg="img/stransmis/stransmis.png" backimgopacity="2.5" backimgw="580">
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
                      <barcode dimension="1D" type="C128" value="{{ $demande->uuid }}" label="label" style="width:30mm; height:12mm; color: #000; font-size: 4mm"></barcode>
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
                        MINISTERE DE L'INTERIEUR ET DE LA DECENTRALISATION - CENTRALE D'INTELIGENCE ET DE DOCUMENTATION-DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS
                    </span>
                </td>
            </tr>
        </table>
        </div>
          <div style="margin: 2px; position: relative;top: 10px;text-align: center">
              <h4 class="majuscule" style="color: #868686"> République du Congo</h4>
              <h2 class="majuscule">récépicé de demande de carte de séjour</h2>
      
          </div>
          <div style="padding: 20px;">
            <table>
                <tbody>
                    <tr>
                        <td >
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="majuscule">
                                            Prefceture : <strong>Prefecture de police</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule">
                                            DOSSIER Nº : <strong>{{$demande->uuid}}</strong>
                                        </td>
                                    </tr>
                                    <tr >
                                        <td class="majuscule" style="padding: 2px; padding-top: 10px;">
                                            NOM : <strong >
                                                @if ($demande->impetrant?->sexe == 'Masculin')
                                                    (M) {{$demande->impetrant?->nom}}
                                                @elseif ($demande->impetrant?->sexe == 'Féminin')
                                                    (MME) {{$demande->impetrant?->nom}}
                                                @else
                                                    {{$demande->impetrant?->nom}}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr class="majuscule">
                                        <td style="">
                                            prénom : <strong>{{$demande->impetrant?->prenom}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule">
                                            né(e) le : <strong>{{date('d/m/Y', strtotime($demande->impetrant?->date_naissance))}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule" style="padding: 2px; padding-top: 10px;">
                                            nom père : <strong>{{$demande->impetrant?->nom_pere}}</strong>, prénom père : <strong>{{$demande->impetrant?->prenom_pere}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule" >
                                            nom père : <strong>{{$demande->impetrant?->nom_mere}}</strong>, prénom père : <strong>{{$demande->impetrant?->prenom_mere}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule" style="padding: 2px; padding-top: 10px;">
                                            nationnalite : <strong>{{$demande->impetrant?->pays?->nationalite}}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div style="position: relative; left: 80px;">
                                                <img src="{{ asset('app/'.$demande->photo) }}" alt="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule">
                                            fait à brazzaville le : <strong style="color: red">{{date('d/m/Y', strtotime($demande->created_at))}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="majuscule" style="padding: 2px; padding-top: 10px;">
                                            Valable jusqu'au : _______________
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                                              
                    </tr>
                </tbody>
            </table>
          </div>
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
      .majuscule {
          text-transform: uppercase;
          margin: 2%;
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
            /* border: 1px solid black; */
            /* border-radius: 18px; */
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
      
</page>