<page backimg="img/stransmis/stransmis.png" backimgopacity="2.5" backimgw="580">
    {{-- <div style="position: absolute; top: 0; left: -2%; width: 2%; height: 0; z-index: 0; pointer-events: none; margin: 0">
        <table style="width: 100%; height: 100%; border-collapse: collapse; margin: 0;">
          <tr>
            <td style="width: 33.33%; height: 1060px;background-color: green;"></td>
            <td style="width: 33.33%; height: 1060px;background-color: yellow;"></td>
            <td style="width: 33.33%; height: 1060px;background-color: red;"></td>
          </tr>
        </table>
    </div> --}}
    <div id="cadre" style="padding-left: 10px; padding-top: 10px; margin-left: 20px; margin-right: 100px; width: calc(100% - 120px); height: calc(100% - 20px);">

        <div id="tete">
        <table cellspacing="3" id="nav_2">
            <tr >
                <td style="width:3%;">
    <img class="armoiri" 
         src="{{ public_path('img/armoi_2.png') }}" 
         id="carte" 
         alt="">
</td>

                <td style="">
                        <h3 class="nomarge tb">DEMANDE DE DELIVRANCE</h3>
                        <h3 class="nomarge tb">DE CARTE DE RESIDENT</h3>
                </td>
                <td style="width:3%;">
    <img class="armoiri_2"
         src="{{ public_path('img/congo_2.png') }}"
         id="carte"
         alt="">
</td>

                <td style="width:auto;" colspan="1">
                    <h4 class="nomarge center tb">REPUBLIQUE DU CONGO</h4>
                    <h6 class="nomarge center tb">Unité * Travail * Progrès</h6>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="tb" style="font-size: 50%;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     MINISTERE DE L'INTERIEUR ET DE LA DECENTRALISATION - CENTRALE D'INTELIGENCE ET DE DOCUMENTATION-DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    </span>
                </td>
            </tr>
        </table>
        </div>
          <div style="margin: 2px; position: relative;top: 10px; width: 95%">
            <div class="center" style="border: 1px solid black; padding: 3px; height: 10px; margin-top: 10px; width: 95%" >  {{--Modification du width: 95%" --}}
                <h3 class="majuscule">departement des migrations et du controle des etrangers</h3>
            </div>
            <div class="center" style="border-bottom: 1px; padding: 3px; height: 10px; margin-top: 10px; width: 95%" >
                <p class="majuscule center" style="font-size: 20px">récépisse de demande d'un titre de séjour</p>
            </div style="width: 95%">
              <div style="padding: 1px; width: 95%">
                <table style="width: 95%">
                    <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                               <div style="position: relative;">
    <img style="width: 100px;"
         src="{{ public_path('app/'.$demande->photo) }}"
         alt="">
</div>

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
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 202px">
                                                            </td>
                                                            <td class="majuscule">
                                                                <strong style="background-color: #d7d7d7">Informations personnelles</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td class="majuscule">
                                                Prefceture : <strong>Prefecture de police</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="majuscule">
                                                DOSSIER Nº : <strong>{{$demande->uuid}}</strong>
                                            </td>
                                        </tr> --}}
                                        <tr>
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
                                        <tr class="majuscule top_4">
                                            <td style="">
                                                prénom : <strong>{{$demande->impetrant?->prenom}}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="majuscule top_4">
                                                né(e) le : <strong>{{date('d/m/Y', strtotime($demande->impetrant?->date_naissance))}}</strong> à <strong>{{$demande->impetrant?->lieu_naissance}}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="majuscule top_4">
                                                état civil : <strong>{{$demande?->etat_civil}}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="majuscule top_4">
                                                nationnalite : <strong>{{$demande->impetrant?->pays?->nationalite}}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                            <td>
                                <div class="qr_code">
                                    <qrcode class="qrcode" value="{{$demande->uuid}}" style="width: 45mm; color: #003333"></qrcode>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="majuscule">
                                                fait à Brazzaville le : <strong>
                                                   @if(!empty($demande->date_demande))
     {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}
