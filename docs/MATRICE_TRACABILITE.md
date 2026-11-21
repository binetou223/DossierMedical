# Matrice de Traçabilité Académique & Technique (Annexe du Mémoire)

**Projet :** Étude et mise en place d'un Système de Gestion de Dossier Médical Électronique (DME)  
**Étudiante :** Binetou (IG3 - Licence en Gestion des Systèmes d'Information)  
**Encadreur :** M. Ahmed Ben Sidy Bouya Seye  

Cette matrice établit la correspondance biunivoque exigée au **Chapitre 7 & 8 du plan directeur** :  
$$\text{Besoin Métier} \longrightarrow \text{Cas d'Utilisation UML} \longrightarrow \text{DTO / Pattern} \longrightarrow \text{Service / Repository} \longrightarrow \text{Écran Vue / Inertia} \longrightarrow \text{Test}$$

---

## Tableau de Traçabilité de Bout en Bout

| Réf. Besoin | Description du Besoin | Cas d'Utilisation (UML) | Couche Données (DTO / Builder) | Couche Métier & Persistance (Service / Repository / Policy) | Interface Utilisateur (Vue.js 3 / Inertia) | Couverture de Test (Pest / PHPUnit) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BF-01** | Inscription sécurisée d'un patient et création du dossier médical | `CU01 : Enregistrer Patient` | `CreatePatientDTO`<br>`CreatePatientDTOBuilder` | `PatientService::registerPatient()`<br>`PatientRepositoryInterface`<br>`PatientPolicy` | `Patients/Create.vue`<br>`Patients/Show.vue` | `DTOAndBuilderTest`<br>`RepositoryPatternTest` |
| **BF-02** | Demande de rendez-vous en ligne avec détection de conflits | `CU02 : Demander Rendez-vous` | `CreateRendezVousDTO`<br>`CreateRendezVousDTOBuilder` | `RendezVousService::scheduleAppointment()`<br>`RendezVousRepositoryInterface`<br>`RendezVousPolicy` | `RendezVous/Create.vue` | `DTOAndBuilderTest`<br>`RepositoryPatternTest` |
| **BF-03** | Validation / Confirmation du rendez-vous par la secrétaire | `CU03 : Valider Rendez-vous` | `UpdateRendezVousStatusDTO` | `RendezVousService::confirmAppointment()`<br>`EnsureUserHasRole` (secretaire/medecin) | `RendezVous/Index.vue` | `RepositoryPatternTest` |
| **BF-04** | Enregistrement des constantes vitales & alertes cliniques | `CU04 : Saisir Constantes` | `CreateConstanteDTO`<br>`CreateConstanteDTOBuilder` | `DossierMedicalService::recordConstantes()`<br>`VitalsAlertManager` (Strategy Pattern)<br>`DossierMedicalPolicy` | `DossierMedical/Show.vue`<br>(Onglet Constantes) | `DTOAndBuilderTest`<br>`RepositoryPatternTest` |
| **BF-05** | Réalisation de la consultation médicale et ordonnance sécurisée | `CU05 : Réaliser Consultation` | `CreateConsultationDTO`<br>`CreateOrdonnanceDTO`<br>`PrescriptionItemDTO` | `ConsultationService::conductConsultation()`<br>`ConsultationRepositoryInterface`<br>`ConsultationPolicy` | `Consultations/Create.vue`<br>`Consultations/Show.vue` | `DTOAndBuilderTest`<br>`RepositoryPatternTest` |
| **BF-06** | Impression / Téléchargement officiel de l'ordonnance | `CU06 : Imprimer Ordonnance` | `Consultation` & `Ordonnance` | `ConsultationController::printOrdonnance()`<br>`AuditService::logAccess(IMPRESSION_ORDONNANCE)` | `Consultations/PrintOrdonnance.vue` | `RepositoryPatternTest` |
| **BF-07** | Consultation de l'historique médical par le patient | `CU07 : Consulter Mon Dossier` | `DossierMedical` | `DossierMedicalService::getDossierByPatient()`<br>`DossierMedicalPolicy` (Secret Médical) | `DossierMedical/Show.vue`<br>(Vue Espace Patient) | `RepositoryPatternTest` |
| **BF-08** | Gestion des utilisateurs et affectation des rôles par l'administrateur | `CU08 : Administrer Comptes` | `User` & `UserRole` | `Admin\UserController`<br>`EnsureUserHasRole(admin)` | `Admin/Users/Index.vue` | `AuthenticationTest` |
| **BF-09** | Journalisation légale immuable et traçabilité des données de santé | `CU09 : Superviser Audit CDP` | `JournalAudit` & `AuditAction` | `AuditService::logAccess()`<br>`JournalAuditRepositoryInterface`<br>`JournalAuditPolicy` | `Admin/Audit/Index.vue` | `RepositoryPatternTest` |
| **BF-10** | Indicateurs de performance et statistiques hospitalières | `CU10 : Visualiser KPIs` | Agrégats Eloquent | `Admin\AdminDashboardController::index()` | `Admin/Dashboard.vue` | `DashboardTest` |

---

## Synthèse de la Couverture
- **100% des cas d'utilisation** sont couverts par un composant d'architecture précis.
- Aucun contrôleur n'effectue de requête SQL directe (respect strict du **Repository Pattern** et du **DIP**).
- Aucune constante n'est persistée sans être évaluée par le **Strategy Pattern** pour les alertes de santé.
- La traçabilité **CDP Sénégal** est nativement greffée sur chaque action sensible.
