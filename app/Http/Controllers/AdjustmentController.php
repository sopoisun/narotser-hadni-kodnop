<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdjustmentRequest;
use App\Adjustment;
use App\AdjustmentDetail;
use Auth;
use DB;
use Gate;

class AdjustmentController extends Controller
{
    public function index(Request $request)
    {
        if( Gate::denies('adjustment.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'adjustments' => Adjustment::with('karyawan', 'detail')->get(),
        ];

        return view(config('app.template').'.adjustment.table', $data);
    }

    public function detail($id)
    {
        if( Gate::denies('adjustment.detail') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'id' => $id,
            'details' => AdjustmentDetail::with('bahan', 'produk')->where('adjustment_id', $id)->get(),
        ];

        //return collect($data['details'])->where('state', 'increase');

        return view(config('app.template').'.adjustment.table-detail', $data);
    }

    public function create(Request $request)
    {
        if( Gate::denies('adjustment.create') ){
            return view(config('app.template').'.error.403');
        }

        if( !$request->old() ){
            $request->session()->forget('data_adjustment');
        }

        $data = [
            'types'     => Adjustment::$types,
            'states'    => Adjustment::$states,
        ];

        return view(config('app.template').'.adjustment.create', $data);
    }

    public function store(AdjustmentRequest $request)
    {
        $denied = false;
        if( !$request->session()->has('data_adjustment') ){
            $denied = true;
        }else{
            // Reduction ( Pengurangan )
            $data_adjustment_reduction          = $request->session()->get('data_adjustment.reduction');
            $data_adjustment_reduction_bahan    = isset($data_adjustment_reduction['bahan']) ? $data_adjustment_reduction['bahan'] : [];
            $data_adjustment_reduction_produk   = isset($data_adjustment_reduction['produk']) ? $data_adjustment_reduction['produk'] : [];

            // Increase ( Penambahan )
            $data_adjustment_increase           = $request->session()->get('data_adjustment.increase');
            $data_adjustment_increase_bahan     = isset($data_adjustment_increase['bahan']) ? $data_adjustment_increase['bahan'] : [];
            $data_adjustment_increase_produk    = isset($data_adjustment_increase['produk']) ? $data_adjustment_increase['produk'] : [];


            if( empty($data_adjustment_reduction_bahan) && empty($data_adjustment_reduction_produk)
                && empty($data_adjustment_increase_bahan) && empty($data_adjustment_increase_produk) ){
                $denied = true;
            }
        }

        if( $denied ){
            return redirect()->back()
                ->withInput()->withErrors(['no_details' => 'Tidak ada barang yang di adjustment.']);
        }

        // Adjustment
        $karyawan_id    = Auth::check() ? Auth::user()->karyawan->id : '1';
        $input          = $request->only(['keterangan', 'tanggal']) + ['karyawan_id' => $karyawan_id];
        $adjustment     = Adjustment::create($input);

        # Update Data [Bahan => Harga, Produk => HPP]
        // Bahan
        if( !empty($data_adjustment_increase_bahan) ){
            $keys   = array_keys($data_adjustment_increase_bahan);
            $bahans = \App\Bahan::stok()
                ->whereIn('bahans.id', $keys)
                ->orderBy('bahans.id')
                ->get();

            foreach($bahans as $bahan){
                $bId = $bahan->id;

                if( $bahan->harga != $data_adjustment_increase_bahan[$bId]['harga'] ){
                    /*$sum = [];
                    for($i=0; $i<$bahan->sisa_stok; $i++){
                        array_push($sum, $bahan->harga);
                    }
                    for($i=0; $i<$data_adjustment_increase_bahan[$bId]['qty']; $i++){
                        array_push($sum, $data_adjustment_increase_bahan[$bId]['harga']);
                    }
                    $harga = Pembulatan(collect($sum)->avg());*/

                    $oldPrice   = $bahan->harga;
                    $oldStok    = $bahan->sisa_stok;
                    $inputPrice = $data_adjustment_increase_bahan[$bId]['harga'];
                    $inputStok  = $data_adjustment_increase_bahan[$bId]['qty'];
                    $harga      = Pembulatan((($oldPrice*$oldStok)+($inputPrice*$inputStok))/($oldStok+$inputStok));

                    if( $harga != $bahan->harga ){
                        \App\Bahan::find($bId)->update(['harga' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'bahan',
                            'relation_id'       => $bId,
                            'old_price'         => $bahan->harga,
                            'old_stok'          => $oldStok,
                            'input_price'       => $data_adjustment_increase_bahan[$bId]['harga'],
                            'input_stok'        => $inputStok,
                            'average_with_round'=> $harga,
                            'action'            => "Adjustment Increase #".$adjustment->id,
                        ]);
                    }
                }
            }
        }
        // Produk, Harga => HPP
        if( !empty($data_adjustment_increase_produk) ){
            $keys    = array_keys($data_adjustment_increase_produk);
            $produks = \App\Produk::stok()
                ->whereIn('produks.id', $keys)
                ->orderBy('produks.id')
                ->get();
            foreach($produks as $produk){
                $pId = $produk->id;

                if( $produk->hpp != $data_adjustment_increase_produk[$pId]['harga'] ){
                    /*$sum = [];
                    for($i=0; $i<$produk->sisa_stok; $i++){
                        array_push($sum, $produk->hpp);
                    }
                    for($i=0; $i<$data_adjustment_increase_produk[$pId]['qty']; $i++){
                        array_push($sum, $data_adjustment_increase_produk[$pId]['harga']);
                    }
                    $harga = Pembulatan(collect($sum)->avg()); // HPP*/

                    $oldPrice   = $produk->hpp;
                    $oldStok    = $produk->sisa_stok;
                    $inputPrice = $data_adjustment_increase_produk[$pId]['harga'];
                    $inputStok  = $data_adjustment_increase_produk[$pId]['qty'];
                    $harga      = Pembulatan((($oldPrice*$oldStok)+($inputPrice*$inputStok))/($oldStok+$inputStok));

                    if( $harga != $produk->hpp  ){
                        \App\Produk::find($pId)->update(['hpp' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'produk',
                            'relation_id'       => $pId,
                            'old_price'         => $produk->hpp,
                            'old_stok'          => $oldStok,
                            'input_price'       => $data_adjustment_increase_produk[$pId]['harga'],
                            'input_stok'        => $inputStok,
                            'average_with_round'=> $harga,
                            'action'            => "Adjustment Increase #".$adjustment->id,
                        ]);
                    }
                }
            }
        }

        // Adjustment Detail
        $data_adjustment = array_merge($data_adjustment_reduction_bahan, $data_adjustment_reduction_produk);
        $data_adjustment = array_merge($data_adjustment_increase_bahan, $data_adjustment);
        $data_adjustment = array_merge($data_adjustment_increase_produk, $data_adjustment);

        $details = [];
        foreach($data_adjustment as $da){
            $temp = $da;
            $temp['adjustment_id'] = $adjustment->id;
            array_push($details, $temp);
        }
        AdjustmentDetail::insert($details);

        $request->session()->forget('data_adjustment');

        return redirect('/adjustment')->with('succcess', 'Sukses simpan adjustment bahan / produk.');
    }

    public function showTest(Request $request)
    {
        $action = $request->get('act') ? $request->get('act') : 'bahan';
        $action = strtolower($action);

        if( $action == 'produk' ){
            return \App\Produk::stok()
                ->orderBy('produks.id')
                ->get();
        }elseif( $action == 'bahan' ){
            return \App\Bahan::stok()
                ->orderBy('bahans.id')
                ->get();
        }else{
            abort(404);
        }
    }

    public function showSession(Request $request)
    {
        return $request->session()->get('data_adjustment');
    }

    public function itemSave(Request $request)
    {
        if( $request->get('relation_id') && $request->get('type') && $request->get('state')
            && $request->get('qty') && $request->get('harga') ){
            $dataAdjustment = $request->session()->has('data_adjustment.'.$request->get('state').'.'.$request->get('type')) ?
                                     $request->session()->get('data_adjustment.'.$request->get('state').'.'.$request->get('type')) : [];
            if( !array_key_exists($request->get('relation_id'),  $dataAdjustment) ){
                $id     = $request->get('relation_id');
                $data   = $request->only(['type', 'state', 'relation_id', 'harga', 'qty']) + ['keterangan' => $request->get('item_keterangan')];
                $dataAdjustment[$id] = $data;
                $request->session()->put('data_adjustment.'.$request->get('state').'.'.$request->get('type'), $dataAdjustment);
                // add subtotal
                $subtotal   = number_format($data['harga'] * $data['qty'], 0, ',', '.');
                $data       = $data + ['subtotal' => $subtotal];
                return $data;
            }
        }else{
            abort(404);
        }
    }

    public function itemRemove(Request $request)
    {
        if( $request->get('id') && $request->get('type') && $request->get('state') ){
            $request->session()->forget('data_adjustment.'.$request->get('state').'.'.$request->get('type').'.'.$request->get('id'));
        }else{
            abort(404);
        }
    }
}
