<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bank;
use Validator;
use Gate;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('bank.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = ['banks' => Bank::with('tax')->where('active', 1)->get()];
        return view(config('app.template').'.bank.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Gate::denies('bank.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.bank.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required',
            'tax_debit' => 'required|numeric',
            'tax_credit_card' => 'required|numeric',
        ], [
            'nama.required' => 'Nama bank tidak boleh kosong.',
            'tax_debit.required' => 'Pajak debit tidak boleh kosong.',
            'tax_debit.numeric' => 'Pajak debit harus angka.',
            'tax_credit_card.required' => 'Pajak kartu kredit tidak boleh kosong.',
            'tax_credit_card.numeric' => 'Pajak kartu kredit harus angka.',
        ]);

        if($validator->fails()){
            return redirect()
                ->back()->withErrors($validator)
                ->withInput();
        }

        $bank = Bank::create($request->all());

        if( $bank ){
            \App\BankTax::insert([
                [
                    'bank_id' => $bank->id,
                    'type' => 'debit',
                    'tax' => $request->get('tax_debit'),
                ],
                [
                    'bank_id' => $bank->id,
                    'type' => 'credit_card',
                    'tax' => $request->get('tax_credit_card'),
                ],
            ]);

            return redirect('/bank')->with('succcess', 'Sukses simpan data bank.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data bank.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Gate::denies('bank.update') ){
            return view(config('app.template').'.error.403');
        }

        $bank = Bank::find($id);

        if( !$bank ){
            return view(config('app.template').'.error.404');
        }

        $bank->load('tax');

        foreach($bank->tax as $bt){
            $type = 'tax_'.$bt->type;
            $bank->$type = $bt->tax;
        }

        $data = ['bank' => $bank];
        return view(config('app.template').'.bank.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required',
            'tax_debit' => 'required|numeric',
            'tax_credit_card' => 'required|numeric',
        ], [
            'nama.required' => 'Nama bank tidak boleh kosong.',
            'tax_debit.required' => 'Pajak debit tidak boleh kosong.',
            'tax_debit.numeric' => 'Pajak debit harus angka.',
            'tax_credit_card.required' => 'Pajak kartu kredit tidak boleh kosong.',
            'tax_credit_card.numeric' => 'Pajak kartu kredit harus angka.',
        ]);

        if($validator->fails()){
            return redirect()
                ->back()->withErrors($validator)
                ->withInput();
        }

        if( Bank::find($id)->update($request->all()) ){
            $bankTax = \App\BankTax::where('bank_id', $id)->get();

            foreach($bankTax as $bt){
                $tax = $request->get('tax_'.$bt->type);
                \App\BankTax::find($bt->id)->update([
                    'tax' => $tax,
                ]);
            }

            return redirect('/bank')->with('succcess', 'Sukses ubah data bank.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data bank.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Gate::denies('bank.delete') ){
            return view(config('app.template').'.error.403');
        }

        $bank = Bank::find($id);

        if( $bank && $bank->update(['active' => 0]) ){
            return redirect()->back()->with('succcess', 'Sukses hapus data bank '.$bank->nama_bank.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data bank.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return \App\BankTax::join('banks', 'bank_taxes.bank_id', '=', 'banks.id')
                ->where('banks.active', 1)
                    ->where('bank_id', $request->get('id'))
                        ->where('type', $request->get('type'))
                            ->first();
        }

        return Bank::with('tax')->where('active', 1)->get();
    }
}
