# DBooks

Ce projet est une application web permettant de gérer une bibliothèque personnelle. Vous pouvez ajouter des livres à votre collection en saisissant leur code ISBN.

L'ISBN (International Standard Book Number), est un identifiant unique à chaque livre.

L'ISBN est généralement imprimé sur la page de droits d'auteur ou à l'arrière du livre.

## API

[Schéma simplifié des échanges entre le client, le serveur et les APIs](README/Fonctionnement%20simplifié.JPG)

Afin d'obtenir les différentes données sur un ouvrage, le serveur utilise deux principales interfaces de programmation d'applications (API) :

1. Google Books API : Cette API fournit un accès aux données sur des millions de livres, notamment des informations sur les titres, les auteurs, les descriptions, les couvertures et bien plus encore. En utilisant l'ISBN comme identifiant, notre application interroge cette API pour récupérer des détails sur les livres.

2. Open Library API : L'API Open Library est une autre ressource précieuse pour obtenir des informations sur les livres. Elle offre des données sur les éditions, les auteurs, les couvertures, les descriptions et d'autres détails pertinents. 

En combinant les résultats de ces deux API, notre application peut présenter aux utilisateurs une vue complète des livres recherchés.

Ces API sont intégrées à notre application pour fournir des informations précises et à jour sur les livres, ce qui améliore l'expérience des utilisateurs en leur offrant un accès facile à une vaste collection de données sur les ouvrages.

    La clé d'API Google Books est à renseigner dans la constante key dans /vars/Tool.js

## Base de données

Ce projet utilise une base de données MySQL. L'intégrité des données est notamment assurée par des transactions, des identifiants uniques et des clés étrangères entre les tables.

Un script permettant de créer la base de données est disponible dans le dossier dbSchemes.

    Le nom de la base de données est à renseigner dans vars/default.php

## JavaScript

Le code JavaScript est partitionné en plusieurs classes pour assurer une meilleure maintenance du code.

Les connexions avec les API de Google Books et d'Open Library sont assurées par deux objets.

La représentation d'un livre est également réalisée via une classe.

## Fonctionnalités

1. Ajout / Suppression
[Capture d'écran de l'ajout d'un livre](README/Ajouter.JPG)
2. Visualiser
[Capture d'écran de la fonctionnalité "Visualiser"](README/Visualiser.JPG)
3. Rechercher
[Capture d'écran d'une recherche](README/Visualiser.JPG)