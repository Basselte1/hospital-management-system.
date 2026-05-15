<?php

namespace App\Http\Controllers;


use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\HistoriqueFacture;
use App\Models\User;
use \App\Models\Patient;
use App\Models\FactureDevi;
use App\Models\Devi;


class FactureDevisController extends Controller
{
    // Route name in web.php expects these methods:
    // - FactureDevisController@FactureDevis
    // - FactureDevisController@FactureDevisCreate
    // - FactureDevisController@FactureDevisStore

    public function FactureDevis()
    {
       
        $factures = Devi::with(['patient:id,name,prenom', 'medecin:id,name,prenom', 'user:id,name', 'ligneDevis', 'factureDevi'])
            ->latest()
            ->paginate(25);

        return view('admin.factures.facture_devis', compact('factures'));
    }

    // Temporary stubs to prevent “method does not exist” errors.
    // Implementations can be added later.
    public function FactureDevisCreate()
    {
        return redirect()->route('facture_devis.index');
    }

    public function FactureDevisStore(Request $request)
    {
        return redirect()->route('facture_devis.index');
    }

    public function export_facture_devis($id)
    {
        return redirect()->route('facture_devis.index');
    }
}

