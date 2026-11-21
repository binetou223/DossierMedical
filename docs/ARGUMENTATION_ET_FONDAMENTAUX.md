# Argumentation Technique & Maîtrise des Fondamentaux (Guide pour la Soutenance)

**Mémoire :** Étude et mise en place d'un Système de Gestion de Dossier Médical Électronique (DME)  
**Étudiante :** Binetou (IG3 - Licence en Gestion des Systèmes d'Information)  
**Encadreur :** M. Ahmed Ben Sidy Bouya Seye  

Ce guide récapitule les justifications technologiques et conceptuelles indispensables pour la rédaction du **Chapitre 6** et la soutenance orale.

---

## 1. Justification Argumentée des Choix Technologiques

### 1.1 Laravel face à un MVC Natif (PHP Vanilla)
- **MVC Natif :** Nécessite de réécrire le routeur, le conteneur de services (IoC), la gestion de session, la couche ORM et la sécurité. Risque élevé de failles de sécurité (injections SQL, failles CSRF, XSS).
- **Laravel :** Framework d'entreprise mature offrant un conteneur d'injection de dépendances natif (fondement du principe **DIP** de SOLID), un ORM robuste (Eloquent) basé sur **PDO**, et une architecture modulaire éprouvée. Il permet de concentrer l'effort sur la logique métier de santé plutôt que sur l'infrastructure de bas niveau.

### 1.2 Inertia.js face à une SPA classique (Vue.js + API REST découplée)
- **SPA classique + API REST :** Exige une double déclaration des routes (backend Laravel + frontend Vue Router), une gestion complexe de jetons JWT / cookies cross-origin, et une duplication de la validation côté client et serveur.
- **Inertia.js :** Fonctionne comme un adaptateur moderne entre Laravel et Vue.js 3 sans construire d'API REST intermédiaire dédiée. Les contrôleurs Laravel retournent directement des composants Vue (`Inertia::render()`) tout en conservant le cycle de routage backend traditionnel de Laravel. Le développement est 2 fois plus rapide, sécurisé par sessions HTTP natives et sans friction d'authentification.

### 1.3 Vue.js 3 face à Blade / Livewire ou React
- **Blade / Livewire :** Moins adapté aux interfaces médicales hautement interactives (ex: ajout dynamique de lignes de médicaments sur une ordonnance, alertes visuelles instantanées).
- **React :** Syntaxe JSX plus verbeuse, courbe d'apprentissage plus abrupte.
- **Vue.js 3 (Composition API & TypeScript) :** Séparation claire `<script setup>`, `<template>`, `<style>`. Réactivité native et typage strict des props idéal pour la manipulation de constantes vitales sensibles.

### 1.4 Eloquent ORM face à PDO Direct
- **PDO direct :** Nécessite l'écriture manuelle de requêtes SQL textuelles `SELECT * FROM ...`, sujettes aux fautes de frappe et difficilement maintenables lors de l'évolution du schéma.
- **Eloquent ORM :** Couche d'abstraction orientée objet encapsulant PDO. Eloquent génère automatiquement des **requêtes préparées PDO** avec liaison de paramètres (*parameter binding*), éliminant mathématiquement tout risque d'injection SQL.

---

## 2. Fondamentaux sous le Framework

### 2.1 Le Cycle Requête-Réponse HTTP dans Laravel
1. **Point d'entrée unique :** La requête HTTP arrive sur `public/index.php`.
2. **Chargement de l'Application :** `bootstrap/app.php` initialise le noyau HTTP et configure les Service Providers (`RepositoryServiceProvider`, `BusinessServiceProvider`).
3. **Pipeline des Middlewares (Chain of Responsibility) :** La requête traverse successivement la protection CSRF, le chiffrement des cookies, l'authentification et le middleware de contrôle des rôles `EnsureUserHasRole`.
4. **Routage :** `routes/web.php` résout le contrôleur cible et exécute la `FormRequest` pour validation.
5. **Couche Métier :** Le contrôleur adapte les données en DTO, invoque le Service approprié qui dialogue avec les Repositories.
6. **Réponse Inertia :** Le contrôleur retourne une réponse JSON Inertia hydratant la vue Vue.js 3 correspondante.

### 2.2 Requêtes Préparées PDO générées par Eloquent
Lorsqu'Eloquent exécute une recherche comme :
```php
Patient::where('numero_dossier', $numeroDossier)->first();
```
Il ne concatène **jamais** la chaîne dans le SQL. Sous le capot, PDO effectue deux étapes distinctes :
1. **Compilation du plan d'exécution :** `SELECT * FROM patients WHERE numero_dossier = ?` (envoyé au moteur MySQL).
2. **Transmission des données séparée :** Le paramètre `$numeroDossier` est envoyé comme simple donnée binaire via `PDOStatement::execute([$numeroDossier])`.
Aucune instruction SQL hostile présente dans `$numeroDossier` ne peut être exécutée.

### 2.3 Protection CSRF (Cross-Site Request Forgery)
- Chaque session active génère un jeton cryptographique aléatoire unique `_token`.
- Pour chaque requête modifiante (`POST`, `PUT`, `PATCH`, `DELETE`), le middleware `VerifyCsrfToken` compare le jeton transmis dans les en-têtes HTTP (`X-XSRF-TOKEN` transmis automatiquement par Axios/Inertia) avec celui présent en session. Tout appel forgé depuis un site malveillant tiers est immédiatement rejeté (HTTP 419).

### 2.4 Hachage Cryptographique des Mots de Passe
- Les mots de passe ne sont jamais stockés en clair.
- Laravel utilise l'algorithme robuste **Bcrypt** (ou **Argon2id**) avec un facteur de coût élevé (*work factor* / salage dynamique). Même en cas de fuite de la base de données, les attaques par dictionnaires et tables arc-en-ciel (*rainbow tables*) sont rendues inopérantes.

### 2.5 Secret Médical & Cadre Légal Sénégalais (Loi CDP)
- La loi sénégalaise n° 2008-12 relative à la protection des données à caractère personnel impose une stricte confidentialité sur les données de santé.
- Dans le DME :
  - **Cloisonnement des rôles (Policies) :** Un patient n'accède qu'à son dossier ; les secrétaires sont cantonnées aux flux administratifs ; les administrateurs n'ont pas accès aux consultations.
  - **Traçabilité immuable (JournalAudit) :** Chaque ouverture de dossier, modification ou impression d'ordonnance est horodatée avec l'identifiant de l'agent, l'IP et l'action.
