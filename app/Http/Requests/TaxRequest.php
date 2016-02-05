<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TaxRequest extends Request
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
            'type' => 'required',
            'procentage' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Type tidak boleh kosong.',
            'procentage.required' => 'Prosentase tidak boleh kosong.',
            'procentage.numeric' => 'Input harus angka.',
            'procentage.min' => 'Input minimal harus 0',
            'procentage.max' => 'Input maksimal harus 100',
        ];
    }
}
