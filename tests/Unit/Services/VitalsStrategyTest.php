<?php

declare(strict_types=1);

use App\Models\Constante;
use App\Services\Strategies\BloodPressureAlertStrategy;
use App\Services\Strategies\GlycemiaAlertStrategy;
use App\Services\Strategies\HeartRateAlertStrategy;
use App\Services\Strategies\ImcAlertStrategy;
use App\Services\Strategies\VitalsAlertManager;

test('ImcAlertStrategy detecte l insuffisance ponderale, le surpoids et l obesite', function () {
    $strategy = new ImcAlertStrategy();

    // Cas 1 : Poids normal
    $cNormal = new Constante(['imc' => 22.0]);
    expect($strategy->evaluate($cNormal))->toBeNull();

    // Cas 2 : Maigreur
    $cMaigre = new Constante(['imc' => 17.5]);
    $resMaigre = $strategy->evaluate($cMaigre);
    expect($resMaigre)->not->toBeNull()
        ->and($resMaigre['type'])->toBe('IMC_MAIGREUR')
        ->and($resMaigre['niveau'])->toBe('warning');

    // Cas 3 : Surpoids
    $cSurpoids = new Constante(['imc' => 27.5]);
    $resSurpoids = $strategy->evaluate($cSurpoids);
    expect($resSurpoids)->not->toBeNull()
        ->and($resSurpoids['type'])->toBe('IMC_SURPOIDS');

    // Cas 4 : Obesite
    $cObesite = new Constante(['imc' => 32.0]);
    $resObesite = $strategy->evaluate($cObesite);
    expect($resObesite)->not->toBeNull()
        ->and($resObesite['type'])->toBe('IMC_OBESITE')
        ->and($resObesite['niveau'])->toBe('danger');
});

test('BloodPressureAlertStrategy detecte la pre-hypertension et les crises hypertensives', function () {
    $strategy = new BloodPressureAlertStrategy();

    // Normotension
    $cNormal = new Constante(['tension_systolique' => 120, 'tension_diastolique' => 80]);
    expect($strategy->evaluate($cNormal))->toBeNull();

    // Pre-hypertension
    $cPreHta = new Constante(['tension_systolique' => 135, 'tension_diastolique' => 85]);
    $resPre = $strategy->evaluate($cPreHta);
    expect($resPre)->not->toBeNull()
        ->and($resPre['type'])->toBe('TENSION_PRE_HYPERTENSION');

    // Crise hypertensive
    $cCrise = new Constante(['tension_systolique' => 190, 'tension_diastolique' => 125]);
    $resCrise = $strategy->evaluate($cCrise);
    expect($resCrise)->not->toBeNull()
        ->and($resCrise['type'])->toBe('TENSION_CRISE_HYPERTENSIVE')
        ->and($resCrise['niveau'])->toBe('danger');
});

test('GlycemiaAlertStrategy detecte hypoglycemie et diabete suspect', function () {
    $strategy = new GlycemiaAlertStrategy();

    $cHypo = new Constante(['glycemie_g_l' => 0.60]);
    expect($strategy->evaluate($cHypo)['type'])->toBe('GLYCEMIE_HYPOGLYCEMIE');

    $cDiabete = new Constante(['glycemie_g_l' => 1.45]);
    expect($strategy->evaluate($cDiabete)['type'])->toBe('GLYCEMIE_DIABETE_SUSPECT');
});

test('VitalsAlertManager agrege toutes les alertes cliniques actives (Strategy Manager)', function () {
    $manager = new VitalsAlertManager();

    // Patient cumulant surpoids, pre-HTA et tachycardie
    $constante = new Constante([
        'poids_kg' => 85,
        'taille_cm' => 170,
        'imc' => 29.4,
        'tension_systolique' => 135,
        'tension_diastolique' => 88,
        'frequence_cardiaque' => 110,
        'glycemie_g_l' => 0.95,
    ]);

    $alerts = $manager->evaluateAll($constante);

    expect($alerts)->toHaveCount(3);
    $types = array_column($alerts, 'type');
    expect($types)->toContain('IMC_SURPOIDS')
        ->and($types)->toContain('TENSION_PRE_HYPERTENSION')
        ->and($types)->toContain('POULS_TACHYCARDIE');
});
