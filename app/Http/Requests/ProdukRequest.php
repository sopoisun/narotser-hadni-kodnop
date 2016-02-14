<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProdukRequest extends Request
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
        $rules = [
            'nama'  => 'required',
            'satuan' => 'required',
            'qty_warning' => 'required|numeric',
        ];

        if( $this->get('konsinyasi') ){
            $rules['supplier_id'] = 'required|exists:suppliers,id';
        }

        if( $this->get('use_mark_up') == 'Tidak' ){
            $rules['harga'] = 'required|numeric';
        }else{
            $rules['mark_up'] = 'required|numeric|between:0,100';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nama.required'         => 'Nama produk tidak boleh kosong.',
            'satuan.required'       => 'Satuan tidak boleh kosong.',
            'supplier_id.required'  => 'Supplier tidak boleh kosong.',
            'supplier_id.exists'    => 'Supplier tidak terdaftar di database.',
            'harga.required'        => 'Harga tidak boleh kosong.',
            'harga.numeric'         => 'Harga harus angka.',
            'mark_up.required'      => 'Mark up tidak boleh kosong.',
            'mark_up.numeric'       => 'Mark up harus angka.',
            'mark_up.between'       => 'Mark up harus angka decimal ( 0.01 - 0.99).',
            'qty_warning.required'   => 'Qty Warning tidak boleh kosong.',
            'qty_warning.numeric'   => 'Qty Warning harus angka.',
        ];
    }
}
