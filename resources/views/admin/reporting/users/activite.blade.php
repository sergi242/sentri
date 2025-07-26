<page backimg="img/stransmis/stransmis.png" backimgopacity="0.5" backimgw="580">
    <!-- En-tête principal -->
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
                                    Brazzaville, le  <strong style="color: #FF0000">{{date('d/m/Y')}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; " class="size4">
                                    {{-- SECRETARIAT --}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;" class="size4">
                                    {{-- ---------------------- --}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                </td>
                            </tr>
                            <!-- Vous pouvez générer les lignes avec une boucle PHP -->
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- Autres lignes si nécessaire -->
        </tbody>
    </table>

    <!-- Contenu principal -->
    <div style="font-family: Arial, sans-serif; font-size: 12px;">
        <!-- Informations Générales -->
        <div class="full-width-section">
            <h4 class="section-title">&nbsp;Informations Générales</h4>
        </div>
        
        <table class="user-info-table">
            <tr>
                <!-- Colonne 1 : Informations générales -->
                <td style="width: 70%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <th style="width:50%;">Nom Complet</th>
                            <td>{{ $user->getNomPrenom() }}</td>
                        </tr>
                        @if($user->email)
                        @endif
                        <tr>
                            <th style="width:50%;">Rôle</th>
                            <td>{{ $user->role?->lib_role }}</td>
                        </tr>
                        <tr>
                            <th style="width:50%;">Grade</th>
                            <td>{{ $user->grade?->grade ?? 'Non défini' }}</td>
                        </tr>
                    </table>
                </td>
        
                <!-- Colonne 2 : Cadre pour la photo -->
                <td style="width: 30%; text-align: center;">
                    <div class="photo-placeholder">
                        <span>Photo non disponible</span>
                    </div>
                </td>
            </tr>
        </table>
        
        

        <!-- Statistiques -->
        <div class="full-width-section">
            <h4 class="section-title">&nbsp;Statistiques Globales</h4>
        </div>
        <table class="statistics-table">
            <tr>
                <th>Demandes Créées</th>
                <td>
                    <div class="stat-value">{{ $user->demandes->count() }}</div>
                </td>
            </tr>
            <tr>
                <th>Soit Transmis</th>
                <td>
                    <div class="stat-value">{{ $user->soitTransmis->count() }}</div>
                </td>
            </tr>
        </table>
        

        <!-- Activité Récente -->
        <div class="full-width-section">
            <h4 class="section-title">&nbsp;Statistiques Détaillées</h4>
        </div>
        
        @if($user->demandes->isEmpty())
            <p style="text-align: center; font-size: 14px; font-weight: bold; color: #555;">Aucune activité.</p>
        @else
            <div class="statistiques-detailles">
                @foreach($user->demandes->groupBy('statut_demande') as $statut => $demandes)
                    <div class="statut-item">
                        <span class="statut-text">{{ $statut }}</span>
                        <span class="statut-count">{{ $demandes->count() }}</span>
                    </div>
                @endforeach
            </div>
        @endif
        
        
        
    </div>

    <!-- Pied de page -->
    <page_footer>
        <p style="text-align: center; font-size: 10px;">Généré automatiquement le {{ now()->format('d/m/Y') }}</p>
    </page_footer>
</page>
<style>
    .full-width-section {
        background-color: #e0e0e0; /* Couleur de fond gris clair */
        margin: 10px 0; /* Marge externe haut et bas */
        padding: 10px 0; /* Espacement interne haut et bas */
        width: 100%; /* Prend toute la largeur disponible */
        box-sizing: border-box; /* Inclut le padding dans la largeur */
    }

    .section-title {
        color: #333; /* Couleur du texte gris foncé */
        font-weight: bold; /* Texte en gras */
        font-family: Arial, sans-serif; /* Police */
        margin: 0; /* Supprime les marges du titre */
        padding-left: 15px; /* Décalage à gauche pour espacement */
        text-align: left; /* Alignement du texte à gauche */
    }


    .photo-placeholder {
    width: 150px; /* Largeur du cadre */
    height: 150px; /* Hauteur du cadre */
    border: 2px solid #ccc; /* Bordure grise légère */
    border-radius: 5px; /* Coins arrondis */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9; /* Fond légèrement gris */
    color: #666; /* Texte gris */
    font-size: 12px;
    font-weight: bold;
    margin: auto; /* Centrage */
    text-align: center;
}

.statistiques-detailles {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; /* Espacement entre les blocs */
    margin-top: 10px;
}

.statut-item {
    flex: 1 1 calc(50% - 15px); /* Deux colonnes avec un petit écart */
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background-color: #f5f5f5; /* Fond gris léger */
    border-left: 5px solid #5888a6; /* Une bordure colorée à gauche */
    border-radius: 5px; /* Coins arrondis */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombre légère */
    font-size: 14px;
    font-weight: bold;
    color: #333;
}

.statut-text {
    color: #3f73ac; /* Couleur du texte des statuts */
    font-weight: bold;
}

.statut-count {
    color: #e58e95; /* Couleur pour le nombre */
    font-size: 16px;
    font-weight: bold;
}

</style>
