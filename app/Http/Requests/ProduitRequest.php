<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProduitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $produitId = $this->route('produit') ? $this->route('produit')->id : null;

        return [
            'designation'   => ['required', 'string', 'max:255', 'unique:produits,designation,' . $produitId],
            'categorie'     => ['required', 'string', 'max:255'],
            'qte_stock'     => ['required', 'integer'],
            'qte_alerte'    => ['required', 'integer'],
            'prix_unitaire' => ['required', 'integer'],
        ];
    }

}
