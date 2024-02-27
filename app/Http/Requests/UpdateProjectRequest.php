<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:150|min:2',
            'description' => 'required|min:5',
            'cover_image' => 'nullable|image|max:1024'
        ];
    }

    // FUNZIONE CHE RESTITUISCE I MESSAGGI PERSONALIZZATI PER GLI ERRORI
    public function messages()
    {
        return [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.max' => 'Il titolo deve contere al massimo 150 caratteri.',
            'title.min' => 'Il titolo deve contere almeno 2 caratteri.',
            'description.required' => 'La descrizione è obbligatoria.',
            'description.min' => 'La descrizione deve contenere almeno 5 caratteri.',
            'cover_image.image' => 'Il file selezionato deve essere una immagine in formato valido (.jph, .jpeg, .webp, .png)',
            'cover_image.max' => 'Il file selezionato supera le dimensioni massime di 1024 Kb. Riprova.',
        ];
    }
}
