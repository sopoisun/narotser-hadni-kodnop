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
            'adjustments' => Adjustment::with('karyawan', 'detail')
                ->orderBy('tanggal', 'desc')->paginate(20),
        ];

        return view(config('app.template').'.adjustment.table', $data);
    }

    public function detail($id)
    {
        if( Gate::denies('adjustment.read.detail') ){
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

    public function preview(AdjustmentRequest $request)
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

        $request->session()->put('info_adjustment', $request->all());

        $bahanIds = array_merge(array_keys($data_adjustment_reduction_bahan), array_keys($data_adjustment_increase_bahan));
        $produkIds = array_merge(array_keys($data_adjustment_reduction_produk), array_keys($data_adjustment_increase_produk));

        $dBahans = [];
        if( count($bahanIds) ){
            $dBahans = \App\Bahan::stok()->whereIn('bahans.id', $bahanIds)->get();
            $temp = [];
            foreach($dBahans as $bahan){
                $_id = $bahan->id;
                $temp[$_id] = $bahan;
            }
            $dBahans = $temp;

            // Bahan
            # Reduction
            $temp = [];
            foreach(array_keys($data_adjustment_reduction_bahan) as $val){
                $temp[$val] = $data_adjustment_reduction_bahan[$val] + ['nama' => $dBahans[$val]['nama'], 'satuan' => $dBahans[$val]['satuan']];
            }
            $data_adjustment_reduction_bahan = $temp;
            # Increase
            $temp = [];
            foreach(array_keys($data_adjustment_increase_bahan) as $val){
                $in_increase_bahan = $data_adjustment_increase_bahan[$val];
                $inStok     = $data_adjustment_increase_bahan[$val]['qty'];
                $inHarga    = $data_adjustment_increase_bahan[$val]['harga'];

                /* Get Avg price */
                $oldTtl     = $dBahans[$val]['sisa_stok'] > 0 ? ( $dBahans[$val]['harga'] * $dBahans[$val]['sisa_stok'] ) : 0;
                $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                $sumInOld   = $oldTtl + $inTtl;
                $qtyTotal   = $dBahans[$val]['sisa_stok'] + $inStok;
                $avgPrice   = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0;
                /* End Get Avg price */

                $temp[$val] = $in_increase_bahan + [
                    'nama'      => $dBahans[$val]['nama'],
                    'satuan'    => $dBahans[$val]['satuan'],
                    'old_stok'  => round($dBahans[$val]['sisa_stok'], 2),
                    'old_harga' => $dBahans[$val]['harga'],
                    'avg_price' => $avgPrice,
                ];
            }
            $data_adjustment_increase_bahan = $temp;
        }

        $dProduks = [];
        if( count($produkIds) ){
            $dProduks = \App\Produk::stok()->whereIn('produks.id', $produkIds)->get();
            $temp = [];
            foreach($dProduks as $produk){
                $_id = $produk->id;
                $temp[$_id] = $produk;
            }
            $dProduks = $temp;

            // Produk
            # Reduction
            $temp = [];
            foreach(array_keys($data_adjustment_reduction_produk) as $val){
                $temp[$val] = $data_adjustment_reduction_produk[$val] + ['nama' => $dProduks[$val]['nama'], 'satuan' => $dProduks[$val]['satuan']];
            }
            $data_adjustment_reduction_produk = $temp;
            # Increase
            $temp = [];
            foreach(array_keys($data_adjustment_increase_produk) as $val){
                $in_increase_produk = $data_adjustment_increase_produk[$val];
                $inStok     = $data_adjustment_increase_produk[$val]['qty'];
                $inHarga    = $data_adjustment_increase_produk[$val]['harga'];

                /* Get Avg price */
                $oldTtl     = $dProduks[$val]['sisa_stok'] > 0 ? ( $dProduks[$val]['hpp'] * $dProduks[$val]['sisa_stok'] ) : 0;
                $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                $sumInOld   = $oldTtl + $inTtl;
                $qtyTotal   = $dProduks[$val]['sisa_stok'] + $inStok;
                $avgPrice   = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0; // HPP
                /* End Get Avg price */

                $temp[$val] = $in_increase_produk + [
                    'nama'      => $dProduks[$val]['nama'],
                    'satuan'    => $dProduks[$val]['satuan'],
                    'old_stok'  => round($dProduks[$val]['sisa_stok'], 2),
                    'old_harga' => $dProduks[$val]['hpp'],
                    'avg_price' => $avgPrice,
                ];
            }
            $data_adjustment_increase_produk = $temp;
        }

        $data = [
            'items' => [
                'reduction' => array_merge($data_adjustment_reduction_bahan, $data_adjustment_reduction_produk),
                'increase' => array_merge($data_adjustment_increase_bahan, $data_adjustment_increase_produk)
            ],
            'info'  => $request->session()->get('info_adjustment'),
        ];

        return view(config('app.template').'.adjustment.preview', $data);
    }

    public function store(Request $request)
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
            return redirect('adjustment/add')->withInput()
                ->withErrors(['no_details' => 'Tidak ada barang yang di adjustment.']);
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
                $bId        = $bahan->id;
                $inStok     = $data_adjustment_increase_bahan[$bId]['qty'];
                $inHarga    = $data_adjustment_increase_bahan[$bId]['harga'];

                if( $bahan->harga != $inHarga ){

                    $oldTtl     = $bahan->sisa_stok > 0 ? ( $bahan->harga * $bahan->sisa_stok ) : 0;
                    $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                    $sumInOld   = $oldTtl + $inTtl;
                    $qtyTotal   = $bahan->sisa_stok + $inStok;
                    $harga      = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0;

                    if( $harga != $bahan->harga ){
                        \App\Bahan::find($bId)->update(['harga' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'bahan',
                            'relation_id'       => $bId,
                            'old_price'         => $bahan->harga,
                            'old_stok'          => $bahan->sisa_stok,
                            'input_price'       => $inHarga,
                            'input_stok'        => $inStok,
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
                $pId        = $produk->id;
                $inStok     = $data_adjustment_increase_produk[$pId]['qty'];
                $inHarga    = $data_adjustment_increase_produk[$pId]['harga'];

                if( $produk->hpp != $inHarga ){

                    $oldTtl     = $produk->sisa_stok > 0 ? ( $produk->hpp * $produk->sisa_stok ) : 0;
                    $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                    $sumInOld   = $oldTtl + $inTtl;
                    $qtyTotal   = $produk->sisa_stok + $inStok;
                    $harga      = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0; // HPP

                    if( $harga != $produk->hpp  ){
                        \App\Produk::find($pId)->update(['hpp' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'produk',
                            'relation_id'       => $pId,
                            'old_price'         => $produk->hpp,
                            'old_stok'          => $produk->sisa_stok,
                            'input_price'       => $inHarga,
                            'input_stok'        => $inStok,
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
        $request->session()->forget('info_adjustment');

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
                // push subtotal with currency format
                $data['subtotal']   = number_format(($data['harga'] * $data['qty']), 0, ',', '.'); // get subtotal from $harga * $qty
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
