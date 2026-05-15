<?php
namespace Database\Seeders; 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;
use App\Models\DevisElement; 
class DevisElementsSeeder extends Seeder { 
    public function run(): void {
        $elements = [
            [
                'nom' => 'CS ANESTHESIQUE EN INTERNE',
                'code' => 'CSAI',
                'prix_unitaire' => 25000,
                'description' => 'Consultation anesthésique en interne',
                'actif' => true
            ],
            [
                'nom' => 'CS ANESTHESIQUE',
                'code' => 'CSA',
                'prix_unitaire' => 30000,
                'description' => 'Consultation anesthésique standard',
                'actif' => true
            ],
            [
                'nom' => 'CONSULTATION ANESTHESISTE',
                'code' => 'CA',
                'prix_unitaire' => 28000,
                'description' => 'Consultation avec anesthésiste',
                'actif' => true
            ],
            [
                'nom' => 'CONSULTATION DU SPECIALISTE',
                'code' => 'CS',
                'prix_unitaire' => 35000,
                'description' => 'Consultation avec médecin spécialiste',
                'actif' => true
            ],
            [
                'nom' => 'KC',
                'code' => 'KC',
                'prix_unitaire' => 50000,
                'description' => 'Coefficient chirurgical',
                'actif' => true
            ],
            [
                'nom' => 'KC + PRELEVEMENT',
                'code' => 'KCP',
                'prix_unitaire' => 75000,
                'description' => 'Coefficient chirurgical avec prélèvement',
                'actif' => true
            ],
            [
                'nom' => 'KA',
                'code' => 'KA',
                'prix_unitaire' => 45000,
                'description' => 'Coefficient anesthésique',
                'actif' => true
            ],
            [
                'nom' => 'KB',
                'code' => 'KB',
                'prix_unitaire' => 20000,
                'description' => 'Coefficient biologique',
                'actif' => true
            ],
            [
                'nom' => 'K (Amplificateur de Brillance)',
                'code' => 'KAB',
                'prix_unitaire' => 40000,
                'description' => 'Coefficient pour amplificateur de brillance',
                'actif' => true
            ],
            [
                'nom' => 'Chambre standard',
                'code' => null,
                'prix_unitaire' => 30000,
                'description' => 'Chambre d\'hospitalisation standard par jour',
                'actif' => true
            ],
            [
                'nom' => 'Visite médicale',
                'code' => null,
                'prix_unitaire' => 10000,
                'description' => 'Visite médicale quotidienne',
                'actif' => true
            ],
            [
                'nom' => 'AMI-JOUR',
                'code' => null,
                'prix_unitaire' => 9000,
                'description' => 'Actes médicaux infirmiers par jour (750 x 12)',
                'actif' => true
            ],
            [
                'nom' => 'Pansement simple',
                'code' => null,
                'prix_unitaire' => 5000,
                'description' => 'Pansement simple',
                'actif' => true
            ],
            [
                'nom' => 'Pansement complexe',
                'code' => null,
                'prix_unitaire' => 15000,
                'description' => 'Pansement complexe ou chirurgical',
                'actif' => true
            ],
            [
                'nom' => 'Perfusion',
                'code' => null,
                'prix_unitaire' => 8000,
                'description' => 'Pose et suivi de perfusion',
                'actif' => true
            ],
            [
                'nom' => 'Echographie abdominale',
                'code' => 'ECHO-ABD',
                'prix_unitaire' => 35000,
                'description' => 'Échographie de l\'abdomen',
                'actif' => true
            ],
            [
                'nom' => 'Radiographie thoracique',
                'code' => 'RX-THORAX',
                'prix_unitaire' => 15000,
                'description' => 'Radiographie du thorax',
                'actif' => true
            ],
            [
                'nom' => 'Bilan sanguin complet',
                'code' => 'BSC',
                'prix_unitaire' => 25000,
                'description' => 'Bilan sanguin complet (NFS, VS, glycémie, etc.)',
                'actif' => true
            ],
            [
                'nom' => 'Scanner',
                'code' => 'SCAN',
                'prix_unitaire' => 85000,
                'description' => 'Examen scanner',
                'actif' => true
            ],
            [
                'nom' => 'IRM',
                'code' => 'IRM',
                'prix_unitaire' => 120000,
                'description' => 'Imagerie par résonance magnétique',
                'actif' => true
            ]
        ];

        foreach ($elements as $element) {
            DevisElement::create($element);
        }

        $this->command->info('Devis elements seeded successfully!');
    }
}