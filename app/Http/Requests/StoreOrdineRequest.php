<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdineRequest extends FormRequest
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
            'voci' => [
                'required'
            ],
            'voci.*.qta' => 'required|int',
            'voci.*.tariffa_id' => 'required|exists:tariffe,id',
            'cliente.email' => 'email' // TODO
        ];
    }
}
