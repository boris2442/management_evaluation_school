<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ModuleStoreRequest extends FormRequest
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
 /**
     * Determine if the user is authorized to make this request.
     */
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'specialite_id' => ['required', 'exists:specialites,id'],

            // CODE_MODULE EST RETIRÉ : Il est généré automatiquement dans le Modèle (Module::creating).
            // 'code_module' => ['required', 'string', 'max:10', 'unique:modules,code_module'], 

            'nom_module' => ['required', 'string', 'max:255'],
            'coef_module' => ['required', 'integer', 'min:1', 'max:100'],

            // Règle d'unicité pour l'ordre (reste composée pour la spécialité)
            'ordre' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('modules')->where(fn($query) => $query->where('specialite_id', $this->specialite_id)),
            ],

            'enseignants' => ['nullable', 'array'],
            'enseignants.*' => ['exists:users,id'],
        ];
    }
}
