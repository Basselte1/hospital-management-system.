<?php

namespace App\Models;

use App\Models\FactureConsultation;
use App\Models\facturExamen;
use App\Models\FactureActe;
use Illuminate\Database\Eloquent\Model;

class HistoriqueFacture extends Model
{
    protected $guarded = [];

    public function facture_consultation()
    {
        return $this->belongsTo(\App\Models\FactureConsultation::class);
    }

      public function facture_examen()
    {
        return $this->belongsTo(\App\Models\FactureExamen::class);
    }

      public function facture_acte()
    {
        return $this->belongsTo(\App\Models\FactureActe::class);
    }

    
}
