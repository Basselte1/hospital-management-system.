<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * One-time data migration: convert legacy comma-separated ordonance fields
 * to the canonical ' | ' pipe format.
 *
 * BACKGROUND
 * ──────────
 * The ordonances table stores medicament, quantite, and description as
 * single text columns with multiple items joined by a separator.
 *
 * The OLD code split on ',' (comma). This is ambiguous because medication
 * names and posologies routinely contain commas, e.g.:
 *   "Amoxicilline 500 mg, gélule"  →  would wrongly split into two items
 *
 * The NEW code uses ' | ' (space-pipe-space) which is safe because this
 * sequence never appears in normal medical text.
 *
 * HOW THIS MIGRATION WORKS
 * ────────────────────────
 * Records that already contain ' | ' are in the new format → skip.
 * Records without ' | ' are legacy. Because we cannot reliably split
 * them on comma (a medication name IS allowed to contain commas), we
 * leave each field as a single-entry value but wrapped in the new
 * format (i.e. no change needed for single-item records since the parser
 * now treats no-pipe as a single item). These records will be
 * automatically corrected when the prescriber opens the edit form and
 * re-saves.
 *
 * If your data has TRUE multi-item comma-separated records that were
 * saved before the ' | ' format was introduced AND you know none of
 * those medication names contain commas, you can enable the
 * AGGRESSIVE mode below by setting $aggressive = true.
 */
return new class extends Migration
{
    public function up(): void
    {
        /*
         * Set $aggressive = true ONLY if you are certain that legacy
         * comma-separated records do NOT have commas inside individual
         * medication names. Leave false (safe default) otherwise.
         */
        $aggressive = false;

        if (!$aggressive) {
            Log::info('ordonances pipe migration: running in SAFE mode — legacy records kept as-is (single-item). Re-save via edit form to upgrade individual records.');
            return;
        }

        // AGGRESSIVE: split on comma and re-join with ' | '
        // Only applies to rows that do NOT already contain ' | '
        DB::table('ordonances')
            ->whereRaw("medicament NOT LIKE '% | %'")
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $convert = function (?string $val): string {
                        if (!$val) return '';
                        // Already in pipe format (extra safety check)
                        if (str_contains($val, ' | ')) return $val;
                        $parts = array_map('trim', explode(',', $val));
                        $parts = array_filter($parts, fn($p) => $p !== '');
                        return implode(' | ', $parts);
                    };

                    DB::table('ordonances')->where('id', $row->id)->update([
                        'medicament'  => $convert($row->medicament),
                        'quantite'    => $convert($row->quantite),
                        'description' => $convert($row->description),
                    ]);
                }
            });

        Log::info('ordonances pipe migration: AGGRESSIVE mode complete — legacy records converted.');
    }

    public function down(): void
    {
        // Irreversible data transformation — no down() action.
        Log::info('ordonances pipe migration: down() is a no-op.');
    }
};