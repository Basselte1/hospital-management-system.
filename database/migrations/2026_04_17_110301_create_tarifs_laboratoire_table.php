<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTarifsLaboratoireTable extends Migration
{
    /**
     * Tariff catalog for laboratory tests.
     * Each row = one named test inside a discipline section,
     * with its unit price in FCFA.
     *
     * Managed by Admin (role 1) only.
     * Readable by Laborantin (10) and Secrétaire (6).
     */
    public function up(): void
    {
        Schema::create('tarifs_laboratoire', function (Blueprint $table) {
            $table->increments('id');

            // Which discipline section this test belongs to
            $table->string('section', 50)
                ->comment('hematologie|hemostase|biochimie|hormonologie|marqueurs|bacteriologie|spermiologie|urines|serologie|parasitologie');

            // Human-readable section label (denormalised for easy display)
            $table->string('section_label', 100)->nullable();

            // The test name exactly as it appears in exam result rows
            $table->string('nom_test', 255);

            // Unit price in FCFA (integer — no cents in FCFA)
            $table->unsignedInteger('prix_unitaire')->default(0)
                ->comment('Prix en FCFA');

            // Optional short description / clinical note
            $table->string('description', 500)->nullable();

            // Whether this test is currently offered (soft-disable without deletion)
            $table->boolean('actif')->default(true);

            // Audit
            $table->unsignedInteger('created_by')->nullable()
                ->comment('user_id of the admin who created this entry');
            $table->unsignedInteger('updated_by')->nullable()
                ->comment('user_id of the admin who last updated this entry');

            $table->timestamps();

            // Unique test name per section (prevent duplicates)
            $table->unique(['section', 'nom_test'], 'uq_section_test');

            // Fast lookups by section
            $table->index('section');
            $table->index('actif');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // ── Seed default tariffs per section ─────────────────────────────────
        // Adjust prices to match your actual CMCU tariff schedule.
        $now = now();
        $rows = [];

        $defaults = [
            'hematologie' => [
                ['Globules rouges (GR)',              2500],
                ['Hémoglobine (Hb)',                  2500],
                ['Hématocrite (Ht)',                  2500],
                ['VGM',                               2500],
                ['TCMH',                              2500],
                ['CCMH',                              2500],
                ['Plaquettes',                        3000],
                ['Leucocytes (GB)',                   3000],
                ['Polynucléaires neutrophiles',       3500],
                ['Lymphocytes',                       3500],
                ['Monocytes',                         3500],
                ['Polynucléaires éosinophiles',       3500],
                ['Polynucléaires basophiles',         3500],
                ['Vitesse de sédimentation (VS)',     2000],
                ['NFS (Numération Formule Sanguine)', 8000],
                ['Groupe sanguin + Rhésus',           4000],
                ['CRP',                               5000],
            ],
            'hemostase' => [
                ['Temps de Quick (TQ/TP)',  4000],
                ['INR',                     4000],
                ['TCA',                     4000],
                ['Fibrinogène',             5000],
                ['Temps de saignement',     3000],
                ['D-dimères',               8000],
            ],
            'biochimie' => [
                ['Glycémie',               3000],
                ['Créatinine',             3000],
                ['Urée',                   3000],
                ['Acide urique',           3000],
                ['ALAT (GPT)',             3500],
                ['ASAT (GOT)',             3500],
                ['GGT',                    3500],
                ['Phosphatases alcalines', 3500],
                ['Bilirubine totale',      3500],
                ['Bilirubine directe',     3500],
                ['Bilirubine indirecte',   3500],
                ['Protéines totales',      3000],
                ['Albumine',               3000],
                ['Cholestérol total',      3500],
                ['HDL-cholestérol',        4000],
                ['LDL-cholestérol',        4000],
                ['Triglycérides',          4000],
                ['Calcium',                3000],
                ['Phosphore',              3000],
                ['Sodium',                 3000],
                ['Potassium',              3000],
                ['Chlore',                 3000],
                ['Bicarbonates',           3000],
                ['Amylase',                4000],
                ['Lipase',                 4000],
                ['CRP',                    5000],
            ],
            'hormonologie' => [
                ['TSH',          7000],
                ['T3 libre',     7000],
                ['T4 libre',     7000],
                ['FSH',          8000],
                ['LH',           8000],
                ['Prolactine',   8000],
                ['Testostérone', 9000],
                ['Œstradiol',    9000],
                ['Progestérone', 9000],
                ['β-hCG',        8000],
                ['Cortisol',     9000],
                ['Insuline',     9000],
                ['Ferritine',    7000],
                ['Fer sérique',  4000],
            ],
            'marqueurs' => [
                ['AFP (Alpha-fœtoprotéine)', 10000],
                ['PSA total',               10000],
                ['PSA libre',               10000],
                ['CA 125',                  12000],
                ['CA 19-9',                 12000],
                ['CA 15-3',                 12000],
                ['CEA (ACE)',               10000],
            ],
            'bacteriologie' => [
                ['Examen direct',              4000],
                ['Culture',                    6000],
                ['Antibiogramme',              8000],
                ['BK direct (BAAR)',           5000],
                ['BK culture (Löwenstein)',   12000],
                ['Hémoculture',               10000],
                ['Coproculture',               8000],
            ],
            'spermiologie' => [
                ['Volume',                  3000],
                ['pH',                      2000],
                ['Concentration',           4000],
                ['Mobilité totale',         4000],
                ['Mobilité progressive',    4000],
                ['Vitalité',                4000],
                ['Morphologie (Kruger)',     5000],
                ['Spermogramme complet',   15000],
            ],
            'urines' => [
                ['Bandelette urinaire (BU)',      2000],
                ['Leucocytes',                    2500],
                ['Hématies',                      2500],
                ['Protéines',                     2500],
                ['Glucose',                       2500],
                ['pH',                            1500],
                ['Densité',                       1500],
                ['Nitrites',                      2000],
                ['Corps cétoniques',              2000],
                ['ECBU (cytobactériologie)',       8000],
            ],
            'serologie' => [
                ['Sérologie VIH (Ag/Ac)',      6000],
                ['Ag HBs',                     5000],
                ['Ac anti-HBs',                5000],
                ['Ac anti-HBc (IgM)',          6000],
                ['Ac anti-VHC',                6000],
                ['TPHA',                       4000],
                ['VDRL',                       4000],
                ['Widal (fièvre typhoïde)',     5000],
                ['Toxoplasmose (IgG/IgM)',      7000],
                ['Rubéole (IgG/IgM)',           7000],
                ['CMV (IgG/IgM)',               7000],
                ['Sérologie paludéenne',        5000],
            ],
            'parasitologie' => [
                ['Goutte épaisse / Frottis sanguin (GE/DP)', 4000],
                ['Recherche de microfilaires',               5000],
                ['Examen parasitologique des selles (EPS)',  5000],
                ['Scotch-test (oxyurose)',                   3000],
                ['Recherche de Giardia',                     4000],
                ['Coproparasitologie complète',              8000],
            ],
        ];

        $sectionLabels = [
            'hematologie'   => 'Hématologie',
            'hemostase'     => 'Hémostase',
            'biochimie'     => 'Biochimie',
            'hormonologie'  => 'Hormonologie',
            'marqueurs'     => 'Marqueurs tumoraux',
            'bacteriologie' => 'Bactériologie',
            'spermiologie'  => 'Spermiologie',
            'urines'        => 'Urines / ECBU',
            'serologie'     => 'Sérologie',
            'parasitologie' => 'Parasitologie',
        ];

        foreach ($defaults as $section => $tests) {
            foreach ($tests as [$nom, $prix]) {
                $rows[] = [
                    'section'       => $section,
                    'section_label' => $sectionLabels[$section],
                    'nom_test'      => $nom,
                    'prix_unitaire' => $prix,
                    'actif'         => true,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }

        DB::table('tarifs_laboratoire')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifs_laboratoire');
    }
}