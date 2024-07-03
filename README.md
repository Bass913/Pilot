# Projet de Réservation de Prestations

Créé par :

-   Rafael (rafael-tdp)
-   Taslima (Taslima-Ahamed-Mze)
-   Bastien (Bass913)
-   Asma (asma1mokeddes)

## Table des matières

-   [Introduction](#introduction)
-   [Comptes de Test](#comptes-de-test)
-   [Fonctionnalités](#fonctionnalités)
    -   [Non connecté](#non-connecté)
    -   [Utilisateur](#utilisateur)
    -   [Employé](#employé)
    -   [Admin](#admin)
    -   [SuperAdmin](#superadmin)
-   [Installation](#installation)
-   [Utilisation](#utilisation)

## Introduction

Ce projet est une application de réservation de prestations qui permet aux utilisateurs de s'inscrire, réserver des prestations, et aux employés de gérer leurs plannings et réservations. Les administrateurs peuvent gérer les établissements, les prestations, et les employés, tandis que les superadmins ont des capacités étendues de gestion des utilisateurs et des demandes de prestataires.

## Comptes de Test

-   **Utilisateur**
    -   Email: user@user.fr
    -   Mot de passe: test
-   **Employé**
    -   Email: employee@employee.fr
    -   Mot de passe: test
-   **Admin**
    -   Email: admin@admin.fr
    -   Mot de passe: test
-   **SuperAdmin**
    -   Email: super@admin.fr
    -   Mot de passe: test

## Fonctionnalités

### Non connecté

-   **S'inscrire**
-   **Faire une demande pour devenir prestataire**
    -   Envoi d'un email à la soumission de la demande
-   **Se connecter**
-   **Chercher des entreprises**
    -   Filtrer par ville, prestation, spécialité...
-   **Réserver une prestation**
    -   Une modale de demande de connexion s'affiche si l'utilisateur n'est pas connecté

### Utilisateur

-   **Modification du compte**
-   **Annuler une réservation**
-   **Reporter une réservation**
-   **Ajouter un avis**
-   **Réserver une prestation**
    -   Envoi d'un email à la réservation d'une prestation
-   **Rappel par SMS**

### Employé

#### Planning

-   **Afficher son planning**
-   **Ajouter une indisponibilité**

#### Réservations

-   **Afficher les réservations**
-   **Modifier les réservations**
-   **Supprimer les réservations**

### Admin

#### Accueil

-   **Afficher les statistiques**

#### Établissements

-   **Afficher les établissements**
-   **Ajouter un établissement**
-   **Supprimer un établissement**
-   **Modifier un établissement**

#### Prestations

-   **Afficher les prestations**
-   **Ajouter une prestation**
-   **Modifier une prestation**
-   **Supprimer une prestation**

#### Employés

-   **Afficher les employés**
-   **Ajouter un employé**
-   **Modifier un employé**
-   **Supprimer un employé**

#### Planning

-   **Afficher le planning des employés**
-   **Ajouter une indisponibilité pour un employé**

#### Réservations

-   **Afficher les réservations**
-   **Modifier les réservations**
-   **Supprimer les réservations**

### SuperAdmin

#### Accueil

-   **Afficher les statistiques**

#### Établissements

-   **Afficher les établissements**
-   **Supprimer un établissement**
-   **Modifier un établissement**

#### Prestations

-   **Afficher les prestations**
-   **Modifier une prestation**
-   **Supprimer une prestation**

#### Employés

-   **Afficher les employés**
-   **Modifier un employé**
-   **Supprimer un employé**

#### Planning

-   **Afficher le planning des utilisateurs**
-   **Ajouter une indisponibilité pour un utilisateur**

#### Réservations

-   **Afficher les réservations**
-   **Modifier les réservations**
-   **Supprimer les réservations**

#### Demandes

-   **Afficher les demandes**
-   **Afficher le Kbis d'une demande**
-   **Accepter une demande**
    -   Envoi d'un email à la validation de la demande
-   **Refuser une demande**

#### Utilisateurs

-   **Afficher les utilisateurs**
-   **Modifier un utilisateur**
-   **Supprimer un utilisateur**

## Installation

Pour installer et exécuter l'application localement, suivez ces étapes :

1. Clonez le dépôt :
    ```sh
    git clone https://github.com/Bass913/Pilot.git
    ```
2. Naviguez dans le répertoire du projet :
    ```sh
    cd Pilot
    ```
3. Créez l'image docker
    ```sh
    docker compose build
    ```
4. Démarrez l'application :
    ```sh
    docker compose up
    ```

## Utilisation

Une fois l'application démarrée, ouvrez votre navigateur et accédez à `http://localhost` pour utiliser l'application.
