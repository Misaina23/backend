<?php

namespace Database\Seeders;

use App\Models\Producer;
use Illuminate\Database\Seeder;

class ProducerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $producers = [
            [
                'nom_site' => 'Sambava',
                'nom_prenom' => 'Ramanatefa Jean',
                'code_producteur' => 'PRD-001',
                'telephone' => '+261 32 12 345 67',
                'date_integration' => '2023-01-15',
                'superficie' => 4.5,
                'chiffre_affaires' => 12500,
                'code_unique_parcelle' => 'PAR-001',
                'culture' => 'Vanille',
                'interculture' => 'Poivrier',
                'nombre_arbres' => 320,
                'gps_parcelle1' => '-14.9275, 50.1712',
                'gps_parcelle2' => '-14.9280, 50.1715',
                'gps_parcelle3' => '-14.9272, 50.1708',
                'gps_menage' => '-14.9260, 50.1720',
                'estimation_recolte' => '280 kg',
                'rendement' => '62 kg/ha',
                'quantite_livree' => 250,
                'nom_ci' => 'Rasoa Daniel',
            ],
            [
                'nom_site' => 'Sambava',
                'nom_prenom' => 'Razafindrainibe Pierre',
                'code_producteur' => 'PRD-002',
                'telephone' => '+261 33 45 678 90',
                'date_integration' => '2023-03-22',
                'superficie' => 3.2,
                'chiffre_affaires' => 8750,
                'code_unique_parcelle' => 'PAR-002',
                'culture' => 'Vanille',
                'interculture' => 'Cacao',
                'nombre_arbres' => 210,
                'gps_parcelle1' => '-14.9100, 50.1500',
                'gps_parcelle2' => '-14.9105, 50.1503',
                'gps_parcelle3' => '-14.9098, 50.1497',
                'gps_menage' => '-14.9090, 50.1510',
                'estimation_recolte' => '200 kg',
                'rendement' => '62.5 kg/ha',
                'quantite_livree' => 180,
                'nom_ci' => 'Rasoa Daniel',
            ],
            [
                'nom_site' => 'Andapa',
                'nom_prenom' => 'Randriamanantena Marie',
                'code_producteur' => 'PRD-003',
                'telephone' => '+261 34 56 789 01',
                'date_integration' => '2022-06-10',
                'superficie' => 6.0,
                'chiffre_affaires' => 15200,
                'code_unique_parcelle' => 'PAR-003',
                'culture' => 'Vanille',
                'interculture' => 'Café',
                'nombre_arbres' => 480,
                'gps_parcelle1' => '-14.7500, 49.6200',
                'gps_parcelle2' => '-14.7505, 49.6205',
                'gps_parcelle3' => '-14.7498, 49.6198',
                'gps_menage' => '-14.7490, 49.6210',
                'estimation_recolte' => '420 kg',
                'rendement' => '70 kg/ha',
                'quantite_livree' => 380,
                'nom_ci' => 'Rakotovao Alain',
            ],
            [
                'nom_site' => 'Sambava',
                'nom_prenom' => 'Rakoto Ratsimba',
                'code_producteur' => 'PRD-004',
                'telephone' => '+261 32 98 765 43',
                'date_integration' => '2024-02-01',
                'superficie' => 2.5,
                'chiffre_affaires' => 6300,
                'code_unique_parcelle' => 'PAR-004',
                'culture' => 'Vanille',
                'interculture' => 'Banane',
                'nombre_arbres' => 180,
                'gps_parcelle1' => '-14.8800, 50.1800',
                'gps_parcelle2' => '-14.8803, 50.1803',
                'gps_parcelle3' => '-14.8798, 50.1798',
                'gps_menage' => '-14.8790, 50.1810',
                'estimation_recolte' => '150 kg',
                'rendement' => '60 kg/ha',
                'quantite_livree' => 140,
                'nom_ci' => 'Rasoa Daniel',
            ],
            [
                'nom_site' => 'Andapa',
                'nom_prenom' => 'Randrianaivo Christian',
                'code_producteur' => 'PRD-005',
                'telephone' => '+261 33 21 098 76',
                'date_integration' => '2023-08-14',
                'superficie' => 5.0,
                'chiffre_affaires' => 11000,
                'code_unique_parcelle' => 'PAR-005',
                'culture' => 'Vanille',
                'interculture' => 'Poivrier',
                'nombre_arbres' => 400,
                'gps_parcelle1' => '-14.7200, 49.6000',
                'gps_parcelle2' => '-14.7205, 49.6005',
                'gps_parcelle3' => '-14.7198, 49.5998',
                'gps_menage' => '-14.7190, 49.6010',
                'estimation_recolte' => '350 kg',
                'rendement' => '70 kg/ha',
                'quantite_livree' => 320,
                'nom_ci' => 'Rakotovao Alain',
            ],
        ];

        foreach ($producers as $producer) {
            Producer::create($producer);
        }
    }
}
