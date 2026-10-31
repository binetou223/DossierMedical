<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentMedicalSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les documents médicaux.
     */
    public function run(): void
    {
        $medecinDiop = User::where('email', 'dr.diop@dossiermedical.sn')->first();
        $medecinDiallo = User::where('email', 'dr.diallo@dossiermedical.sn')->first();
        $medecinNdiaye = User::where('email', 'dr.ndiaye@dossiermedical.sn')->first();

        $patientAmadou = Patient::where('numero_dossier', 'PAT-2026-0001')->first();
        $patientAissatou = Patient::where('numero_dossier', 'PAT-2026-0002')->first();
        $patientMoussa = Patient::where('numero_dossier', 'PAT-2026-0003')->first();
        $patientKhadija = Patient::where('numero_dossier', 'PAT-2026-0004')->first();

        $dossierAmadou = $patientAmadou?->dossierMedical;
        $dossierAissatou = $patientAissatou?->dossierMedical;
        $dossierMoussa = $patientMoussa?->dossierMedical;
        $dossierKhadija = $patientKhadija?->dossierMedical;

        $documents = [
            // Amadou Ba
            [
                'dossier' => $dossierAmadou,
                'data' => [
                    'nom' => 'Bilan_Biologique_Complet_Glycemie_Creatinine.pdf',
                    'type_document' => 'analyse_biologique',
                    'chemin_fichier' => 'documents/dossiers/PAT-2026-0001/bilan_sanguin_2026.pdf',
                    'taille_octets' => 452100,
                    'ajoute_par_user_id' => $medecinDiop?->id,
                ],
            ],

            // Moussa Traoré
            [
                'dossier' => $dossierMoussa,
                'data' => [
                    'nom' => 'Compte_Rendu_Coronarographie_Pose_Stent.pdf',
                    'type_document' => 'rapport_medical',
                    'chemin_fichier' => 'documents/dossiers/PAT-2026-0003/coronarographie_2017.pdf',
                    'taille_octets' => 1245000,
                    'ajoute_par_user_id' => $medecinDiop?->id,
                ],
            ],
            [
                'dossier' => $dossierMoussa,
                'data' => [
                    'nom' => 'Electrocardiogramme_Repos_12_Derivations.pdf',
                    'type_document' => 'imagerie',
                    'chemin_fichier' => 'documents/dossiers/PAT-2026-0003/ecg_repos_recent.pdf',
                    'taille_octets' => 320000,
                    'ajoute_par_user_id' => $medecinDiop?->id,
                ],
            ],

            // Aïssatou Sow
            [
                'dossier' => $dossierAissatou,
                'data' => [
                    'nom' => 'Echographie_Obstetricale_Morphologique_T2.pdf',
                    'type_document' => 'imagerie',
                    'chemin_fichier' => 'documents/dossiers/PAT-2026-0002/echo_morphologique_t2.pdf',
                    'taille_octets' => 2180000,
                    'ajoute_par_user_id' => $medecinNdiaye?->id,
                ],
            ],

            // Khadija Sy
            [
                'dossier' => $dossierKhadija,
                'data' => [
                    'nom' => 'Exploration_Fonctionnelle_Respiratoire_Spirometrie.pdf',
                    'type_document' => 'analyse_fonctionnelle',
                    'chemin_fichier' => 'documents/dossiers/PAT-2026-0004/efr_spirometrie.pdf',
                    'taille_octets' => 580000,
                    'ajoute_par_user_id' => $medecinDiallo?->id,
                ],
            ],
        ];

        foreach ($documents as $item) {
            $dossier = $item['dossier'];
            if (! $dossier) {
                continue;
            }

            $dossier->documents()->firstOrCreate(
                ['nom' => $item['data']['nom']],
                $item['data']
            );
        }
    }
}
