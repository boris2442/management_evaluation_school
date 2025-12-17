<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AnneeAcademiqueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Par défaut, le $id de l'Année Académique est null pour le store (création)
        // et il est présent dans l'URL pour le update (modification).
        $anneeId = $this->route('annee_academique');
        return [
            'libelle' => [
                'required',
                'string',
                'max:255',
                // Règle d'unicité: ignore l'ID si on est en mode 'update'
                Rule::unique('annee_academiques', 'libelle')->ignore($anneeId),
            ],
            'date_debut' => 'required|date',
            // La date de fin doit être après la date de début
            'date_fin' => 'required|date|after:date_debut',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'libelle.unique' => 'Ce libellé d\'année académique est déjà utilisé.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
        ];
    }
}
