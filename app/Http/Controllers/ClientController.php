<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FactureClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\PdfService;


class ClientController extends Controller
{
    // =========================================================
    //  1. DOSSIERS CLIENTS  (resources/views/admin/clients/)
    // =========================================================

    /**
     * Liste des dossiers clients → admin/clients/index.blade.php
     */
    public function index()
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::with('user')->latest()->paginate(50);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Formulaire de création → admin/clients/create.blade.php
     */
    public function create()
    {
        $this->authorize('create', Client::class);

        $users = User::where('role_id', 2)->with('patients')->get();

        return view('admin.clients.create', compact('users'));
    }

    /**
     * Enregistrement d'un nouveau dossier client
     */
    public function store(Request $request)
    {
        $this->authorize('create', Client::class);

        $request->validate([
            'nom'            => 'required',
            'date_insertion' => 'required|date',
            'prise_en_charge'=> 'required',
        ]);

        $client = new Client();
        $client->nom              = $request->nom;
        $client->prenom           = $request->prenom;
        $client->motif            = $request->motif;
        $client->montant          = (int) $request->montant;
        $client->assurance        = $request->assurance;
        $client->numero_assurance = $request->numero_assurance;
        $client->demarcheur       = $request->demarcheur;
        $client->medecin_r        = $request->medecin_r;
        $client->date_insertion   = $request->date_insertion;
        $client->user_id          = Auth::id();

        // Calcul du taux de prise en charge
        $taux = (int) $request->prise_en_charge;   // ex: 80 = 80 %
        $montant = (int) $request->montant;
        $avance  = (int) $request->avance;

        $client->prise_en_charge = $taux;

        if ($client->assurance) {
            // Cas AVEC assurance
            $client->partassurance = (int) ($montant * $taux / 100);
            $client->partpatient   = $montant - $client->partassurance;

            if ($avance) {
                $client->avance  = $avance;
                $client->reste   = $client->partpatient - $avance;
            } else {
                // Pas d'avance : on considère que la part patient sera entièrement dûe
                $client->avance = 0;
                $client->reste  = $client->partpatient;
            }
        } else {
            // Cas SANS assurance
            $client->partassurance = 0;
            $client->partpatient   = 0;

            if ($avance) {
                $client->avance = $avance;
                $client->reste  = $montant - $avance;
            } else {
                $client->avance = 0;
                $client->reste  = $montant;
            }
        }

        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Le client a été ajouté avec succès !');
    }

    /**
     * Modification d'un dossier client
     */
    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $request->validate([
            'nom'            => 'required',
            'date_insertion' => 'required|date',
        ]);

        $client->nom              = $request->nom;
        $client->prenom           = $request->prenom;
        $client->assurance        = $request->assurance;
        $client->numero_assurance = $request->numero_assurance;
        $client->montant          = $request->montant;
        $client->avance           = $request->avance;
        $client->reste            = $request->reste;
        $client->partpatient      = $request->partpatient;
        
        $client->partassurance    = $request->partassurance;
        $client->demarcheur       = $request->demarcheur;
        $client->prise_en_charge  = $request->prise_en_charge;
        $client->motif            = $request->motif;
        $client->medecin_r        = $request->medecin_r;
        $client->date_insertion   = $request->date_insertion;
        $client->user_id          = Auth::id();
        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Les informations ont été mises à jour avec succès !');
    }

    /**
     * Suppression d'un dossier client
     */
    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Le client a bien été supprimé.');
    }

    // =========================================================
    //  2. FACTURES CLIENTS  (resources/views/admin/factures/)
    // =========================================================

    /**
     * Liste des factures clients → admin/factures/client.blade.php
     * Route : GET /admin/factures-client  →  name('factures.client')
     */
    public function indexFactures()
    {
        $this->authorize('view', User::class);

        $facturesClients = FactureClient::latest()->paginate(50);

        // Dates distinctes pour le sélecteur de bilan journalier
        $lists = FactureClient::selectRaw('DATE(date_insertion) as jour')
            ->groupBy('jour')
            ->orderByDesc('jour')
            ->pluck('jour');

        return view('admin.factures.client', compact('facturesClients', 'lists'));
    }

    /**
     * Génère une facture à partir d'un dossier client
     * Route : GET /admin/clients/{id}/generer-facture  →  name('clientP.pdf')
     */
    public function generate_client(Request $request, $id)
    {
        $this->authorize('generateFacture', Client::class);

        $client = Client::findOrFail($id);

        FactureClient::create([
            'client_id'      => $client->id,
            'assurance'      => $client->assurance,
            'partassurance'  => $client->partassurance,
            'partpatient'    => $client->partpatient,
            'motif'          => $client->motif,
            'montant'        => $client->montant,
            'demarcheur'     => $client->demarcheur,
            'avance'         => $client->avance,
            'reste'          => $client->reste,
            'prenom'         => $client->prenom,
            'nom'            => $client->nom,
            'medecin_r'      => $client->medecin_r,
            'date_insertion' => $client->date_insertion,
            'user_id'        => Auth::id(),
        ]);

        return redirect()->route('factures.client')
            ->with('success', 'La facture a été générée. Consultez la liste des factures.');
    }

    /**
     * Export PDF d'une facture client
     * Route : GET /admin/facture-client/{id}/pdf  →  name('factures.client_pdf')
     */
    public function exportPdf($id, Request $request)
    {
        try {
            $facture = FactureClient::findOrFail($id);

            return PdfService::generateclient(
                $facture,
                $request->input('layout', 'standard'),
                $request->has('auto_print')
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', "La facture client n°{$id} est introuvable.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }

    /**
     * Export PDF du bilan journalier des clients externes
     * Route : POST /admin/bilan-clientexterne  →  name('bilan_clientexterne.pdf')
     */
    public function exportBilan(Request $request)
    {
        $this->authorize('view', User::class);

        $request->validate(['day' => 'required|date']);

        $day      = $request->day;
        $factures = FactureClient::whereDate('date_insertion', $day)->get();

        $totalPercu = $factures->sum('montant');
        $avances    = $factures->sum('avance');
        $restes     = $factures->sum('reste');
        $clients    = $factures->sum('partpatient');
        $assurances = $factures->sum('partassurance');

        return PdfService::generate(
            'admin.etats.bilan_clientexterne',
            compact('factures', 'totalPercu', 'avances', 'restes', 'clients', 'assurances'),
            "bilan_clients_{$day}.pdf"
        );
    }
}