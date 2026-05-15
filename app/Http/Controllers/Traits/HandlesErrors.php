<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trait HandlesErrors
 * 
 * Gestion centralisée des erreurs pour les controllers
 * Fournit des messages d'erreur conviviaux pour les utilisateurs
 * tout en loggant les détails techniques pour les développeurs
 */
trait HandlesErrors
{
    /**
     * Gère les exceptions de manière centralisée
     * 
     * @param \Exception $e L'exception capturée
     * @param string $context Contexte de l'erreur (ex: "création de facture")
     * @param string|null $redirectRoute Route de redirection (null = retour arrière)
     * @return RedirectResponse
     */
    protected function handleError(\Exception $e, string $context, ?string $redirectRoute = null): RedirectResponse
    {
        // Log détaillé pour les développeurs
        $this->logError($e, $context);

        // Message convivial pour l'utilisateur
        $userMessage = $this->getUserFriendlyMessage($e, $context);

        // Redirection
        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : redirect()->back();
        
        return $redirect->with('error', $userMessage)->withInput();
    }

    /**
     * Log les détails de l'erreur pour le débogage
     * 
     * @param \Exception $e
     * @param string $context
     */
    protected function logError(\Exception $e, string $context): void
    {
        Log::error("Erreur dans {$context}", [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
        ]);
    }

    /**
     * Génère un message d'erreur convivial basé sur le type d'exception
     * 
     * @param \Exception $e
     * @param string $context
     * @return string
     */
    protected function getUserFriendlyMessage(\Exception $e, string $context): string
    {
        // Gestion par type d'exception
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return "L'élément recherché est introuvable. Il a peut-être été supprimé.";
        }

        if ($e instanceof AuthorizationException) {
            return "Vous n'avez pas les permissions nécessaires pour effectuer cette action.";
        }

        if ($e instanceof ValidationException) {
            return "Les données fournies sont invalides. Veuillez vérifier les champs du formulaire.";
        }

        if ($e instanceof \PDOException || $e instanceof \Illuminate\Database\QueryException) {
            return "Une erreur de base de données s'est produite lors de {$context}. Veuillez réessayer.";
        }

        // Message générique si le type d'erreur n'est pas reconnu
        return "Une erreur est survenue lors de {$context}. Veuillez réessayer ou contacter le support.";
    }

    /**
     * Valide les données de la requête avec gestion d'erreur
     * 
     * @param array $rules Règles de validation
     * @param array $messages Messages personnalisés (optionnel)
     * @return array Données validées
     */
    protected function validateWithErrorHandling(array $rules, array $messages = []): array
    {
        try {
            return request()->validate($rules, $messages);
        } catch (ValidationException $e) {
            // Log les erreurs de validation
            Log::warning('Erreur de validation', [
                'errors' => $e->errors(),
                'input' => request()->except(['password', '_token']),
                'user_id' => auth()->id(),
            ]);
            
            throw $e; // Relancer l'exception pour que Laravel gère le retour
        }
    }

    /**
     * Exécute une transaction avec gestion d'erreur automatique
     * 
     * @param callable $callback
     * @param string $context
     * @return mixed
     * @throws \Exception
     */
    protected function executeTransaction(callable $callback, string $context)
    {
        try {
            return \DB::transaction($callback);
        } catch (\Exception $e) {
            $this->logError($e, $context);
            throw $e;
        }
    }

    /**
     * Vérifie qu'une ressource existe, sinon retourne une erreur conviviale
     * 
     * @param mixed $model Le modèle à vérifier
     * @param string $resourceName Nom de la ressource (ex: "facture", "patient")
     * @return mixed
     */
    protected function findOrFailWithMessage($modelClass, $id, string $resourceName)
    {
        try {
            return $modelClass::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->logError($e, "recherche de {$resourceName} #{$id}");
            
            return redirect()->back()->with('error', 
                "Le/La {$resourceName} demandé(e) est introuvable. Il/Elle a peut-être été supprimé(e)."
            );
        }
    }

    /**
     * Gère les erreurs de génération de PDF
     * 
     * @param \Exception $e
     * @param string $pdfType Type de PDF (ex: "facture", "bilan")
     * @return RedirectResponse
     */
    protected function handlePdfError(\Exception $e, string $pdfType): RedirectResponse
    {
        Log::error("Erreur de génération PDF - {$pdfType}", [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        $message = "Impossible de générer le PDF de {$pdfType}. ";
        
        // Messages spécifiques selon le type d'erreur
        if (str_contains($e->getMessage(), 'memory')) {
            $message .= "Mémoire insuffisante.";
        } elseif (str_contains($e->getMessage(), 'timeout')) {
            $message .= "Le traitement a pris trop de temps.";
        } elseif (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'template')) {
            $message .= "Le modèle de document est introuvable.";
        } else {
            $message .= "Veuillez réessayer.";
        }

        return redirect()->back()->with('error', $message);
    }

    /**
     * Vérifie si des données existent avant de générer un rapport
     * 
     * @param mixed $data
     * @param string $reportType
     * @return bool
     */
    protected function validateDataForReport($data, string $reportType): bool
    {
        if (empty($data) || (is_countable($data) && count($data) === 0)) {
            redirect()->back()->with('warning', 
                "Aucune donnée disponible pour générer le {$reportType}."
            )->send();
            return false;
        }
        return true;
    }

    /**
     * Formate un message de succès
     * 
     * @param string $action L'action effectuée (ex: "créée", "supprimée")
     * @param string $resource La ressource concernée (ex: "facture", "patient")
     * @param mixed $identifier Identifiant de la ressource (optionnel)
     * @return string
     */
    protected function successMessage(string $action, string $resource, $identifier = null): string
    {
        $message = "Le/La {$resource}";
        
        if ($identifier) {
            $message .= " n°{$identifier}";
        }
        
        $message .= " a été {$action} avec succès.";
        
        return $message;
    }
}