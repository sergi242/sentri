# 🏛️ DMCE - Configuration du Projet
**Département des Migrations et du Contrôle des Étrangers - République du Congo**

---

## 📌 INFORMATIONS GÉNÉRALES

### Environnement
- **Serveur** : Ubuntu 22.04
- **Stack** : Laravel 10.x, PHP 8.2, MySQL/MariaDB
- **Frontend** : Bootstrap 4, jQuery, Chart.js 3.9.1
- **Icônes** : Line Awesome
- **PDF** : DomPDF / Html2Pdf
- **Port production** : 82
- **IP serveur** : 192.168.1.100
- **URL production** : http://192.168.1.100:82

### Chemins importants
```bash
# Application principale
/var/www/html/.apps/dmce

# Configuration Apache
/etc/apache2/sites-available/dmce.conf

# Logs
/var/www/html/.apps/dmce/storage/logs/laravel.log
```

### Base de données
- **Nom** : `dmce` (anciennement `old_dmce`)
- **Timezone** : Africa/Brazzaville (UTC+1)
- **Charset** : utf8mb4_unicode_ci

---

## 🗂️ ARCHITECTURE DU PROJET

### Structure des dossiers principaux
dmce/
├── app/
│   ├── Http/Controllers/
│   │   ├── StatistiqueController.php      # Dashboard principal (ancien)
│   │   ├── StatistiquesController.php     # Statistiques avancées (nouveau)
│   │   ├── DemandeController.php          # Gestion demandes
│   │   ├── SoitTransmisController.php     # Soit-Transmis + Attribution masse
│   │   ├── MonitorController.php          # Surveillance temps réel
│   │   ├── WatchlistController.php        # Système d'alertes
│   │   └── ...
│   ├── Models/
│   │   ├── User.php
│   │   ├── Demande.php
│   │   ├── SoitTransmis.php
│   │   ├── FluxMigratoire.php
│   │   ├── Impetrant.php
│   │   └── ...
│   └── Middleware/
│       └── AuditMiddleware.php            # Tracking activités
├── resources/views/admin/
│   ├── home/dashboard.blade.php           # Dashboard principal
│   ├── statistiques/dashboard.blade.php   # Stats avancées
│   ├── soittransmis/
│   │   ├── index.blade.php
│   │   ├── attribution-masse.blade.php    # Attribution en masse
│   │   └── ...
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── header-nav.blade.php
│   └── ...
├── routes/web.php                          # Routes principales
└── public/
├── img/grades/                         # Images grades militaires
└── ...

---

## 🔗 RELATIONS ENTRE MODÈLES

### User
```php
// Relations
demandes()           → hasMany(Demande, 'created_by')
demandesCreees()     → hasMany(Demande, 'created_by')  // Alias
role                 → belongsTo(Role)
grade                → belongsTo(Grade)

// Méthodes utiles
getNomPrenom()       → string "Nom Prénom"
```

### Demande
```php
// Relations
impetrant            → belongsTo(Impetrant)
createur             → belongsTo(User, 'created_by')
soitTransmis         → belongsTo(SoitTransmis, 'soit_transmis_id')
attributeur          → belongsTo(User, 'attribue_par')

// Champs importants
uuid                 → int (identifiant unique séquentiel)
numero_document      → string (format: 00XXX, calculé depuis UUID)
attribue             → boolean (0/1)
date_attribution     → date
statut_demande       → enum ('Approuvée', 'En attente...', 'Envoyée au contentieux')
type_demande         → string ('Visa', 'CRT', 'Diplomate')
```

### SoitTransmis
```php
// Relations
demandes()           → hasMany(Demande, 'soit_transmis_id')
createur()           → belongsTo(User, 'created_by')
commanditaire()      → belongsTo(User, 'commanditaire_id')
user()               → belongsTo(User, 'users_id')  // Signataire

// Champs
numero               → string (format: AAMMJJXXX)
description          → text
commanditaire_id     → int
users_id             → int (signataire)
created_by           → int
```

