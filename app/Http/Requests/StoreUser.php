<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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
        return [
            'email' => ['required', 'email', 'unique:users'],
            'ruolo' => ['required', Rule::in(['admin', 'account_manager', 'esercente', 'cliente'])],
            'password' => [ 'required' , 'confirmed' ],
            'meta.nome' => 'nullable',
            'meta.cognome' => 'nullable',
            'username' => [ 'required', 'unique:users']
        ];
    }
}
