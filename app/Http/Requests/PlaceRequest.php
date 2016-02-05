<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PlaceRequest extends Request
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
            'nama'    => 'required|min:3',
            'harga'   => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'nama.required'         => 'Nama karyawan tidak boleh kosong.',
            'nama.min'              => 'Nama karyawan harus lebih dari 3 karakter.',
            'harga.required'        => 'Harga tidak boleh kosong.',
            'harga.numeric'         => 'Harga harus angka.',
        ];
    }
}