### FluxMigratoire
```php
// Relations
frontiere()          → belongsTo(FrontiereCongo, 'frontieres_id')
pays()               → belongsTo(Pays, 'pays_id')

// Champs (IMPORTANT: pas de type_flux!)
total_entree         → int
total_sortie         → int
date_movement        → date
frontieres_id        → int
pays_id              → int
```

### Impetrant
```php
// Relations
demandes()           → hasMany(Demande)
pays()               → belongsTo(Pays)
```

---

## 🎯 FONCTIONNALITÉS PRINCIPALES

### 1. Dashboard Principal (`/dashboard`)
- **Controller** : `StatistiqueController`
- **Vue** : `admin.home.dashboard`
- **Features** : 
  - Statistiques rapides (Aujourd'hui, Semaine, Mois, Année)
  - Calendrier FullCalendar des demandes
  - Graphiques simples

### 2. Statistiques Avancées (`/statistiques`)
- **Controller** : `StatistiquesController`
- **Vue** : `admin.statistiques.dashboard`
- **Features** :
  - 8 graphiques interactifs Chart.js
  - Filtres mois/année
  - Comparaisons périodiques
  - Export PDF (stream)
  - API endpoints pour AJAX

**Graphiques disponibles :**
1. Évolution demandes par jour (line chart)
2. Demandes vs Flux migratoires (bar chart double Y-axis)
3. Demandes par type (doughnut)
4. Demandes par statut (pie)
5. Top 10 agents actifs (horizontal bar)
6. Flux par frontière (grouped bar)
7. Flux par nationalité TOP 15 (horizontal bar)
8. Comparaison mois actuel vs précédent

### 3. Attribution en Masse (`/soit-transmis/attribution-masse`)
- **Controller** : `SoitTransmisController`
- **Vue** : `admin.soittransmis.attribution-masse`
- **Features** :
  - Recherche multi-critères (numéro, commanditaire, signataire, description, date, statut)
  - Calcul automatique numéros documents : `'00' + (UUID - 1)`
  - Attribution individuelle ou groupée
  - Transaction DB sécurisée
  - Statuts : Non attribué, Partiel (X/Y), Complet

### 4. Moniteur Temps Réel (`/monitor`)
- **Controller** : `MonitorController`
- **Vue** : à créer ou existante
- **Features** :
  - Surveillance activités en temps réel
  - Polling 5 secondes
  - Drawer par agent avec historique
  - Table `user_sessions`

### 5. Watchlist / Alertes (`/watchlist`)
- **Controller** : `WatchlistController`
- **Features** :
  - Système d'alertes sécurité
  - Vérification impétrants
  - Recherche AJAX

---

## 🔐 SYSTÈME DE PERMISSIONS

### Gates disponibles
```php
'dashboard.view'
'users.view', 'users.create', 'users.edit', 'users.destroy'
'demandes.view.all', 'demandes.view.approved', 'demandes.view.pending'
'demandes.create', 'demandes.edit', 'demandes.destroy'
'demandes.grant'              // Attribution documents
'demandes.contentieux.add'
'flux.view', 'flux.create', 'flux.edit'
'roles.view', 'roles.create'
// ... etc
```

### Middleware
```php
AuditMiddleware             // Tracking toutes activités
auth                        // Authentification
can:permission              // Autorisation
```

---

## 🗄️ TABLES CLÉS

### demandes
```sql
id, uuid, impetrants_id, created_by, type_demande, statut_demande,
numero_document, date_attribution, attribue, attribue_par,
soit_transmis_id, date_demande, created_at, updated_at, deleted_at
```

### soit_transmis
```sql
id, numero, description, users_id (signataire), 
commanditaire_id, created_by, created_at, updated_at
```

### flux_migratoires
```sql
id, frontieres_id, total_entree, total_sortie, 
date_movement, pays_id, users_id, created_at, updated_at
```

### user_sessions (monitoring)
```sql
id, user_id, session_id, ip_address, user_agent,
last_activity, login_at, logout_at
```

### users
```sql
id, nom, prenom, email, password, role_id, grade_id,
created_at, updated_at
```

---

## ⚙️ CONVENTIONS DE CODE

### Naming
- **Controllers** : Singulier `DemandeController`, `UserController`
- **Models** : Singulier `Demande`, `User`
- **Tables** : Pluriel `demandes`, `users`
- **Routes** : kebab-case `soit-transmis`, `attribution-masse`
- **Méthodes** : camelCase `getNomPrenom()`, `attributionMasseForm()`
- **Variables** : snake_case `$soit_transmis`, `$demandes_count`

### Structure Controller
```php
public function index()           // Liste
public function create()          // Formulaire création
public function store()           // Enregistrement
public function show($id)         // Détails
public function edit($id)         // Formulaire édition
public function update($id)       // Mise à jour
public function destroy($id)      // Suppression

// API pour AJAX
public function apiMethodName()   // Préfixe "api"
```

### Routes
```php
// Groupement par préfixe
Route::prefix('statistiques')->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('statistiques.index');
    Route::get('api/...', '...');
});
```

### Vues Blade
```blade
@extends('admin.layouts.app')
@section('title', 'Titre Page')
@section('content')
    <!-- Contenu -->
@endsection
@section('scripts')
    <!-- JS spécifique -->
@endsection
```

---

## 📊 FORMULES & CALCULS IMPORTANTS

### Numéro Soit-Transmis
```php
// Format: AAMMJJXXX
$annee = substr(date('Y'), 2);           // 24
$mois = chr(date('n') + 64);             // D (avril = 4 → chr(68))
$jour = date('d');                        // 08
$numero = sprintf('%03d', $sequence);    // 001
// Résultat: 24D08001
```

### Numéro Document
```javascript
// Dans attribution-masse.blade.php
function calculerNumeroDocument(uuid) {
    return '00' + (parseInt(uuid) - 1);
}
// uuid=1 → 000
// uuid=2 → 001
// uuid=100 → 0099
```

### Statut Attribution Soit-Transmis
```php
$demandesAttribuees = $soitTransmis->demandes->where('attribue', 1)->count();
$totalDemandes = $soitTransmis->demandes->count();

if ($totalDemandes == 0) {
    $statut = 'vide';
} elseif ($demandesAttribuees == 0) {
    $statut = 'non_attribue';
} elseif ($demandesAttribuees < $totalDemandes) {
    $statut = 'partiel';  // "Partiel (X/Y)"
} else {
    $statut = 'complet';
}
```

---

## 🎨 FRONTEND & UI

### Libraries chargées
```html
<!-- CSS -->
Bootstrap 4
Line Awesome icons
Select2 4.1.0
Chart.js 3.9.1
FullCalendar (dashboard)

<!-- JS -->
jQuery 3.x
Bootstrap 4 JS
Select2
Chart.js
Toastr (notifications)
```

### Couleurs projet
```javascript
const colors = {
    primary: '#1E9FF2',      // Bleu principal
    success: '#28D094',      // Vert succès
    danger: '#FF4961',       // Rouge danger
    warning: '#FF9149',      // Orange warning
    info: '#1E9FF2',         // Bleu info
    secondary: '#6c757d'     // Gris secondaire
};
```

### Badges statuts
```blade
<!-- Demandes -->
<span class="badge badge-success">Approuvée</span>
<span class="badge badge-warning">En attente</span>
<span class="badge badge-danger">Contentieux</span>

<!-- Attribution ST -->
<span class="badge badge-secondary">Aucune demande</span>
<span class="badge badge-danger">Non attribué</span>
<span class="badge badge-warning">Partiel (X/Y)</span>
<span class="badge badge-success">Complet</span>
```

---

## 🚀 COMMANDES UTILES

### Développement
```bash
# Navigation
cd /var/www/html/.apps/dmce

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Voir routes
php artisan route:list
php artisan route:list | grep statistiques

# Permissions fichiers
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Redémarrer Apache
sudo systemctl restart apache2

# Logs en temps réel
tail -f storage/logs/laravel.log
```

### Base de données
```bash
# Accès MySQL
mysql -u root -p

# Backup
mysqldump -u root -p dmce > backup_$(date +%Y%m%d).sql

# Restore
mysql -u root -p dmce < backup.sql
```

### Git (si utilisé)
```bash
git status
git add .
git commit -m "feat: description"
git push origin main
```

---

## 🐛 ERREURS COMMUNES & SOLUTIONS

### 1. "Column not found: type_flux"
**Cause** : La table `flux_migratoires` n'a pas de colonne `type_flux`
**Solution** : Utiliser `total_entree` et `total_sortie` + `SUM()`

### 2. "Route not defined"
**Cause** : Routes mal placées ou cache
**Solution** :
```bash
php artisan route:clear
php artisan cache:clear
```

### 3. "Call to undefined method demandesCreees()"
**Cause** : Relation inexistante dans User
**Solution** : Utiliser `demandes()` ou ajouter la relation

### 4. "Undefined variable $users"
**Cause** : Variable non passée au `compact()`
**Solution** : Vérifier le controller `compact('var1', 'var2')`

### 5. Timezone UTC au lieu de +1
**Cause** : MySQL retourne UTC, Laravel applique timezone après
**Solution** : Utiliser `CONVERT_TZ()` dans requêtes SQL brutes

---

## 📦 DÉPENDANCES COMPOSER

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^10.0",
        "barryvdh/laravel-dompdf": "^2.0",
        // ... autres dépendances
    }
}
```

---

## 🔄 WORKFLOW DÉPLOIEMENT

### Test → Production
1. Valider sur instance test
2. Backup base de données production
3. Pull/Copy code vers production
4. Exécuter migrations si nécessaire
5. Clear tous les caches
6. Vérifier logs
7. Test fonctionnel complet

### Checklist avant déploiement
- [ ] Tests fonctionnels OK
- [ ] Pas d'erreurs dans logs
- [ ] Backup DB effectué
- [ ] Routes vérifiées
- [ ] Permissions fichiers OK
- [ ] Variables d'environnement correctes

---

## 📝 NOTES IMPORTANTES

### Points d'attention
1. **FluxMigratoire** : Pas de `type_flux`, utiliser `total_entree`/`total_sortie`
2. **Soit-Transmis** : Relation `user()` pour signataire, pas `users()`
3. **Attribution** : Formule numéro = `'00' + (UUID-1)`
4. **Timezone** : Africa/Brazzaville (UTC+1) dans config
5. **PDF** : Utiliser `stream()` pas `download()` pour affichage navigateur
6. **User relations** : `demandes()` existe, pas `demandesCreees()`

### À ne JAMAIS faire
- ❌ Modifier directement en production sans backup
- ❌ Commit avec credentials dans code
- ❌ Supprimer logs sans archivage
- ❌ Modifier structure DB sans migration
- ❌ Clear cache en pleine utilisation
- ❌ Utiliser `type_flux` dans FluxMigratoire

### Best practices
- ✅ Toujours tester sur instance test
- ✅ Backup avant modifications majeures
- ✅ Versionner avec Git
- ✅ Documenter changements importants
- ✅ Utiliser transactions DB pour opérations critiques
- ✅ Logger erreurs dans storage/logs

---

## 📞 CONTACTS & RÉFÉRENCES

### Documentation Laravel
- https://laravel.com/docs/10.x
- https://laravelshift.com/laravel-code-tips

### Documentation libraries
- Chart.js: https://www.chartjs.org/docs/latest/
- Bootstrap 4: https://getbootstrap.com/docs/4.6/
- Select2: https://select2.org/

---

**Dernière mise à jour** : 2026-04-08
**Développeur principal** : Lieutenant ONDELE
**Projet** : DMCE - République du Congo
