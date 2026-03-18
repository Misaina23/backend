<?php

namespace Database\Seeders;

use App\Models\Inspection;
use Illuminate\Database\Seeder;

class InspectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspections = [
            [
                'code_producteur' => 'PRD-001',
                'nom_producteur' => 'Ramanatefa Jean',
                'code_unique_parcelle' => 'PAR-001',
                'date_inspection' => '2025-01-10',
                'observations' => 'Parcelle bien entretenue. Plants de vanille en bonne santé. Bonne fermentation des gousses.',
                'conformite' => 'Conforme',
                'actions_correctives' => 'Continuer les pratiques actuelles.',
                'gps_inspection' => '-14.9275, 50.1712',
                'inspecteur' => 'Rasoa Daniel',
            ],
            [
                'code_producteur' => 'PRD-002',
                'nom_producteur' => 'Razafindrainibe Pierre',
                'code_unique_parcelle' => 'PAR-002',
                'date_inspection' => '2025-01-12',
                'observations' => 'Some plants affected by disease. Need better drainage.',
                'conformite' => 'Partiel',
                'actions_correctives' => 'Improve drainage system. Apply fungicide treatment.',
                'gps_inspection' => '-14.9100, 50.1500',
                'inspecteur' => 'Rasoa Daniel',
            ],
            [
                'code_producteur' => 'PRD-003',
                'nom_producteur' => 'Randriamanantena Marie',
                'code_unique_parcelle' => 'PAR-003',
                'date_inspection' => '2025-01-15',
                'observations' => 'Excellent quality vanilla. Good harvest this season. Well maintained plantation.',
                'conformite' => 'Conforme',
                'actions_correctives' => 'Continue current practices.',
                'gps_inspection' => '-14.7500, 49.6200',
                'inspecteur' => 'Rakotovao Alain',
            ],
            [
                'code_producteur' => 'PRD-004',
                'nom_producteur' => 'Rakoto Ratsimba',
                'code_unique_parcelle' => 'PAR-004',
                'date_inspection' => '2025-01-18',
                'observations' => 'Young plantation. Need more training on pruning techniques.',
                'conformite' => 'Partiel',
                'actions_correctives' => 'Provide training on pruning. Add support posts.',
                'gps_inspection' => '-14.8800, 50.1800',
                'inspecteur' => 'Rasoa Daniel',
            ],
            [
                'code_producteur' => 'PRD-005',
                'nom_producteur' => 'Randrianaivo Christian',
                'code_unique_parcelle' => 'PAR-005',
                'date_inspection' => '2025-01-20',
                'observations' => 'Good production. Some trees need rehabilitation.',
                'conformite' => 'Conforme',
                'actions_correctives' => 'Continue monitoring. Rehabilitate old trees.',
                'gps_inspection' => '-14.7200, 49.6000',
                'inspecteur' => 'Rakotovao Alain',
            ],
        ];

        foreach ($inspections as $inspection) {
            Inspection::create($inspection);
        }
    }
}
