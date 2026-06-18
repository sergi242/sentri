<page backimg="img/stransmis/stransmis.png" backimgopacity="0.5" backimgw="580">
    <page_header>
        <!-- Contenu de l'en-tête de page -->
    </page_header>
    

    {{-- Mon filligramme --}}
    {{-- <img src="img/stransmis/stransmis.jpeg"/> --}}

    {{-- <style>
        table{
            /* background-color: aqua; */
            background-image: url("img/stransmis/stransmis.jpeg")
        }
    </style> --}}
    <!-- Contenu de la page -->
    <table style="margin-top: 7%">
        <tbody>
            <!-- Contenu des lignes de tableau -->
            <tr>
                <td style="text-align: center;">
                    <table>
                        <tbody>
                            <!-- Contenu des lignes de tableau -->
                            <tr>
                                <td style="text-align: center;" class="size4">
                                    MINISTERE DE L’INTERIEUR<br>
                                    ET DE LA DECENTRALISATION<br>
                                </td>   
                                <td style="text-align: center;  width: 600px;" class="size4">
                                    REPUBLIQUE DU CONGO <br>
                                    <strong>Unité – Travail - Progrès</strong> <br>
                                    ----------------------
                                </td>        
                            </tr>
                            <tr>
                                <td style="text-align: center;" class="size4">
                                    ----------------------
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    CENTRALE D’INTELLIGENCE<br>
                                    ET DE LA DOCUMENTATION
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    ----------------------
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;" class="size4">
                                    <strong>
                                        DEPARTEMENT DES MIGRATIONS ET DU <br>
                                        CONTROLE DES ETRANGERS
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;" class="size4">
                                    ----------------------
                                </td>
                                <td class="size4">
                                    Brazzaville, le  <strong style="color: #FF0000">{{$soit_transmis->created_at->format('d/m/Y')}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; " class="size4">
                                    SECRETARIAT
                                </td>
                            </tr>
			    <tr>
                                <td style="text-align: center;" class="size4">
                                    ----------------------
                                </td>
                            </tr>

                            <tr>
                                <td style="text-align: center;">
                                    N°<u>{{$soit_transmis->numero}}</u> /MID/CID/DMCE.Sec                                </td>
                            </tr>
                            <!-- Vous pouvez générer les lignes avec une boucle PHP -->
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- Autres lignes si nécessaire -->
        </tbody>
    </table>
    {{-- Deuxieme partie --}}
    <table style="margin-top: 3%">
        <tbody>
            <tr>
                <td style="text-align: center;  width: 1000px;" class="size3"   >
                    <strong>
                        L’Administrateur Principal du <br>
                        Département des Migrations et du Contrôle <br>
                        des Etrangers
                        @if($soit_transmis->user->id != 56)
                            ,&nbsp;PO
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;  width: 1000px;" class="size3">
                    Au
                </td>
            </tr>
            <tr>
                <td style="text-align: center;  width: 1000px;" class="size3">
                    Chef de Division Documents de séjour
                </td>
            </tr>
            <tr>
                <td style="text-align: center;  width: 1000px;" class="size3">
                    <h4>
                        <u>BRAZZAVILLE</u>
                    </h4>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Commencement de l'objet --}}
    <table style="margin-top: 2%">
        <tbody>
            <tr>
                <td style="text-align: center;  width: 400px;" class="size3">
                    <u><strong>Objet</strong></u> : Soit transmis
                </td>
            </tr>
        </tbody>
    </table>


    <table style="margin-top: 4%">
        <tbody>
            <tr>
                <td style="text-align: left;  width: 80px;" class="size3">
                    
                </td>
                <td style="text-align: left;" class="size3 bordure_basse_none" style="width: 650px">
                    @if ($soit_transmis->demandes_count == 1)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Je vous transmets, un dossier de demande de carte de résident temporaire (CRT), introduits par 
                        @if($demandes[0]->impetrant->sexe != "Masculin")
                            Madame <strong>{{$demandes[0]->impetrant->nom}} {{$demandes[0]->impetrant->prenom}}</strong> de nationnalité <strong>{{$demandes[0]->impetrant->pays->nationalite}}</strong>
                        @else
                            Monsieur <strong>{{$demandes[0]->impetrant->nom}} {{$demandes[0]->impetrant->prenom}}</strong>  de nationnalité <strong>{{$demandes[0]->impetrant->pays->nationalite}}</strong>
                        @endif
                    @else
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Je vous transmets, {{$lettre_number}} ({{$soit_transmis->demandes_count}})
                        dossiers de demande de cartes de résident temporaire (CRT), introduits par les impétrants pour impression.
                    @endif
                </td>
            </tr>
        </tbody>
    </table>


    <table style="margin-top: 20%">
        <tbody>
            <tr>
                <td style="text-align: left;  width: 350px;" class="size3">

                </td>
                <td style="text-align: left;" class="size3">
                    <u>{{ ucfirst($soit_transmis->user->grade->grade) }}<Strong> {{$soit_transmis->user->prenom}} {{$soit_transmis->user->nom}}</Strong> </u>
                </td>
            </tr>
        </tbody>
    </table>
    
    <page_footer>
        <!-- Contenu du pied de page -->
    </page_footer>
</page>
<style>
    .bordure_basse_none {
    	border-bottom: none;
    }
    td{
        font-size: 15px;
    }
    .size3{
        font-size: 18px;
    }
    .size4{
        font-size: 104%;
    }
</style>
@if ($soit_transmis->demandes_count == 1)
    
@else
<page style="text-align: center" backimg="img/stransmis/stransmis.png" backimgw="580">
    <page_header>
        <!-- Contenu de l'en-tête de page -->
    </page_header>
    <h3>Dossiers du {{date('d/m/Y')}} </h3>
    <table style="margin-top: 2%; margin-left: auto; margin-right: auto; padding: 10px;" class="table_bordure" >
        <tbody>
            <tr>
                <td style="text-align: center; width: 32px;" class="size3 espace_table_0 bordure_td">
                   <strong> N°</strong>
                </td>
                <td style="text-align: center; width: 300px;" class="size3 espace_table bordure_td" >
                    <strong>Noms Et Prénoms</strong> 
                </td>
                <td style="text-align: center; width: 275px;" class="size3 espace_table bordure_td" >
                    <strong>Nationalité</strong>
                </td>
                <td style="text-align: center; width: 100px;" class="size3 espace_table bordure_td">
                    <strong>Obs</strong>
                </td>
            </tr>
            @foreach ($demandes as $key => $demande )
            
                <tr>
                    <td class="size3 espace_table_0" style="width: 32px; border-right: 1px solid black; border-left: 1px solid black;">{{ $key + 1 }}</td>
                    @if($demande->retire)
    <td colspan="3" class="size3" style="border-right:1px solid black; color:red; font-weight:bold;">
        DOSSIER RETIRÉ
    </td>

                            @else
                                <td class="size3" style="border-right: 1px solid black;">
                                    {{$demande->impetrant->nom}} {{$demande->impetrant->prenom}}
                                </td>
                                <td class="size3" style="border-right: 1px solid black;">
                                    {{$demande->impetrant->pays->nationalite}}
                                </td>
                                <td class="size3" style="border-right: 1px solid black;"></td>
                            @endif

                </tr>
            @endforeach
	    <tr>
                <td colspan="4" class="bordure_td" style="margin-top: 0%;">
                </td>
            </tr>

            <tr>
                <td colspan="4" class="bordure_td">
                    <strong>TOTAL</strong>
		    -----------------------------------------------------------------------------------------------------
	  	    <strong>{{$soit_transmis->demandes_count}} Dossiers</strong> &nbsp;&nbsp;
                </td>
            </tr>
        </tbody>
    </table>
    
    <page_footer>
        <!-- Contenu du pied de page -->
    </page_footer>
</page>
@endif

<style>
    .espace_table{
        width: 200px;
       
    }
    .espace_table_0{
        width: 100px;
    }
    .bordure_td{
        border: 1px solid black;
    }
    .bordure_td2{
        border-right: 1px solid black;
    }
    .table_bordure{
        border-collapse: collapse; 
        border: 1px solid black;
    }
</style>
