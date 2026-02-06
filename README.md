# Mini Blog - Application Symfony

Une application de blog développée avec Symfony 7.4, permettant la gestion d'articles, de commentaires et d'utilisateurs avec un système d'authentification et d'autorisation.

## 📋 Table des matières

- [Caractéristiques](#caractéristiques)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Structure de la base de données](#structure-de-la-base-de-données)
- [Fonctionnalités](#fonctionnalités)
- [Rôles et permissions](#rôles-et-permissions)
- [Architecture](#architecture)
- [Technologies utilisées](#technologies-utilisées)

## ✨ Caractéristiques

- 📝 Gestion complète des articles de blog
- 💬 Système de commentaires avec modération
- 👥 Gestion des utilisateurs
- 🔐 Authentification et autorisation
- 🏷️ Catégorisation des articles
- 📱 Interface responsive et moderne
- 🎨 Design épuré avec navigation intuitive

## 🔧 Prérequis

- PHP >= 8.2
- Composer
- MySQL ou MariaDB
- Serveur web (Apache/Nginx) ou Symfony CLI
- Node.js et npm (optionnel, pour les assets)

## 🚀 Installation

1. **Cloner le projet**
```bash
cd /Applications/MAMP/htdocs/symfony/tp-mini-blog
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**

Créez un fichier `.env.local` à la racine du projet et configurez votre connexion à la base de données :

```env
DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/mini_blog?serverVersion=8.0"
```

4. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Charger les fixtures (optionnel)**
```bash
php bin/console doctrine:fixtures:load
```

6. **Lancer le serveur de développement**
```bash
symfony server:start
# ou
php -S localhost:8000 -t public/
```

L'application sera accessible sur `http://localhost:8000`

## 🗄️ Structure de la base de données

### Entités principales

#### **User** (Utilisateur)
- `id` : Identifiant unique
- `email` : Adresse email (unique)
- `password` : Mot de passe hashé
- `firstName` : Prénom
- `lastName` : Nom
- `profilePicture` : Photo de profil
- `roles` : Rôles (ROLE_USER, ROLE_ADMIN)
- `isActive` : Statut actif/inactif
- `createdAt` : Date de création
- `updatedAt` : Date de dernière modification

#### **Post** (Article)
- `id` : Identifiant unique
- `title` : Titre de l'article (max 150 caractères)
- `content` : Contenu de l'article (texte)
- `picture` : Image de l'article
- `publishedAt` : Date de publication
- `author` : Relation avec User (auteur)
- `category` : Relation avec Category

#### **Category** (Catégorie)
- `id` : Identifiant unique
- `name` : Nom de la catégorie (max 100 caractères)
- `description` : Description de la catégorie

#### **Comment** (Commentaire)
- `id` : Identifiant unique
- `content` : Contenu du commentaire
- `createdAt` : Date de création
- `status` : Statut du commentaire (approuvé, en attente, rejeté)
- `user` : Relation avec User (auteur)
- `post` : Relation avec Post (article)

### Relations

- Un **User** peut écrire plusieurs **Posts** (1:N)
- Un **Post** appartient à une **Category** (N:1)
- Un **Post** peut avoir plusieurs **Comments** (1:N)
- Un **User** peut écrire plusieurs **Comments** (1:N)

## 🎯 Fonctionnalités

### Zone publique

#### Page d'accueil (`/`)
- Affichage de tous les articles publiés
- Tri par date de publication (plus récents en premier)
- Navigation vers les détails d'un article

#### Détails d'un article
- Affichage complet de l'article
- Informations sur l'auteur et la catégorie
- Liste des commentaires approuvés
- Formulaire de commentaire (pour utilisateurs connectés)

#### Authentification
- **Inscription** (`/register`) : Création de compte utilisateur
- **Connexion** (`/login`) : Authentification
- **Déconnexion** (`/logout`)

### Zone d'administration (ROLE_ADMIN uniquement)

#### Gestion des articles (`/post`)
- **Liste des articles** : Vue d'ensemble de tous les articles
- **Créer un article** : Formulaire de création avec :
  - Titre
  - Contenu
  - Image
  - Auteur (sélection par nom)
  - Catégorie (sélection par nom)
- **Modifier un article** : Édition des informations
- **Supprimer un article** : Suppression avec confirmation

#### Gestion des utilisateurs (`/admin/user`)
- **Liste des utilisateurs** : Affichage de tous les utilisateurs
- **Détails d'un utilisateur** : Informations complètes
- **Activer/Désactiver un utilisateur** : Toggle du statut

#### Gestion des commentaires (`/admin/comment`)
- **Liste des commentaires** : Vue d'ensemble avec statut
- **Modération** : Approuver, rejeter ou supprimer
- **Filtrage** : Par statut (en attente, approuvé, rejeté)

#### Gestion des catégories (`/category`)
- **Liste des catégories**
- **Créer une catégorie**
- **Modifier une catégorie**
- **Supprimer une catégorie**

### Navigation

Le header affiche des boutons différents selon le rôle de l'utilisateur :

**Visiteur non connecté :**
- Bouton "Connexion"

**Utilisateur connecté (ROLE_USER) :**
- Nom de l'utilisateur
- Bouton "Déconnexion"

**Administrateur (ROLE_ADMIN) :**
- Nom de l'utilisateur
- Bouton "Créer un article"
- Bouton "Articles"
- Bouton "Utilisateurs"
- Bouton "Commentaires"
- Bouton "Déconnexion"

L'onglet actif est mis en évidence avec un fond gris foncé pour faciliter la navigation.

## 🔒 Rôles et permissions

### ROLE_USER
- Consulter les articles
- Publier des commentaires
- Gérer son profil

### ROLE_ADMIN
- Toutes les permissions de ROLE_USER
- Créer, modifier et supprimer des articles
- Gérer les utilisateurs (activer/désactiver)
- Modérer les commentaires
- Gérer les catégories

## 🏗️ Architecture

### Structure des dossiers

```
tp-mini-blog/
├── assets/              # Assets frontend (CSS, JS)
│   └── styles/
│       └── app.css
├── config/              # Configuration Symfony
├── migrations/          # Migrations de base de données
├── public/              # Point d'entrée web
│   └── index.php
├── src/
│   ├── Controller/      # Contrôleurs
│   │   ├── AdminCommentController.php
│   │   ├── CategoryController.php
│   │   ├── CommentController.php
│   │   ├── HomeController.php
│   │   ├── PostController.php
│   │   ├── PublicPostController.php
│   │   ├── RegistrationController.php
│   │   ├── SecurityController.php
│   │   └── UserController.php
│   ├── Entity/          # Entités Doctrine
│   │   ├── Category.php
│   │   ├── Comment.php
│   │   ├── Post.php
│   │   └── User.php
│   ├── Form/            # Formulaires Symfony
│   │   ├── CategoryType.php
│   │   ├── PostType.php
│   │   └── UserType.php
│   └── Repository/      # Repositories Doctrine
├── templates/           # Templates Twig
│   ├── base.html.twig   # Template de base
│   ├── category/
│   ├── comment/
│   ├── home/
│   ├── post/
│   ├── registration/
│   ├── security/
│   └── user/
└── var/                 # Cache et logs
```

### Patterns utilisés

- **MVC** : Architecture Modèle-Vue-Contrôleur
- **Repository Pattern** : Abstraction de l'accès aux données
- **Form Type** : Gestion des formulaires avec validation
- **Twig** : Moteur de templates pour les vues
- **Doctrine ORM** : Mapping objet-relationnel
- **Security** : Système d'authentification et d'autorisation Symfony

## 🛠️ Technologies utilisées

### Backend
- **Symfony 7.4** : Framework PHP
- **Doctrine ORM 3.6** : Gestion de la base de données
- **Twig** : Moteur de templates
- **Symfony Security** : Authentification et autorisation
- **Symfony Form** : Gestion des formulaires

### Frontend
- **HTML5/CSS3** : Structure et style
- **Twig** : Templates dynamiques
- **Design personnalisé** : Interface moderne et responsive

### Base de données
- **MySQL/MariaDB** : Système de gestion de base de données

### Outils de développement
- **Symfony Maker Bundle** : Génération de code
- **Doctrine Migrations** : Gestion des migrations
- **Doctrine Fixtures** : Données de test
- **Symfony Debug Bundle** : Outils de débogage
- **PHPUnit** : Tests unitaires

## 📝 Utilisation

### Créer un article

1. Connectez-vous avec un compte administrateur
2. Cliquez sur "Créer un article" dans le header
3. Remplissez le formulaire :
   - Titre de l'article
   - Contenu
   - Sélectionnez un auteur (nom complet)
   - Sélectionnez une catégorie
   - Ajoutez une image
4. Validez le formulaire

### Modérer les commentaires

1. Accédez à la section "Commentaires"
2. Visualisez les commentaires avec leur statut
3. Approuvez, rejetez ou supprimez les commentaires selon les besoins

### Gérer les utilisateurs

1. Accédez à la section "Utilisateurs"
2. Consultez la liste des utilisateurs inscrits
3. Activez ou désactivez un compte utilisateur en cliquant sur le bouton correspondant

## 🎨 Personnalisation

### Modifier les styles

Les styles principaux sont définis dans `templates/base.html.twig` dans la section `{% block stylesheets %}`.

Vous pouvez également ajouter des styles personnalisés dans `assets/styles/app.css`.

### Modifier le header

Le header est intégré dans `templates/base.html.twig`. Vous pouvez modifier :
- La navigation
- Les boutons
- Le logo
- Les styles

## 📄 Licence

Projet propriétaire - Usage éducatif

## 👨‍💻 Auteur

Développé dans le cadre d'un projet Symfony
