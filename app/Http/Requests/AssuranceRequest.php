<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssuranceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assuranceId = $this->route('assurance')?->id;

        return [
            'nom' => 'required|string|max:255',
            'numero_police' => 'required|string|max:255|unique:assurances,numero_police,' . $assuranceId,
            'type_id' => 'required|exists:types,id',
            'montant_prime' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,expire',
        ];
    }
}
