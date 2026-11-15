<?php

declare(strict_types=1);

use App\DTOs\Constante\CreateConstanteDTO;
use App\DTOs\Consultation\CreateConsultationDTO;
use App\DTOs\DocumentMedical\UploadDocumentMedicalDTO;
use App\DTOs\DossierMedical\UpdateDossierMedicalDTO;
use App\DTOs\Ordonnance\CreateOrdonnanceDTO;
use App\DTOs\Ordonnance\PrescriptionItemDTO;
use App\DTOs\Patient\CreatePatientDTO;
use App\DTOs\RendezVous\CreateRendezVousDTO;
use App\DTOs\RendezVous\UpdateRendezVousStatusDTO;
use App\Enums\AppointmentStatus;
use App\Enums\BloodGroup;
use Carbon\Carbon;

test('CreatePatientDTOBuilder construit un DTO patient valide', function () {
    $dto = CreatePatientDTO::builder()
        ->withName('Aminata Diallo')
        ->withEmail('aminata.diallo@example.com')
        ->withPassword('Secur1ty!2026')
        ->withTelephone('+221771234567')
        ->withAdresse('Dakar, Sacré-Cœur 3')
        ->bornOn('1995-04-12')
        ->withGenre('F')
        ->withGroupeSanguin(BloodGroup::O_POS)
        ->withProfession('Enseignante')
        ->withContactUrgence('Moussa Diallo', '+221770001122')
        ->build();

    expect($dto)->toBeInstanceOf(CreatePatientDTO::class)
        ->and($dto->name)->toBe('Aminata Diallo')
        ->and($dto->email)->toBe('aminata.diallo@example.com')
        ->and($dto->groupeSanguin)->toBe(BloodGroup::O_POS)
        ->and($dto->dateNaissance?->toDateString())->toBe('1995-04-12')
        ->and($dto->genre)->toBe('F');

    $array = $dto->toArray();
    expect($array['groupe_sanguin'])->toBe('O+')
        ->and($array['contact_urgence_nom'])->toBe('Moussa Diallo');
});

test('CreatePatientDTOBuilder lève une exception si les champs obligatoires manquent', function () {
    CreatePatientDTO::builder()
        ->withName('Test')
        ->build();
})->throws(InvalidArgumentException::class);

test('CreateRendezVousDTOBuilder applique les valeurs par défaut et calcule date_fin', function () {
    $debut = Carbon::parse('2026-11-20 10:00:00');

    $dto = CreateRendezVousDTO::builder()
        ->forPatient(1)
        ->withMedecin(2)
        ->inService(3)
        ->startsAt($debut)
        ->withMotif('Consultation suivi hypertension')
        ->build();

    expect($dto)->toBeInstanceOf(CreateRendezVousDTO::class)
        ->and($dto->patientId)->toBe(1)
        ->and($dto->medecinId)->toBe(2)
        ->and($dto->serviceId)->toBe(3)
        ->and($dto->statut)->toBe(AppointmentStatus::EN_ATTENTE)
        ->and($dto->dateHeureFin->equalTo($debut->copy()->addMinutes(30)))->toBeTrue();

    $array = $dto->toArray();
    expect($array['statut'])->toBe(AppointmentStatus::EN_ATTENTE->value)
        ->and($array['motif'])->toBe('Consultation suivi hypertension');
});

test('UpdateRendezVousStatusDTOBuilder valide l obligation du motif lors d une annulation', function () {
    UpdateRendezVousStatusDTO::builder()
        ->withStatut(AppointmentStatus::ANNULE)
        ->build();
})->throws(InvalidArgumentException::class);

test('UpdateRendezVousStatusDTOBuilder construit un statut d annulation avec motif', function () {
    $dto = UpdateRendezVousStatusDTO::builder()
        ->withStatut(AppointmentStatus::ANNULE)
        ->withMotifAnnulation('Impossibilité de se déplacer')
        ->build();

    expect($dto->statut)->toBe(AppointmentStatus::ANNULE)
        ->and($dto->motifAnnulation)->toBe('Impossibilité de se déplacer');
});