@else
    {{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y') }}
@endif

                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="majuscule" style="padding: 2px; padding-top: 10px;">
                                                Valable jusqu'au : 
                                                <strong style="color: red">
                                                    @if($demande->fiches->count() > 0)
                                                        {{ \Carbon\Carbon::parse($demande->fiches->last()->date_valite_fiche)->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($demande->date_validiter_fiche)->format('d/m/Y') }}
                                                    @endif
                                                </strong>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 420px; text-align: right">
                                <barcode dimension="1D" type="C128" value="{{ $demande->uuid }}" label="label" style="width:30mm; height:12mm; color: #000; font-size: 4mm"></barcode>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <div style="width: 95%; height: 2px; background-color: black; margin: -7px auto 0 auto;"></div>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div class="cercle">
                                    2
                                </div>
                            </td>
                            <td class="majuscule">
                                <strong style="background-color: #d7d7d7">cas d'utilisation du titre sollicité </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tbody>
                                        @php
$unchecked = public_path('img/unchecked.png');
@endphp
                                        <tr>
    <td>
        <img src="{{ $unchecked }}" alt=""> Première demande (Je n'ai pas encore de titre de séjour)
    </td>
</tr>
<tr>
    <td>
        <img src="{{ $unchecked }}" alt=""> 2em titre de séjour (Je suis autorisé à avoir un deuxième titre de séjour)
    </td>
</tr>
<tr>
    <td>
        <img src="{{ $unchecked }}" alt=""> Renouvellement de titre de séjour (Mon titre de séjour est perdu, à refaire ou expiré)
    </td>
</tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                        </tr>
                    </tbody>
                </table>
                {{-- AUTres informations --}}
                <br>
                <br>
                <div style="width: 95%; height: 2px; background-color: black; margin: -7px auto 0 auto;"></div>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div class="cercle">
                                    3
                                </div>
                            </td>
                            <td class="majuscule">
                                <strong style="background-color: #d7d7d7">Informations complementaire </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <tr>
                                <td class="majuscule top_4" style="padding: 2px; padding-top: 10px;">
                                    nom et prénom père : <strong>{{$demande->impetrant?->nom_pere}}</strong>, <strong>{{$demande->impetrant?->prenom_pere}}</strong>
                                </td>
                                <td style="width: 100px;">

                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="majuscule top_4" >
                                    nom et prénom mère : <strong>{{$demande->impetrant?->nom_mere}}</strong>, <strong>{{$demande->impetrant?->prenom_mere}}</strong>
                                </td>
                                <td>

                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="majuscule top_4" colspan="2" >
                                    Adresse : <strong>{{$demande?->numero_adresse}},{{$demande?->avenue_rue}}, {{$demande?->quartier?->lib_quartier}} - {{$demande?->quartier?->arrondissement?->lib_arrondissement}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="majuscule top_4" >
                                    Telephone : <strong>{{$demande?->telephone}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="majuscule top_4">
                                    profession : <strong>{{$demande->profession}}</strong>
                                </td>
                            </tr>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div style="width: 95%; height: 2px; background-color: black; margin: -7px auto 0 auto;"></div>
                <table style="margin: 0; width: 95px">
                    <tbody>
                        <tr>
                            <td>
                                <p >
                                    Je certifie que toutes les informations sur ce document sont exactes et je reconnais avoir été informé que ma demande
                                    <p>pourrait ne pas aboutir si la totalité des pièces exigées n'est pas conforme à la réglementation en vigueur.</p>
                                    <u>Lu et approuvé</u>                                    
                                </p>
                            </td>
                        </tr>
                        <tr  style=" margin 0;">
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 90px">
                                                &nbsp;
                                            </td>
                                            <td style="width: 350px;">
                                                Signature de l'interéssé
                                            </td>
                                            <td style="text-align: center; " class="">
                                                Administrateur Principal du <br>
                                                Département des Migrations et du Contrôle <br>
                                                des Etrangers
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @php
$cachetRond = public_path('img/crt/cachet_rond.png');
$signature = public_path('img/crt/signature.png');
$cachetNom = public_path('img/crt/cachet_nominatif.png');
@endphp

{{-- Cachet du directeur --}}
<div style="position: absolute; top: 103%; left: 70%;">
    @if(file_exists($cachetRond))
        <img src="{{ $cachetRond }}" style="width:150px;">
    @endif
</div>

{{-- Signature --}}
<div style="position: absolute; top: 110%; left: 60%;">
    @if(file_exists($signature))
        <img src="{{ $signature }}" style="width:150px;">
    @endif
</div>

{{-- Cachet date --}}
<div style="position: absolute; top: 116%; left: 50%;">
    @if(file_exists($cachetNom))
        <img src="{{ $cachetNom }}" style="width:150px;">
    @endif
</div>

              </div>
          </div>

      </div>

      <style>
        body {
            margin: 0;
            padding: 0;
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
            /* padding: 2%; */
            height: 100%;
            width: calc(100% - 120px);
            /* background-color: bisque; */
            /* padding: 5% */
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
        /* #tete{
            width: 60px;
            border: 1px solid black;
            background-color: #000000

        } */

        #tete {
            width: auto;
            border: 1px solid black;
            background-color: #000000;
            padding: 10px;
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
      p{
        margin: 0%;
        padding: 0%;
      }
      .center{
        text-align: center;
      }
      .cercle {
        width: 20px; /* Taille du cercle */
        height: 10px; /* Taille du cercle */
        background-color: #000; /* Couleur de fond du cercle */
        color: #fff; /* Couleur du texte */
        border-radius: 50%; /* Bord arrondi pour former un cercle */
        text-align: center; /* Centrer le texte horizontalement */
        line-height: 50px; /* Centrer le texte verticalement */
        font-size: 20px; /* Taille du texte */
        }
        .top_4{
            padding-top: 4px;
        }
        .qr_code{
            /* border: 1px solid black; */
            height: 140px;
            width: 190px;
        }
        .signature{
            border: 1px solid black;
            height: 90px;
            width: 120px;
        }
        hr {display:block; margin : -7px 0; width:200px;}
        .signature_bas{
            position: absolute;
            border: 1px solid black;
            height: 90px;
            width: 320px;
            bottom: -200; /* Place l'élément en bas de la fenêtre */
            left: 430; /* Place l'élément tout à gauche */
            /* height: 10px;
            width: 10px; */
        }
      </style>

</page>
