<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

/**
 * DossierNumberService
 * ─────────────────────────────────────────────────────────────────────────────
 * Generates unique patient dossier numbers for the CMCU application.
 *
 * FORMAT:  CMCU + YYYY + 7 random digits
 * EXAMPLE: CMCU20251234567
 *
 * Collision safety:
 *   The generator retries up to MAX_ATTEMPTS times if the generated number
 *   already exists in the database. With 7 random digits (10 million
 *   possibilities per year) a collision is extremely unlikely in practice,
 *   but the retry loop guarantees uniqueness.
 *
 * Usage (in PatientsController@store):
 *   $numeroDossier = app(DossierNumberService::class)->generate();
 * ─────────────────────────────────────────────────────────────────────────────
 */
class DossierNumberService
{
    /** Prefix is hard-coded for this single-hospital build. */
    protected string $prefix = 'CMCU';

    /** Maximum attempts before throwing an exception. */
    protected int $maxAttempts = 10;

    /**
     * Generate a guaranteed-unique dossier number.
     *
     * @throws \RuntimeException if a unique number cannot be found after MAX_ATTEMPTS tries.
     */
    public function generate(): string
    {
        $year = now()->year;

        for ($attempt = 1; $attempt <= $this->maxAttempts; $attempt++) {
            $number = $this->buildNumber($year);

            // Check uniqueness — use a direct DB query to avoid loading the model
            $exists = DB::table('patients')
                ->where('numero_dossier', $number)
                ->exists();

            if (!$exists) {
                return $number;
            }
        }

        throw new \RuntimeException(
            "Could not generate a unique dossier number after {$this->maxAttempts} attempts."
        );
    }

    /**
     * Build the formatted number string.
     *
     * CMCU + YYYY + 7 random digits
     * e.g. CMCU20251234567
     */
    protected function buildNumber(int $year): string
    {
        // random_int is cryptographically secure and avoids mt_rand collisions
        $random = str_pad((string) random_int(0, 9_999_999), 7, '0', STR_PAD_LEFT);

        return "{$this->prefix}{$year}{$random}";
    }
}