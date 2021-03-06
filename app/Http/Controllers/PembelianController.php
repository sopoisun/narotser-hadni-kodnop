<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PembelianRequest;
use App\Http\Requests\PembelianBayarRequest;
use App\Pembelian;
use App\PembelianDetail;
use App\PembelianBayar;
use Carbon\Carbon;
use Auth;
use DB;
use Gate;
use Artisan;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Gate::denies('pembelian.read') ){
            return view(config('app.template').'.error.403');
        }

         $data = [
             'pembelians' => Pembelian::with('detail', 'bayar', 'supplier', 'karyawan')
                ->orderBy('tanggal', 'desc')->paginate(20),
         ];

         return view(config('app.template').".pembelian.table", $data);
    }

    public function detail($id)
    {
        if( Gate::denies('pembelian.read.detail') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'id' => $id,
            'details' => PembelianDetail::with('bahan', 'produk')
                            ->where('pembelian_id', $id)->get(),
        ];

        return view(config('app.template').'.pembelian.table-detail', $data);
    }

    public function create(Request $request)
    {
        if( Gate::denies('pembelian.create') ){
            return view(config('app.template').'.error.403');
        }

        if( !$request->old() ){
            $request->session()->forget('data_pembelian');
        }

        return view(config('app.template').'.pembelian.create');
    }

    public function preview(PembelianRequest $request)
    {
        $denied = false;

        if( !$request->session()->has('data_pembelian') ){
            $denied = true;
        }else{
            $beliBahan = $request->session()->has('data_pembelian.bahan') ? $request->session()->get('data_pembelian.bahan') : [];
            $beliProduk = $request->session()->has('data_pembelian.produk') ? $request->session()->get('data_pembelian.produk') : [];

            if( empty($beliBahan) && empty($beliProduk) ){
                $denied = true;
            }
        }

        if( $denied ){
            return redirect()->back()
                ->withInput()->withErrors(['no_details' => 'Tidak ada barang yang dibeli.']);
        }

        $request->session()->put('info_pembelian', $request->all());

        // Bahan
        $itemBahan = [];
        if( count($beliBahan) ){
            $bahans = \App\StokBahan::with(['bahan'])->whereIn('bahan_id', array_keys($beliBahan))->get();
            foreach($bahans as $bahan){
                $_id        = $bahan->bahan_id;
                $inStok     = $beliBahan[$_id]['stok'];
                $inHarga    = $beliBahan[$_id]['harga'] / $inStok;

                /* Get Avg Price */
                $oldTtl     = $bahan->stok > 0 ? ( $bahan->bahan->harga * $bahan->stok ) : 0;
                $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                $sumInOld   = $oldTtl + $inTtl;
                $qtyTotal   = $bahan->stok + $inStok;
                $avgPrice   = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0;
                /* End Get Avg Price */

                array_push($itemBahan, $beliBahan[$_id] + [
                    'nama'          => $bahan->bahan->nama,
                    'satuan_stok'   => $bahan->bahan->satuan,
                    'old_stok'      => round($bahan->stok, 2),
                    'old_harga'     => $bahan->bahan->harga,
                    'avg_price'     => $avgPrice,
                ]);
            }
        }
        // Produk
        $itemProduk = [];
        if( count($beliProduk) ){
            $produks = \App\StokProduk::with(['produk'])->whereIn('produk_id', array_keys($beliProduk))->get();
            foreach($produks as $produk){
                $_id        = $produk->produk_id;
                $inStok     = $beliProduk[$_id]['stok'];
                $inHarga    = $beliProduk[$_id]['harga'] / $inStok;

                /* Get Avg price */
                $sum = [];
                for($i=0; $i<$produk->stok; $i++){
                    array_push($sum, $produk->produk->hpp);
                }
                for($i=0; $i<$inStok; $i++){
                    array_push($sum, $inHarga);
                }
                $avgPrice = collect($sum)->avg(); // HPP

                $oldTtl     = $produk->stok > 0 ? ( $produk->produk->hpp * $produk->stok ) : 0;
                $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                $sumInOld   = $oldTtl + $inTtl;
                $qtyTotal   = $produk->stok + $inStok;
                $avgPrice   = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0; // HPP
                /* End Get Avg price */

                array_push($itemProduk, $beliProduk[$_id] + [
                    'nama'          => $produk->produk->nama,
                    'satuan_stok'   => $produk->produk->satuan,
                    'old_stok'      => round($produk->stok, 2),
                    'old_harga'     => $produk->produk->hpp,
                    'avg_price'     => $avgPrice,
                ]);
            }
        }

        $data = [
            'items'  => array_merge($itemBahan, $itemProduk),
            'info'   => $request->session()->get('info_pembelian')
        ];

        return view(config('app.template').'.pembelian.preview', $data);
    }

    public function store(Request $request)
    {
        $denied = false;

        if( !$request->session()->has('data_pembelian') ){
            $denied = true;
        }else{
            $beliBahan = $request->session()->has('data_pembelian.bahan') ? $request->session()->get('data_pembelian.bahan') : [];
            $beliProduk = $request->session()->has('data_pembelian.produk') ? $request->session()->get('data_pembelian.produk') : [];

            if( empty($beliBahan) && empty($beliProduk) ){
                $denied = true;
            }
        }

        if( $denied ){
            return redirect('pembelian/add')->withInput()
                ->withErrors(['no_details' => 'Tidak ada barang yang dibeli.']);
        }

        // Pembelian
        $karyawan_id    = Auth::check() ? Auth::user()->karyawan->id : '1';
        $input          = $request->only(['supplier_id', 'tanggal']) + ['karyawan_id' => $karyawan_id];
        $pembelian      = Pembelian::create($input);
        // Pembelian Bayar
        if( $request->get('bayar') != "0" ){
            $input = [
                'pembelian_id'  => $pembelian->id,
                'nominal'       => $request->get('bayar'),
                'karyawan_id'   => $karyawan_id,
                'tanggal'       => $request->get('tanggal'),
            ];
            PembelianBayar::create($input);
            // Update Purchase Account
            Artisan::call('purchase:count', [ 'tanggal' => $pembelian->tanggal->format('Y-m-d') ]);
        }

        # Update [Bahan => Harga, Produk => HPP]
        // Bahan
        if( !empty($beliBahan) ){
            $keys   = array_keys($beliBahan);
            $bahans = \App\StokBahan::with(['bahan'])
                ->whereIn('bahan_id', $keys)
                ->orderBy('bahan_id')
                ->get();

            foreach($bahans as $bahan){
                $bId        = $bahan->bahan_id;
                $inStok     = $beliBahan[$bId]['stok'];
                $inHarga    = $beliBahan[$bId]['harga'] / $inStok;

                if( $bahan->bahan->harga != $inHarga ){

                    $oldTtl     = $bahan->stok > 0 ? ( $bahan->bahan->harga * $bahan->stok ) : 0;
                    $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                    $sumInOld   = $oldTtl + $inTtl;
                    $qtyTotal   = $bahan->stok + $inStok;
                    $harga      = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0;

                    if( $harga != $bahan->bahan->harga ){
                        //echo "<pre>", print_r(['id' => $bId, 'nama' => $bahan->nama, 'harga' => $harga]), "</pre>";
                        \App\Bahan::find($bId)->update(['harga' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'bahan',
                            'relation_id'       => $bId,
                            'old_price'         => $bahan->bahan->harga,
                            'old_stok'          => $bahan->stok,
                            'input_price'       => $inHarga,
                            'input_stok'        => $inStok,
                            'average_with_round'=> $harga,
                            'action'            => "Pembelian #".$pembelian->id,
                        ]);
                    }
                }
            }

            Artisan::call('bahan:count');
        }
        // Produk, Harga => HPP
        if( !empty($beliProduk) ){
            $keys    = array_keys($beliProduk);
            $produks = \App\StokProduk::with(['produk'])
                ->whereIn('produk_id', $keys)
                ->orderBy('produk_id')
                ->get();
            foreach($produks as $produk){
                $pId        = $produk->produk_id;
                $inStok     = $beliProduk[$pId]['stok'];
                $inHarga    = $beliProduk[$pId]['harga'] / $inStok;

                if( $produk->produk->hpp != $inHarga ){

                    $oldTtl     = $produk->stok > 0 ? ( $produk->produk->hpp * $produk->stok ) : 0;
                    $inTtl      = $inStok > 0 ? ( $inHarga * $inStok ) : 0;
                    $sumInOld   = $oldTtl + $inTtl;
                    $qtyTotal   = $produk->stok + $inStok;
                    $harga      = $sumInOld > 0 && $qtyTotal > 0 ? ( $sumInOld / $qtyTotal ) : 0; // HPP

                    if( $harga != $produk->produk->hpp  ){
                        //echo "<pre>", print_r(['id' => $pId, 'nama' => $produk->nama, 'harga' => $harga]), "</pre>";
                        \App\Produk::find($pId)->update(['hpp' => $harga]);
                        \App\AveragePriceAction::create([
                            'type'              => 'produk',
                            'relation_id'       => $pId,
                            'old_price'         => $produk->produk->hpp,
                            'old_stok'          => $produk->stok,
                            'input_price'       => $inHarga,
                            'input_stok'        => $inStok,
                            'average_with_round'=> $harga,
                            'action'            => "Pembelian #".$pembelian->id,
                        ]);
                    }
                }
            }

            Artisan::call('produk:count');
        }

        // Pembelian Detail
        $details  = array_merge($beliBahan, $beliProduk);
        $temp = [];
        foreach( $details as $detail ){
            array_push($temp, ($detail + ['pembelian_id' => $pembelian->id]));
        }
        PembelianDetail::insert($temp);

        $request->session()->forget('data_pembelian');
        $request->session()->forget('info_pembelian');

        return redirect('/pembelian')->with('succcess', 'Sukses simpan data pembelian.');
    }

    public function bayar($id)
    {
        if( Gate::denies('pembelian.bayar') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'id'     => $id,
            'total'  => PembelianDetail::where('pembelian_id', $id)->get()->sum('harga'),
            'bayars' => PembelianBayar::with('karyawan')
                        ->where('pembelian_id', $id)
                        ->orderBy('tanggal')->get(),
        ];

        return view(config('app.template').'.pembelian.table-bayar', $data);
    }

    public function bayarStore(PembelianBayarRequest $request, $id)
    {
        $input = $request->only(['tanggal', 'nominal']) + [
            'pembelian_id' => $id,
            'karyawan_id' => '1',
        ];

        $pembelian = Pembelian::find($id);

        if( PembelianBayar::create($input) ){
            // Update Purchase Account
            Artisan::call('purchase:count', [ 'tanggal' => $pembelian->tanggal->format('Y-m-d') ]);

            return redirect()->back()->with('succcess', 'Sukses simpan pembayaran.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan pembayaran.']);
    }

    public function showItem(Request $request)
    {
        return $request->session()->get('data_pembelian');
    }

    public function saveItem(Request $request)
    {
        if( $request->get('relation_id') && $request->get('qty') && $request->get('satuan')
            && $request->get('harga') && $request->get('stok') && $request->get('type') ){

            $dataPembelian = $request->session()->has('data_pembelian.'.$request->get('type')) ?
                                $request->session()->get('data_pembelian.'.$request->get('type')) : [];

            if( !array_key_exists($request->get('relation_id'),  $dataPembelian) ){
                $id = $request->get('relation_id');
                $dataPembelian[$id] = $request->only(['type', 'relation_id', 'qty', 'satuan', 'harga', 'stok']);
                $request->session()->put('data_pembelian.'.$request->get('type'), $dataPembelian);
                $dataRet = $dataPembelian[$id];
                $dataRet['harga'] = number_format($request->get('harga'), 0, ',', '.');
                return $dataRet;
            }
        }

        return $request->all();
    }

    public function removeItem(Request $request)
    {
        if( $request->get('id') && $request->get('type') ){
            $request->session()->forget('data_pembelian.'.$request->get('type').'.'.$request->get('id'));
        }else{
            return view(config('app.template').'.error.404');
        }
    }
}
