<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: create_sections_laboratoire_table
 *
 * Replaces the hardcoded SECTIONS / TESTS_PAR_SECTION constants in
 * LaboratoireController with fully database-driven records managed
 * by the Admin via SectionLaboratoireController.
 *
 * Steps:
 *  1. Create `sections_laboratoire`  — one row per discipline category.
 *  2. Seed the 10 default sections that were previously hard-coded.
 *  3. Add `section_id` FK to `tarifs_laboratoire` and back-fill from
 *     the existing `section` string column.
 */
class CreateSectionsLaboratoireTable extends Migration
{
    public function up(): void
    {
        // ── 1. sections_laboratoire ──────────────────────────────────────────
        Schema::create('sections_laboratoire', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug', 50)->unique();
            $table->string('label', 100);
            $table->string('icon', 60)->default('fa-flask');
            $table->string('color_classes', 120)->default('tw-bg-slate-50 tw-border-slate-300');
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index('actif');
            $table->index('ordre');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // ── 2. Seed the 10 default sections ─────────────────────────────────
        $now  = now();
        $seed = [
            [1,  'hematologie',   'Hématologie',            'fa-droplet',      'tw-bg-red-50 tw-border-red-300'],
            [2,  'hemostase',     'Hémostase',               'fa-tint',         'tw-bg-orange-50 tw-border-orange-300'],
            [3,  'biochimie',     'Biochimie',               'fa-flask',        'tw-bg-yellow-50 tw-border-yellow-300'],
            [4,  'hormonologie',  'Hormonologie',             'fa-dna',          'tw-bg-lime-50 tw-border-lime-300'],
            [5,  'marqueurs',     'Marqueurs tumoraux',       'fa-microscope',   'tw-bg-teal-50 tw-border-teal-300'],
            [6,  'bacteriologie', 'Bactériologie',            'fa-bacterium',    'tw-bg-cyan-50 tw-border-cyan-300'],
            [7,  'spermiologie',  'Spermiologie',             'fa-circle-dot',   'tw-bg-sky-50 tw-border-sky-300'],
            [8,  'urines',        'Urines / ECBU',            'fa-toilet-paper', 'tw-bg-blue-50 tw-border-blue-300'],
            [9,  'serologie',     'Sérologie',                'fa-shield-virus', 'tw-bg-indigo-50 tw-border-indigo-300'],
            [10, 'parasitologie', 'Parasitologie',            'fa-bug',          'tw-bg-violet-50 tw-border-violet-300'],
        ];

        foreach ($seed as [$ordre, $slug, $label, $icon, $color]) {
            DB::table('sections_laboratoire')->insert([
                'slug'          => $slug,
                'label'         => $label,
                'icon'          => $icon,
                'color_classes' => $color,
                'ordre'         => $ordre,
                'actif'         => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // ── 3. Add section_id FK to tarifs_laboratoire ───────────────────────
        Schema::table('tarifs_laboratoire', function (Blueprint $table) {
            $table->unsignedInteger('section_id')->nullable()->after('section')
                ->comment('FK → sections_laboratoire.id');

            $table->index('section_id');
            $table->foreign('section_id')
                  ->references('id')->on('sections_laboratoire')
                  ->onDelete('restrict');
        });

        // Back-fill section_id from the existing section slug string
        DB::statement("
            UPDATE tarifs_laboratoire
            SET section_id = (
                SELECT id FROM sections_laboratoire
                WHERE sections_laboratoire.slug = tarifs_laboratoire.section
            )
        ");
    }

    public function down(): void
    {
        // ── SQLite-compatible: recreate tarifs_laboratoire without section_id ─
        //
        // SQLite does not support ALTER TABLE DROP COLUMN when foreign keys or
        // indexes are involved. The safe approach is to:
        //   1. Copy all rows (minus section_id) into a new temp table.
        //   2. Drop the original.
        //   3. Rename the temp table back.
        //
        // We use DB::statement() to stay below Laravel's Blueprint layer,
        // which generates an empty column list on SQLite and crashes.

        // Disable FK checks so we can freely drop / rename
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("
            CREATE TABLE tarifs_laboratoire_new AS
            SELECT
                id,
                section,
                section_label,
                nom_test,
                prix_unitaire,
                description,
                actif,
                created_by,
                updated_by,
                created_at,
                updated_at
            FROM tarifs_laboratoire
        ");

        Schema::drop('tarifs_laboratoire');

        DB::statement('ALTER TABLE tarifs_laboratoire_new RENAME TO tarifs_laboratoire');

        // Restore the indexes and constraints that CreateTarifsLaboratoireTable
        // originally created (without section_id).
        DB::statement('CREATE UNIQUE INDEX uq_section_test ON tarifs_laboratoire (section, nom_test)');
        DB::statement('CREATE INDEX tarifs_laboratoire_section_index ON tarifs_laboratoire (section)');
        DB::statement('CREATE INDEX tarifs_laboratoire_actif_index   ON tarifs_laboratoire (actif)');

        DB::statement('PRAGMA foreign_keys = ON');

        // Drop the sections table now that the FK referencing it is gone
        Schema::dropIfExists('sections_laboratoire');
    }
}