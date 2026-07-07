# 🏫 Système de Gestion Scolaire - C.S. "L'AVENIR D'OR"

Application web full-stack de gestion administrative, pédagogique et financière développée avec **Laravel 11**, **Blade**, **Bootstrap 5** et **Vite**.

## 🚀 Fonctionnalités Clés

- **Sécurisation par rôles** : Accès cloisonné via Middleware (`admin`, `comptable`, `enseignant`) avec redirection dynamique intelligente dès la racine `/`.
- **Gestion du Registre (CRUD)** : Suivi complet des élèves géré de manière fluide par un module React.
- **Comptabilité simplifiée** : Encaissement exclusif des frais de scolarité (écolage) par tranches avec cumul automatique des montants sur un reçu unique (`REC-2026-XXX`).
- **Saisie des Notes** : Calcul automatisé des appréciations pédagogiques officielles du primaire au Togo.
- **Outils de correction** : Boutons de modification sécurisés pour les reçus de scolarité et les notes via des pages dédiées.

## 🧠 Focus Technique : Logique Anti-Doublon & Cumul des Tranches

Pour éviter la prolifération de reçus multiples pour un même élève lors des paiements échelonnés de l'écolage, une logique d'interception et de mise à jour a été implémentée dans le `PaiementController` :

1. **Vérification d'existence** : Avant toute création de ligne, l'application effectue une requête ciblée pour vérifier si l'élève (`eleve_id`) possède déjà un enregistrement actif pour les frais de scolarité (`type_frais = 'scolarite'`).
2. **Cumul Dynamique** : Si un historique existe, le système bloque la création d'un nouveau reçu et utilise l'opérateur d'assignation par addition (`+=`) pour fusionner le nouveau montant versé à l'ancien.
3. **Mise à jour de traçabilité** : La date du versement est actualisée avec la date la plus récente, tandis que la référence de reçu initiale (`REC-2026-XXX`) est conservée pour garantir la continuité comptable.

## ⚛️ Module React (front-end découplé + API Laravel)

Le module **Registre des Élèves** dispose d'une version front-end moderne en **React**, accessible via l'onglet _« Registre des Élèves »_ dans le menu pour les rôles `admin` et `comptable`.

- **Page hôte** : `/eleves` (route Blade minimale qui injecte et charge le bundle React).
- **API JSON** consommée par React (soumise aux mêmes restrictions de rôles) :
    - `GET /api/eleves` — liste des élèves, classes, et prochain matricule.
    - `POST /api/eleves` — enregistrement d'un élève.
    - `PUT /api/eleves/{id}` — modification d'un élève.
    - `DELETE /api/eleves/{id}` — suppression d'un élève (et de ses notes/paiements en cascade).
- **Code source React** : `resources/js/react/` (`main.jsx`, `EleveApp.jsx`).
- **Contrôleur API** : `app/Http/Controllers/Api/EleveApiController.php`.
- **Authentification** : réutilise la session Laravel existante (cookie + jeton CSRF). React et Blade partagent la même connexion de manière transparente.

Les modules Paiements, Notes et Classes restent propulsés par Laravel Blade pour cette version.

## 🛠️ Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- PHP (>= 8.2)
- Composer
- Node.js & NPM
- Un serveur local (Laragon, XAMPP) ou l'utilisation de SQLite.

## 📦 Installation et Configuration

Suivez ces étapes pour installer le projet localement :

### 1. Cloner le projet

```bash
git clone https://github.com
cd gestion-ecole
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Configurer l'environnement

Copiez le fichier d'exemple `.env` :

```bash
cp .env.example .env
```

_Ouvrez le fichier `.env` et configurez vos accès à la base de données (MySQL ou SQLite)._

### 5. Générer la clé d'application

```bash
php artisan key:generate
```

### 6. Migrer la base de données et charger les données de test (Seeding)

Cette commande réinitialise les tables et génère automatiquement 3 comptes de test, 12 classes, 50 fiches élèves, 100 notes et 25 reçus financiers d'écolage propres (sans aucun droit d'inscription) :

```bash
php artisan migrate:fresh --seed
```

## 💻 Lancement de l'Application

Pour démarrer l'application, ouvrez deux terminaux distincts :

**Terminal 1 : Serveur Laravel**

```bash
php artisan serve
```

**Terminal 2 : Compilation des assets avec Vite (Requis pour React)**

```bash
npm run dev
```

Accédez ensuite à l'application via l'URL : `http://127.0.0.1:8000`

## 🔑 Comptes de Test (Démonstration)

Utilisez les identifiants suivants pour tester les différents profils de l'application (mot de passe unique : `password`) :

- **Administrateur (Directeur)** : `admin@ecole.tg` (Accède à la gestion des classes, élèves, notes et paiements)
- **Comptable** : `comptable@ecole.tg` (Accède au registre d'élèves et à la gestion exclusive des frais de scolarité)
- **Enseignant** : `enseignant@ecole.tg` (Accède uniquement au module de saisie et modification des notes)

---

_Projet réalisé par **VIDE Kossi** - Niveau Approfondi en Développement Web Juillet 2026._
