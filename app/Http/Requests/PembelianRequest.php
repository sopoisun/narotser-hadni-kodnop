<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PembelianRequest extends Request
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
            'bayar' => 'required|integer|less_than_eq:total',
            'total' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'tanggal.required' => 'Tanggal tidak boleh kosong.',
            'tanggal.date' => 'format tanggal salah.',
            'bayar.required' => 'Pembayaran tidak boleh kosong.',
            'bayar.integer' => 'Input harus angka.',
            'bayar.less_than_eq' => 'Pembayaran harus <= total.',
            'total.required' => 'Total tidak boleh kosong.',
            'Total.integer' => 'Input harus angka',
            'total.min' => 'Total tidak boleh 0',
        ];
    }
}