test('CreateConstanteDTOBuilder construit un DTO pour la prise de constantes', function () {
    $dto = CreateConstanteDTO::builder()
        ->forDossier(5)
        ->takenBy(2)
        ->withPoids(68.5)
        ->withTaille(172.0)
        ->withTension(120, 80)
        ->withFrequenceCardiaque(72)
        ->withTemperature(36.8)
        ->withObservations('État général stable')
        ->build();

    expect($dto)->toBeInstanceOf(CreateConstanteDTO::class)
        ->and($dto->dossierMedicalId)->toBe(5)
        ->and($dto->poidsKg)->toBe(68.5)
        ->and($dto->tensionSystolique)->toBe(120)
        ->and($dto->tensionDiastolique)->toBe(80);

    $array = $dto->toArray();
    expect($array['poids_kg'])->toBe(68.5)
        ->and($array['tension_systolique'])->toBe(120);
});

test('CreateConsultationDTOBuilder construit et clôture une consultation médicale', function () {
    $dto = CreateConsultationDTO::builder()
        ->forDossier(5)
        ->conductedBy(2)
        ->withMotif('Céphalées persistantes')
        ->withDiagnostic('Migraine avec aura')
        ->withExamenPhysique('Examen neuro normal, constantes ok')
        ->withConduiteATenir('Repos et triptans')
        ->cloturer()
        ->build();

    expect($dto)->toBeInstanceOf(CreateConsultationDTO::class)
        ->and($dto->estCloturee)->toBeTrue()
        ->and($dto->clotureeLe)->not->toBeNull()
        ->and($dto->diagnostic)->toBe('Migraine avec aura');
});

test('CreateOrdonnanceDTOBuilder construit une ordonnance avec plusieurs prescriptions imbriquées', function () {
    $item1 = PrescriptionItemDTO::builder()
        ->medicament('Amoxicilline')
        ->dosage('1g')
        ->forme('Gélule')
        ->posologie('1 gélule matin et soir')
        ->duree('7 jours')
        ->build();

    $item2 = PrescriptionItemDTO::builder()
        ->medicament('Paracétamol')
        ->dosage('1000mg')
        ->forme('Comprimé')
        ->posologie('1 comprimé si douleur max 3/jour')
        ->duree('5 jours')
        ->build();

    $dto = CreateOrdonnanceDTO::builder()
        ->forConsultation(12)
        ->withReference('ORD-2026-0042')
        ->withInstructions('Prendre au cours des repas')
        ->addPrescription($item1)
        ->addPrescription($item2)
        ->build();

    expect($dto)->toBeInstanceOf(CreateOrdonnanceDTO::class)
        ->and($dto->consultationId)->toBe(12)
        ->and($dto->numeroReference)->toBe('ORD-2026-0042')
        ->and($dto->prescriptions)->toHaveCount(2)
        ->and($dto->prescriptions[0]->nomMedicament)->toBe('Amoxicilline')
        ->and($dto->prescriptions[1]->nomMedicament)->toBe('Paracétamol');

    $array = $dto->toArray();
    expect($array['prescriptions'])->toHaveCount(2)
        ->and($array['prescriptions'][0]['nom_medicament'])->toBe('Amoxicilline');
});

test('UpdateDossierMedicalDTOBuilder met à jour les antécédents et allergies', function () {
    $dto = UpdateDossierMedicalDTO::builder()
        ->withAllergies('Pénicilline, Arachides')
        ->withAntecedentsPersonnels('Appendicectomie en 2018')
        ->withRegimeAlimentaire('Hypo-sodé')
        ->build();

    expect($dto)->toBeInstanceOf(UpdateDossierMedicalDTO::class)
        ->and($dto->allergies)->toBe('Pénicilline, Arachides')
        ->and($dto->antecedentsPersonnels)->toBe('Appendicectomie en 2018')
        ->and($dto->regimeAlimentaire)->toBe('Hypo-sodé');

    $array = $dto->toArray();
    expect($array)->toHaveKey('allergies')
        ->and($array)->toHaveKey('antecedents_personnels')
        ->and($array)->not->toHaveKey('notes_confidentielles');
});

test('UploadDocumentMedicalDTOBuilder valide les informations du fichier médical', function () {
    $dto = UploadDocumentMedicalDTO::builder()
        ->forDossier(3)
        ->withNom('Radiographie thoracique')
        ->ofType('radiographie')
        ->storedAt('/documents/radio_001.pdf')
        ->withSize(2048500)
        ->uploadedBy(2)
        ->build();

    expect($dto)->toBeInstanceOf(UploadDocumentMedicalDTO::class)
        ->and($dto->nom)->toBe('Radiographie thoracique')
        ->and($dto->typeDocument)->toBe('radiographie')
        ->and($dto->cheminFichier)->toBe('/documents/radio_001.pdf')
        ->and($dto->tailleOctets)->toBe(2048500);
});
