<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ordonance
 *
 * @property int $id
 * @property int $user_id
 * @property int $patient_id
 * @property string $description
 * @property string $medicament
 * @property string $quantite
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereMedicament($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ordonance whereUserId($value)
 * @mixin \Eloquent
 */
class Ordonance extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'description',
        'medicament',
        'quantite'
    ];

    // NOTE: medicament, quantite, description are stored as pipe-separated strings (' | ')
    // Do NOT cast them to array — they are NOT JSON. Use parsePipeField() to parse them.
    protected $casts = [];

    /**
     * Relationships
     */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ventePharmacie()
    {
        return $this->hasMany(VentePharmacie::class);
    }


    /**
     * Parse a stored field that uses ' | ' as the canonical separator.
     *
     * IMPORTANT: Commas are NOT used as a separator. Medication names routinely
     * contain commas (e.g. "Amoxicilline 500 mg, gélule"). Splitting on commas
     * would shred those names into phantom records.
     *
     * Legacy records that were stored before the ' | ' format was adopted are
     * returned as a single-element array containing the raw value — they will
     * display as one block until the prescriber edits and re-saves the ordonnance,
     * at which point the controller re-saves in pipe format.
     */
    private function parsePipeField(?string $value): array
    {
        $value = trim((string) ($value ?? ''));
        if ($value === '') return [];

        if (str_contains($value, ' | ')) {
            return array_map('trim', explode(' | ', $value));
        }

        // Legacy record — no pipe separator found.
        // Return as a single item; do NOT split on comma.
        return [$value];
    }

    /**
     * Get parsed medications from the prescription
     * Returns array of ['medicament' => name, 'quantite' => qty, 'description' => desc]
     */
    public function getParsedMedicationsAttribute()
    {
        $medicaments  = $this->parsePipeField($this->medicament);
        $quantites    = $this->parsePipeField($this->quantite);
        $descriptions = $this->parsePipeField($this->description);

        $parsed = [];
        foreach ($medicaments as $index => $medicament) {
            $parsed[] = [
                'medicament'  => $medicament,
                'quantite'    => $quantites[$index]    ?? '',
                'description' => $descriptions[$index] ?? '',
            ];
        }

        return $parsed;
    }

    /**
     * Get medications as array
     */
    public function getMedicamentsArrayAttribute()
    {
        return $this->parsePipeField($this->medicament);
    }

    /**
     * Get quantities as array
     */
    public function getQuantitesArrayAttribute()
    {
        return $this->parsePipeField($this->quantite);
    }

    /**
     * Get descriptions as array
     */
    public function getDescriptionsArrayAttribute()
    {
        return $this->parsePipeField($this->description);
    }

}