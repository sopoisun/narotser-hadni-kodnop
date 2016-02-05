<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PembelianBayarRequest extends Request
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
            'tanggal' => 'required|date',
            'nominal' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'tanggal.required' => 'Tanggal tidak boleh kosong.',
            'tanggal.date' => 'Input harus tanggal.',
            'nominal.required' => 'Bayar tidak boleh kosong.',
            'nominal.integer' => 'Input harus angka',
            'nominal.min' => 'Bayar tidak boleh 0.',
        ];
    }
}
