<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SupplierRequest extends Request
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
            'nama'      => 'required|min:3',
            'no_hp'     => 'required|numeric|digits_between:11,12',
        ];
    }

    public function messages()
    {
        return [
            'nama.required'         => 'Nama Supplier tidak boleh kosong.',
            'nama.min'              => 'Nama Supplier harus lebih dari 3 karakter.',
            'no_hp.required'        => 'No HP tidak boleh kosong',
            'no_hp.numeric'         => 'Input harus Angka',
            'no_hp.digits_between'  => 'Input harus 11 sampai 12 digit.',
        ];
    }
}
