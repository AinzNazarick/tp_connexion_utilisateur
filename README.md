# Symfony - TP Connexion Utilisateur
## Description du projet
Ce projet est une application Symfony avec un système de gestion d'authentification et de rôles pour les utilisateurs. 
Elle permet aux utilisateurs de s'inscrire, de se connecter, et d'accéder à différentes sections du site selon leurs rôles (Utilisateur, Administrateur, Super Administrateur).


## Fonctionnalités

- Inscription utilisateur
- Connexion avec gestion de sessions
- Gestion des rôles (`ROLE_USER`, `ROLE_ADMIN`, `ROLE_SUPER_ADMIN`)
- Zones réservées en fonction des rôles
- Sécurisation des mots de passe

## Documentation des contrôleurs

Ce tableau donne les différents contrôleurs de l'application, les rôles requis pour y avoir accès.

| Nom du contrôller        | URL            | Rôles              | Page Twig                         | Description                                 |
|--------------------------|----------------|--------------------|-----------------------------------|---------------------------------------------|
| **HomeController**       | `/`            | `ROLE_USER`        | `home/index.html.twig`            | Pas d'accueil                               |
| **AdminController**      | `/admin`       | `ROLE_ADMIN`       | `admin/index.html.twig`           | Page réservé pour les administrateur        |
| **SuperAdminController** | `/super-admin` | `ROLE_SUPER_ADMIN` | `superadmin/user/index.html.twig` | Page réservé pour les super-administrateur  |


### Détails des rôles
- **ROLE_USER** : Rôle par défaut pour les utilisateurs connectés.
- **ROLE_ADMIN** : Accès réservé aux administrateurs.
- **ROLE_SUPER_ADMIN** : Accès réservé aux super administrateurs pour les fonctionnalités avancées.
  
## Conclusion

Cette application Symfony met en place un système d'authentification robuste avec gestion des rôles, hashage des mots de passe et accès sécurisé aux différentes sections du site. Les rôles permettent de définir des permissions précises pour chaque utilisateur, tout en assurant une hiérarchisation claire entre les rôles d'utilisateur, d'administrateur et de super-administrateur.