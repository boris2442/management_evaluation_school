<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SpecialiteRequest extends FormRequest
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
        $specialiteId = $this->route('specialite');
        return [
            'nom_specialite' => [ // CORRIGÉ : utilise 'nom_specialite'
                'required',
                'string',
                'max:255',
                // Règle d'unicité sur le nom
                Rule::unique('specialites', 'nom_specialite')->ignore($specialiteId),
            ],
            'code_unique' => [ // CORRIGÉ : utilise 'code_unique'
                'required', // Rendons le code unique obligatoire
                'string',
                'max:10',
                Rule::unique('specialites', 'code_unique')->ignore($specialiteId),
            ],
            'description' => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'nom_specialite.unique' => 'Ce nom de spécialité est déjà utilisé.',
            'code_unique.unique' => 'Ce code unique de spécialité est déjà utilisé.',
        ];
    }
}
