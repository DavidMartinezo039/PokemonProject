<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSetRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación para la solicitud.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png',
        ];

        if ($this->isMethod('put')) {
            $rules['name'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Personaliza los mensajes de error.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe estar en formato jpeg, png, jpg o gif.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
