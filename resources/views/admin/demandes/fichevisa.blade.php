<style type="text/css">
    table
    {
        border: medium solid #6495ed;
        border-collapse: collapse;
    }

    td,th {
        /* border:solid; */
        width: 100%;
        padding: 1px;
        text-align: left;
        font-size: 14px ! important;

    }
    .bordure{
        border: 1px solid red;
    }
    .bordurebas{
        border-bottom: 1px solid red;
    }
    td{
        font-size: 80%;
    }
    b{
        font-size: 100%;
    }
    small, p, th, td{
        color: red;
    }

    p.ligne{
        border-bottom:1px solid red;
    }

    .pall{
        padding: 4px;
    }


</style>
<page orientation="portrait" backcolor="#FEFEFE" backimgx="center"  backimgw="38%"
      backtop="0mm"
      backbottom="0mm"
      backcenter="0mm">


    <table cellspacing="0" style="width: 99%; font-size: 14px;">
        <col style="width: 40%">
        <col style="width: 20%">
        <col style="width: 40%">
        <thead>
            <tr style="text-align: center">
                <th style="text-align: center;"></th>
                <th style="text-align: center;"></th>
                <th style="text-align: center;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">
                    MINISTERE DE L'INTERIEUR ET DE LA DECENTRALISATION  <br>
                    ------------------------ <br>
                    CENTRALE D'INTELLIGENCE ET DE DOCUMENTATION <br>
                    ------------------------ <br>
                    DEPARTEMENT DES MIGRATIONS ET DE CONTROLE DES ETRANGERS <br>
                    ------------------------ <br>
                </td>
                <td></td>
                <td style="text-align: center;">
                    REPUBLIQUE DU CONGO <br>
                    Unité - Travail -Progrès <br><br>

                    <table cellspacing="0" style="width: 99%; font-size: 14px;">
                        <col style="width: 16%">
                        <col style="width: 16%">
                        <col style="width: 16%">
                        <col style="width: 16%">
                        <col style="width: 16%">
                        <col style="width: 16%">
                        <col style="width: 16%">
                        <thead>
                            <tr style="text-align: center">
                                <th style="text-align: center;" colspan="6" class="bordure">DEMANDE POUR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;" class="bordure">E</td>
                                <td style="text-align: center;" class="bordure"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;border-right: 1px solid red;"></td>
                                <td style="text-align: center;" class="bordure">R</td>
                                <td style="text-align: center;" class="bordure"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table cellspacing="0" style="width: 99%; font-size: 14px;">
        <col style="width: 60%">
        <col style="width: 40%">
        <thead>
            <tr style="text-align: center">
                <th style="text-align: center;" class="bordurebas"></th>
                <th style="text-align: center;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;" class="bordure">
                    N° _____________________ DU ____________________
                </td>
                <td style="text-align: center;">
                    VCS________________________ MOIS <br>
                    VLS________________________ MOIS <br>
                    CLS________________________ MOIS <br>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table cellspacing="0" style="width: 99%; font-size: 14px;">
        <col style="width: 40%">
        <col style="width: 60%">
        <thead>
            <tr style="text-align: center">
                <th style="text-align: center;"></th>
                <th style="text-align: center;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left;">
                    <div class="bordure pall">
                        Passeport <br><br>
                        N° {{$demande->passeport()?->numero_document}}<br><br>
                        Délivré le____________________________________ <br><br>
                        à ____________________________________________ <br><br>
                        valable j'usqu'au_____________________________ <br><br>
                        valable j'usqu'au_____________________________ <br><br>
                        <div style="margin-left: 80px;">
                            {{-- <img src="C:\laragon\www\pnfs-rgb\public\images\maphoto.jpg" width="120px" height="140px"> --}}
                            <img src="{{ asset("app/$demande->photo") }}" style="width: 100px;height: 140px;">
                        </div>
                    </div><br>
                    <div class="bordure pall">
                        {{-- @forelse ($demande->pieces as $piece)
                        - {{ $piece->piece }} <br><br>
                        @empty

                        @endforelse --}}

                        - Carte consulaire <br><br>
                        - Ancien titre <br><br>
                        - Visa d'entréé <br><br>
                        - Deux photos <br><br>
                        - Certificat de domiciliation <br><br>
                        - Attestation d'employeur <br><br>
                        - Contrat de travail <br><br>
                        - Certificat médical <br><br>
                        - Pattente <br>
                    </div><br>
                    <div class="bordure pall" style="border-radius: 25px;padding-left: 12px;">
                        N° téléphone : {{$demande->telephone}}<br><br>
                        N° téléphone : {{$demande->telephone}}<br>
                    </div>
                </td>
                <td style="text-align: left;" class="pall" style="padding-left: 15px;">
                    Nom(s): {{$demande->impetrant?->nom}} <br><br>
                    Prénom(s): {{$demande->impetrant?->prenom}} <br><br>
                    Date et lieu de naissance: {{$demande->impetrant?->date_naissance}} {{$demande->impetrant?->lieu_naissance}} <br><br>
                    Fils de: {{$demande->impetrant?->nom_pere}}, Et de: {{$demande->impetrant?->nom_mere}} <br><br>
                    Nationalité d'origine: {{$demande->impetrant?->pays?->lib_pays}}, Actuelle {{$demande->impetrant?->pays?->lib_pays}} <br><br>
                    Etat matrimonial: <br><br> <span style="{{ $demande->etat_civil=="Marié(e)" ? 'text-decoration:underline':'' }}"> Mairié(e) </span>, <span style="{{ $demande->etat_civil=="Veuf(ve)" ? 'text-decoration:underline':'' }}"> Veuf(ve) </span>, <span style="{{ $demande->etat_civil=="Divorcé(e)" ? 'text-decoration:underline':'' }}"> Divorcé(e)</span>, <span style="{{ $demande->etat_civil=="Célibataire" ? 'text-decoration:underline':'' }}"> Célibataire</span>  <br><br>
                    Employeur: {{$demande->employeur?->nom_employeur}}<br><br>
                    Adresse: <br><br>
                    <div style="margin-left: 20px;">
                        Département: {{$demande->quartier?->arrondissement?->departement?->lib_departement}} <br><br>
                        Arrondissement: {{$demande->quartier?->arrondissement?->lib_arrondissement}} <br><br>
                        Quartier: {{$demande->quartier?->lib_quartier}} <br><br>
                        Ruelle: {{$demande->employeur->adresse_physique}} <br><br>
                        Numero: ______________________________________________________ <br><br>
                    </div>
                    Profession: {{$demande->profession}} <br><br>
                    Sexe:  {{ $demande->impetrant?->sexe == 'Masculin'  ? 'Masculin':'Féminin' }}<br><br>
                    Lieu d'embarquement (Hors Congo): ___________________________________ <br><br>
                    Lieu de débarquement (Au Congo):____________________________________ <br><br>
                    Voix utilisée:  <br><br>
                    Date d'arrivée au Congo: __________________________________________ <br><br>
                    Motifs détaillés du sejour: __________________________________________ <br><br>
                    Numero de l'ancien titre de sejour: {{$demande->numero_ancien_document}} <br>
                    <small>NB: Je soussigne, certifie exacts les renseignements ci-dessous, toute faute d'orthographe sera ma responsabilité tant qu'elle est identique à ce qui est écrit</small><br><br>
                    <div class="bordure" style="text-align: center;margin-left: 60px;margin-right: 60px;border-radius: 25px;">
                        <p>Signature du demandeur</p><br><br>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" style="width: 99%; font-size: 14px;">
        <col style="width: 50%">
        <col style="width: 50%">
        <thead>
            <tr style="text-align: center">
                <th class="bordure" style="text-align: center;" colspan="2">PARTIE RESERVEE A L'ADMINISTRATION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; border-left: 1px solid red;border-bottom: 1px solid red;">
                    <small><u>DIVISION DOCUMENTS DE SEJOUR</u></small>
                    <table cellspacing="0" style="width: 99%; font-size: 14px;">
                        <col style="width: 100%">
                        <thead>
                            <tr style="text-align: center">
                                <th style="text-align: center;" class="bordurebas"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bordure">
                                <td style="text-align: center; padding-top: 30px; padding-bottom: 30px;" class="bordure">
                                    <h4 style="color: #ebcaca;">Observation</h4>
                                </td>
                            </tr>
                        </tbody>
                    </table><br>

                    <small><u>ADMINISTRATEUR PRINCIPAL</u></small>
                    <table cellspacing="0" style="width: 99%; font-size: 14px;">
                        <col style="width: 50%">
                        <col style="width: 50%">
                        <thead>
                            <tr style="text-align: center">
                                <th style="text-align: center;" class="bordurebas"></th>
                                <th style="text-align: center;" class="bordurebas"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center; padding-bottom: 60px;" class="bordure">
                                    INSTRUCTION
                                </td>
                                <td style="text-align: center; padding-bottom: 60px;" class="bordure">
                                    DECISION FINALE
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="text-align: center;border-bottom: 1px solid red;border-right: 1px solid red;">
                    <u>SECRETARIAT</u> <br><br>
                    Numero du visa : {{$demande->numero_document}} <br><br>
                    Date d'émission : {{$demande->date_emission}} <br><br>
                    Date d'expiration : {{$demande->date_expiration}} <br><br>
                </td>
            </tr>
        </tbody>
    </table>
</page>
