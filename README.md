# Distributeur Picard - Projet étudiant

Application web fullstack pour distributeur automatique Picard avec Symfony + Vue.js

## Install

### Backend (Symfony) :
cd backend
composer install --no-scripts
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony server:start

### Frontend (Vue.js) :
cd frontend
npm install
npm run dev

## URLs
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api

## Features

- Catalogue produits Picard
- Panier avec quantités
- Inscription/connexion
- Commande avec paiement simulé
- Points de fidélité (1pt = 10€)
- Historique des commandes

## Test

1. Créer un compte
2. Ajouter des produits au panier
3. Commander avec une fausse carte (ex: 1234567890123456)
4. Voir l'historique dans le profil

## Structure

backend/          # API Symfony
- src/Entity/     # User, Product, Order
- src/Controller/ # Auth, Product, Order
- fixtures/       # Données de test

frontend/         # Interface Vue.js
- src/views/      # Pages
- src/components/ # Composants
- src/store/      # État global

## DB

SQLite (automatique) avec produits Picard pré-chargés

## Notes

- Pas de vrai paiement (simulation)
- Auth simple sans JWT pour éviter les problèmes
- Images produits par défaut
- Points fidélité calculés automatiquement
