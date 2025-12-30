<page backimg="img/stransmis/stransmis.png" backimgopacity="0.5" backimgw="580">
    <page_header>
        <!-- Contenu de l'en-tête de page -->
    </page_header>
    
    <!-- Contenu de la page -->
    <table style="margin-top: 7%; width: 100%; text-align: center;">
        <tbody>
            <!-- Afficher le Ministère si sélectionné -->
            @if($entete == 1)
            <tr>
                <td colspan="2" class="size4">
                    MINISTÈRE DE L’INTÉRIEUR<br>
                    ET DE LA DÉCENTRALISATION<br>
                    ----------------------
                </td>
            </tr>

            <!-- Afficher la Centrale -->
            <tr>
                <td colspan="2" class="size4">
                    CENTRALE D’INTELLIGENCE<br>
                    ET DE LA DOCUMENTATION<br>
                    ----------------------
                </td>
            </tr>
            @endif
            <!-- Afficher le Département -->
            <tr>
                <td colspan="2" class="size4">
                    DÉPARTEMENT DES MIGRATIONS<br>
                    ET DU CONTRÔLE DES ÉTRANGERS<br>
                    ----------------------
                </td>
            </tr>

            <!-- Afficher la Division et la Section -->
            @if($division)
            <tr>
                <td colspan="2" class="size4">
                    {!! strtoupper($division) !!}
                    <br>
                    ----------------------
                    <br>
                    @if($section && $entete != 1)
                    <strong>{!! strtoupper($section) !!}</strong>
                    <br>
                    ----------------------<br>
                    N_____/DMCE/DDS/SED<br>
                    @endif
                </td>
            </tr>
            <!-- Date -->
            @endif
        </tbody>
    </table>
    <div style="position: absolute; top: 80px; right: 30px; text-align: center;" class="size4">
        RÉPUBLIQUE DU CONGO <br>
        <strong>Unité – Travail – Progrès</strong> <br>
        ----------------------
    </div>

    <table>
        <tbody>
            <tr>
                <td style="text-align: center; width: 500px;" class="size4"></td>
                <td class="size4">
                    Brazzaville, le <strong style="color: #FF0000">{{date('d/m/Y')}}</strong>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Titre principal -->
    <div class="titre">
        <span style="font-size: 11pt;"><h4>{{ $title }}</h4></span>
    </div>
    <div style="overflow-x: auto;">
        <table id="table">
            <thead>
                <tr>
                    <th class="th" style="width: 100px;">Nationnalité</th>
                    <th class="th" style="width: 100px;">Homme</th>
                    <th class="th" style="width: 100px;">Femme</th>
                    <th class="th" style="width: 100px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <!-- Le contenu des cellules sera généré avec une boucle PHP -->
                @foreach ($resultats as $resultat)
                <tr>
                    <td class="td" style="word-wrap: break-word">{{$resultat->nationalite}}</td>
                    <td class="td">{{$resultat->Masculin}}</td>
                    <td class="td">{{$resultat->Feminin}}</td>
                    <td class="td">{{$resultat->total}}</td>
                </tr>
                @endforeach
                <tr id="total_cel" class="total-row">
                    <td class="td" style="word-wrap: break-word"><strong>TOTAL</strong></td>
                    <td class="td"><strong>{{$somme['Masculin']}}</strong></td>
                    <td class="td"><strong>{{$somme['Feminin']}}</strong></td>
                    <td class="td"><strong>{{$somme['total_par_nationalite']}}</strong></td>
                </tr>
            </tbody>
          </table>

        <div style="margin-top: 20px; padding: 10px; border: 1px solid #333; background-color: #f9f9f9; page-break-inside: avoid;">
            <h5 style="margin: 0; color: #333;">Commentaires</h5>
            <p style="margin: 5px 0; font-size: 10pt; line-height: 1.5;">
                {{ $commentaires ?? 'Aucun commentaire.' }}
            </p>
        </div>    
    </div>

    <!-- Signataire -->
    <div style="margin-top: 80px; text-align: right; font-size: 11pt;">
        <u>{{ ucfirst($signataire->grade->grade) }} <strong>{{ $signataire->nom }} {{ $signataire->prenom }}</strong></u>
    </div>
    <style>
        #table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10pt;
            margin: auto;
            table-layout: fixed;
        }
        .th, .td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            word-wrap: break-word;
        }
        #table th, #table td {
            border: 1px solid black;
            padding: 2px 5px;
            text-align: center;
            height: 10px;
        }
        .total-row {
            background-color: #333;
            color: white;
        }
        .titre {
            margin-top: 20px;
            margin-bottom: 5px;
            text-align: center;
            text-decoration: underline;
            font-size: 12pt;
        }
        .size4 {
            font-size: 12pt;
        }
    </style>
    
    <page_footer>
    </page_footer>
</page>