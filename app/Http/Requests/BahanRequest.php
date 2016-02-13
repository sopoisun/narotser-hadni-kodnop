<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BahanRequest extends Request
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
            'nama'      => 'required',
            'satuan'    => 'required',
            //'harga'     => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'nama.required'         => 'Nama Bahan tidak boleh kosong.',
            'satuan.required'       => 'Satuan tidak boleh kosong.',
            //'harga.required'        => 'Harga tidak boleh kosong.',
            //'harga.numeric'         => 'Harga harus angka.',
        ];
    }
}
