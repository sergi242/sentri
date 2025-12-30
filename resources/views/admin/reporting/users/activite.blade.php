<page backimg="img/stransmis/stransmis.png" backimgopacity="0.5" backimgw="580">
    <!-- En-tête principal -->
    <table style="margin-top: 7%">
        <tbody>
            <tr>
                <td style="text-align: center;">
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: center;" class="size4">
                                    MINISTERE DE L'INTERIEUR<br>
                                    ET DE LA DECENTRALISATION<br>
                                </td>   
                                <td style="text-align: center; width: 600px;" class="size4">
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
                                <td style="text-align: center;" class="size4">
                                    CENTRALE D'INTELLIGENCE<br>
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
                        </tbody>
                    </table>
                </td>
            </tr>
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
                <td style="width: 70%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <th style="width:50%;">Nom Complet</th>
                            <td>{{ $user->getNomPrenom() }}</td>
                        </tr>
                        @if($user->email)
                        <tr>
                            <th style="width:50%;">Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th style="width:50%;">Rôle</th>
                            <td>{{ $user->role?->lib_role ?? 'Non défini' }}</td>
                        </tr>
                        <tr>
                            <th style="width:50%;">Grade</th>
                            <td>{{ $user->grade?->grade ?? 'Non défini' }}</td>
                        </tr>
                    </table>
                </td>
        
                <td style="width: 30%; text-align: center;">
                    <div class="photo-placeholder">
                        @if($user->photo && file_exists(public_path('uploads/users/'.$user->photo)))
                            <img 
                                src="{{ public_path('uploads/users/'.$user->photo) }}"
                                style="width:120px; height:150px; border:1px solid #000;"
                            >
                        @else
                            
                        @endif

                    </div>
                </td>
            </tr>
        </table>

        <!-- Statistiques Globales -->
        <div class="full-width-section">
            <h4 class="section-title">&nbsp;Statistiques Globales</h4>
        </div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            <tr>
                <th style="background-color: #2c5aa0; color: white; padding: 8px 12px; text-align: left; border: 1px solid #1e4070;">Demandes Créées</th>
                <td style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: white;">
                    <span style="font-size: 12px; font-weight: bold; color: #d32f2f;">{{ $user->demandes->count() }}</span>
                </td>
            </tr>
            <tr>
                <th style="background-color: #2c5aa0; color: white; padding: 8px 12px; text-align: left; border: 1px solid #1e4070;">Demandes Attribuée</th>
                <td style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: white;">
                    <span style="font-size: 12px; font-weight: bold; color: #d32f2f;">{{ $user->dossiers_attribues_count }}</span>
                </td>
            </tr>
            <tr>
                <th style="background-color: #2c5aa0; color: white; padding: 8px 12px; text-align: left; border: 1px solid #1e4070;">Soit Transmis</th>
                <td style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f8f9fa;">
                    <span style="font-size: 12px; font-weight: bold; color: #d32f2f;">{{ $user->soitTransmis->count() }}</span>
                </td>
            </tr>
            <tr>
                <th style="background-color: #2c5aa0; color: white; padding: 8px 12px; text-align: left; border: 1px solid #1e4070;">Flux Migratoires</th>
                <td style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: white;">
                    <span style="font-size: 12px; font-weight: bold; color: #d32f2f;">{{ $user->fluxMigratoires->count() }}</span>
                </td>
            </tr>
        </table>

        <!-- Statistiques Détaillées -->
        <div class="full-width-section">
            <h4 class="section-title">&nbsp;Statistiques Détaillées</h4>
        </div>
        
        @if($user->demandes->isEmpty())
            <p style="text-align: center; font-size: 14px; font-weight: bold; color: #555;">Aucune activité.</p>
        @else
            <div class="statistiques-detailles">
                @foreach($user->demandes->groupBy('statut_demande') as $statut => $demandes)
                    <div class="statut-item">
                        <span class="statut-text">{{ $statut ?: 'Non défini' }}</span>
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
    .size4 {
        font-size: 12px;
    }

    .full-width-section {
        background-color: #2c5aa0; /* Bleu institution */
        margin: 10px 0;
        padding: 10px 0;
        width: 100%;
        box-sizing: border-box;
    }

    .section-title {
        color: #ffffff; /* Texte blanc */
        font-weight: bold;
        font-family: Arial, sans-serif;
        margin: 0;
        padding-left: 15px;
        text-align: left;
    }

    .photo-placeholder {
        width: 150px;
        height: 150px;
        border: 2px solid #2c5aa0; /* Bordure bleu institution */
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f4f8; /* Fond bleu très clair */
        color: #666;
        font-size: 12px;
        font-weight: bold;
        margin: auto;
        text-align: center;
    }

    .statistiques-detailles {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
    }

    .statut-item {
        flex: 1 1 calc(50% - 15px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #f5f5f5;
        border-left: 5px solid #2c5aa0; /* Bordure bleu institution */
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 12px;
        font-weight: bold;
        color: #333;
    }

    .statut-text {
        color: #2c5aa0; /* Bleu institution */
        font-weight: bold;
    }

    .statut-count {
        color: #d32f2f; /* Rouge vif */
        font-size: 12px;
        font-weight: bold;
    }
</style>