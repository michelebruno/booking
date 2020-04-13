<?php

namespace App\Http\Requests;

use App\Prodotto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'required',
                'array'
            ],
            'voci.*.qta' => 'required|int|gte:1',
            'voci.*.tariffa' => 'required|exists:mysql.varianti_tariffa,slug',
            'voci.*.prodotto' => [
                'required',
                Rule::exists("mongodb.prodotti", 'codice' )->where('tipo', Prodotto::TIPO_DEAL)
            ],
            'cliente' => 'required',
            'cliente.email' => ['email' , 'required_if:cliente.id,null'],
            'cliente.id' => [ 'nullable', 'sometimes']
        ];
    }
}
