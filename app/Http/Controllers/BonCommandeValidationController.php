<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BonCommandeValidationController extends Controller
{
    /** Only Admin and Gestionnaire can validate / reject */
    private $validationRoles = [1, 3];

    /** Admin, Gestionnaire and Logistique can send email */
    private $sendRoles = [1, 3, 5];

    /**
     * List orders pending validation (statut = 'envoye')
     */
    public function index()
    {
        if (!in_array(Auth::user()->role_id, $this->validationRoles)) {
            abort(403, 'Seul le gestionnaire peut valider les bons de commande');
        }

        $pendingOrders = BonCommande::where('statut', 'envoye')
                                    ->with(['items', 'createdBy'])
                                    ->latest()
                                    ->paginate(20);

        return view('admin.bon_commandes.validation', compact('pendingOrders'));
    }


    /**
     * Validate a single order  →  envoye → valide
     */
    public function validateCommande(Request $request, $id)
    {
        if (!in_array(Auth::user()->role_id, $this->validationRoles)) {
            abort(403, 'Seul le gestionnaire peut valider les bons de commande');
        }

        $request->validate([
            'validation_comment' => 'nullable|string|max:1000',
        ]);

        $bonCommande = BonCommande::findOrFail($id);

        if (!$bonCommande->canBeValidated()) {
            return back()->with('error', 'Ce bon de commande ne peut pas être validé (statut actuel : ' . $bonCommande->statut . ')');
        }

        $bonCommande->update([
            'statut'             => 'valide',
            'validated_by'       => Auth::id(),
            'validated_at'       => now(),
            'validation_comment' => $request->validation_comment,
        ]);

        return back()->with('success', 'Bon de commande ' . $bonCommande->numero_bon . ' validé avec succès! Prêt pour réception.');
    }

    /**
     * Reject an order  →  envoye → annule
     */
    public function reject(Request $request, $id)
    {
        if (!in_array(Auth::user()->role_id, $this->validationRoles)) {
            abort(403, 'Seul le gestionnaire peut rejeter les bons de commande');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $bonCommande = BonCommande::findOrFail($id);

        if (!$bonCommande->canBeValidated()) {
            return back()->with('error', 'Ce bon de commande ne peut pas être modifié');
        }

        $bonCommande->update([
            'statut'             => 'annule',
            'validated_by'       => Auth::id(),
            'validated_at'       => now(),
            'validation_comment' => 'REJETÉ: ' . $request->rejection_reason,
        ]);

        return back()->with('success', 'Bon de commande ' . $bonCommande->numero_bon . ' rejeté.');
    }

    /**
     * Batch-validate multiple orders at once
     */
    public function batchValidate(Request $request)
    {
        if (!in_array(Auth::user()->role_id, $this->validationRoles)) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'order_ids'     => 'required|array',
            'order_ids.*'   => 'exists:bon_commandes,id',
            'batch_comment' => 'nullable|string',
        ]);

        $validated = 0;

        foreach ($request->order_ids as $orderId) {
            $bc = BonCommande::find($orderId);

            if ($bc && $bc->canBeValidated()) {
                $bc->update([
                    'statut'             => 'valide',
                    'validated_by'       => Auth::id(),
                    'validated_at'       => now(),
                    'validation_comment' => $request->batch_comment,
                ]);
                $validated++;
            }
        }

        return back()->with('success', "$validated bon(s) de commande validé(s) avec succès!");
    }

    // -------------------------------------------------------------------------
    // Send email – callable from the validation page by Gestionnaire
    // -------------------------------------------------------------------------

    /**
     * Send (or resend) the BC email to the fournisseur.
     *
     * This action lives here so that the Gestionnaire can trigger it directly
     * from the validation / show page without having to navigate elsewhere.
     * The actual sending logic (PDF generation + Mail) is delegated to the
     * BonCommandeController via a shared helper.  We keep it DRY by just
     * forwarding the request.
     *
     * Allowed: Admin(1), Gestionnaire(3), Logistique(5)
     */
    public function sendEmail(Request $request, $id)
    {
        if (!in_array(Auth::user()->role_id, $this->sendRoles)) {
            abort(403, 'Accès non autorisé');
        }

        // Delegate to the existing send() action on BonCommandeController
        $controller = app(BonCommandeController::class);

        // We call it as a sub-request so the response (redirect) bubbles back
        return $controller->send($id);
    }
}