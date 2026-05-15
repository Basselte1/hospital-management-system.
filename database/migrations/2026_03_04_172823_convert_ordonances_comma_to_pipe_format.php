<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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