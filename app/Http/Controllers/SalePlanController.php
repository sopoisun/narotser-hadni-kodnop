<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SalePlan;
use App\SalePlanDetail;
use App\Produk;
use App\ProdukDetail;
use App\StokProduk;
use App\StokBahan;
use DB;
use Gate;
use Validator;

class SalePlanController extends Controller
{
    public function index()
    {
        if( Gate::denies('saleplan.read') ){
            return view(config('app.template').'.error.403');
        }

        $data = [
            'sale_plans' => SalePlan::orderBy('tanggal', 'desc')->paginate(20),
        ];

        return view(config('app.template').'.saleplan.table', $data);
    }

    public function detail($id)
    {
        if( Gate::denies('saleplan.detail') ){
            return view(config('app.template').'.error.403');
        }

        $salePlan = SalePlan::find($id);

        if( !$salePlan ){
            return view(config('app.template').'.error.404');
        }

        $data = [
            'salePlan'  => $salePlan,
            'details'   => SalePlanDetail::with('produk')
                            ->where('sale_plan_id', $id)->get(),
        ];

        return view(config('app.template').'.saleplan.table-detail', $data);
    }

    public function detailBahan($id)
    {
        if( Gate::denies('saleplan.detail.bahan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_detailBahan($id);

        return view(config('app.template').'.saleplan.bahans', $data);
    }

    public function detailBahanPrint($id)
    {
        if( Gate::denies('saleplan.detail.bahan') ){
            return view(config('app.template').'.error.403');
        }

        $data = $this->_detailBahan($id);

        $print = new \App\Libraries\SalePlan([
            'header' => 'Bahan / Produk yang dibutuhkan Sale Plan '.$data['salePlan']['kode_plan'],
            'data' => $data['display'],
        ]);

        $print->WritePage();
    }

    public function _detailBahan($id)
    {
        $salePlanDetail = SalePlanDetail::where('sale_plan_id', $id)->get();
        $produkIds = array_column($salePlanDetail->toArray(), 'produk_id');
        $display = [];

        # Produk no Bahan (Trading sale)
        // get data produk where no bahan, where id in $produkIds
        $produkNoBahan = Produk::leftJoin('produk_details', 'produks.id', '=', 'produk_details.produk_id')
            ->whereIn('produks.id', $produkIds)
            ->whereNull('produk_details.produk_id')->where('active', 1)
            ->select('produks.*')->get();

        if( $produkNoBahan->count() ){
            $produkNoBahanIds = array_column($produkNoBahan->toArray(), 'id');
            $stokProduk = StokProduk::whereIn('produk_id', $produkNoBahanIds)->get();

            foreach ($produkNoBahan as $p) {
                $_salePlanDetail = $salePlanDetail->where('produk_id', (string)$p->id)->first();
                $_stokProduk = $stokProduk->where('produk_id', (string)$p->id)->first();
                $stok_yg_dibeli = $_stokProduk->stok - ($_salePlanDetail->qty);
                array_push($display, [
                    'type'  => 'Produk',
                    'nama'  => $p->nama,
                    'harga' => $p->hpp,
                    'satuan_pakai'      => $p->satuan,
                    'qty_diperlukan'    => $_salePlanDetail->qty,
                    'stok'              => $_stokProduk->stok,
                    'stok_yg_dibeli'    => ($stok_yg_dibeli) > 0 ? 0 : abs($stok_yg_dibeli),
                ]);
            }
        }
        #end produk no bahan (Trading sale)

        $bahans = ProdukDetail::with(['bahan'])->whereIn('produk_id', $produkIds)
            ->select([
                    'produk_details.id', 'produk_details.produk_id',
                    'produk_details.bahan_id', DB::raw('produk_details.qty as qty'),
                ])->get();
        // push qty saleplan to bahan produk
        foreach ($bahans as $b) {
            $_salePlanDetail = $salePlanDetail->where('produk_id', $b->produk_id)->first();
            $b['saleplancount'] = $_salePlanDetail->qty;
        }
        // group bahan by id
        $bahansGroup    = $bahans->groupBy('bahan_id');
        // sum bahan
        $sumFromGroup   = [];
        foreach ($bahansGroup as $key => $val) {
            $sumFromGroup[$key] = collect($val)->sum(function($i){
                return $i['qty'] * $i['saleplancount'];
            });
        }

        /*return [
            $bahans,
            $bahansGroup,
            $sumFromGroup,
            array_keys($sumFromGroup)
        ];*/

        $bahanIds = array_keys($sumFromGroup);

        $bahanStok  = StokBahan::whereIn('bahan_id', $bahanIds)->get();

        foreach ($bahansGroup as $b) {
            $i = $b[0]['bahan'];
            $_stokBahan = $bahanStok->where('bahan_id', (string)$i->id)->first();
            $stok_yg_dibeli = $_stokBahan->stok - ($sumFromGroup[$i->id]);
            array_push($display, [
                'type'  => 'Bahan',
                'nama'  => $i->nama,
                'harga' => $i->harga,
                'satuan_pakai'      => $i->satuan,
                'qty_diperlukan'    => $sumFromGroup[$i->id],
                'stok'              => $_stokBahan->stok,
                'stok_yg_dibeli'    => ($stok_yg_dibeli) > 0 ? 0 : abs($stok_yg_dibeli),
            ]);
        }

        return [
            'salePlan'  => SalePlan::find($id),
            'display'   => $display,
        ];
    }

    public function create(Request $request)
    {
        if( Gate::denies('saleplan.create') ){
            return view(config('app.template').'.error.403');
        }

        return view(config('app.template').'.saleplan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'saleplan_detail_ids' => 'required',
        ], [
            'tanggal.required'  => 'Tanggal tidak boleh kosong',
            'tanggal.date'      => 'Input harus tanggal',
            'saleplan_detail_ids.required' => 'Tidak ada produk yang dijual',
        ]);

        if( $validator->fails() ){
            return redirect()->back()->withInput()
                ->withErrors($validator);
        }

        $tanggal = $request->get('tanggal');

        $salePlan = SalePlan::where('tanggal',$tanggal)->first();

        $dataSalePlan = json_decode($request->get('saleplan_detail_ids'), true);

        if( !$salePlan ){
            $salePlan = SalePlan::create([
                'kode_plan' => "SP-".date('dmY-his'),
                'tanggal'   => $tanggal,
            ]);

            foreach ($dataSalePlan as $d) {
                SalePlanDetail::create([
                    'sale_plan_id'  => $salePlan->id,
                    'produk_id'     => $d['id'], // produk_id
                    'qty'           => $d['qty'],
                    'harga'         => $d['harga'],
                ]);
            }
        }else{
            $salePlanDetailOld = SalePlanDetail::where('sale_plan_id', $salePlan->id)->get();

            foreach ($dataSalePlan as $d) {
                $check = $salePlanDetailOld->where('produk_id', $d['id'])->first();

                if( !$check ){
                    SalePlanDetail::create([
                        'sale_plan_id'  => $salePlan->id,
                        'produk_id'     => $d['id'], // produk_id
                        'qty'           => $d['qty'],
                        'harga'         => $d['harga'],
                    ]);
                }else{
                    SalePlanDetail::find($check->id)
                        ->update([
                            'qty'   => ($check->qty + $d['qty']),
                        ]);
                }
            }
        }

        return redirect('/pembelian/saleplan');
    }

    public function itemSession(Request $request)
    {
        return $request->session()->has('data_saleplan') ?
            $request->session()->get('data_saleplan') : [];
    }

    public function saveItem(Request $request)
    {
        if( $request->get('id') && $request->get('qty') && $request->get('harga') ){
            $dataSalePlan = $request->session()->has('data_saleplan') ?
                                $request->session()->get('data_saleplan') : [];

            if( !array_key_exists($request->get('id'),  $dataSalePlan) ){
                $id = $request->get('id');
                $dataSalePlan[$id] = $request->only(['id', 'nama', 'qty', 'harga']);
                $request->session()->put('data_saleplan', $dataSalePlan);
                $dataRet = $request->all();
                $dataRet['harga']       = number_format($request->get('harga'), 0, ',', '.');
                $dataRet['subtotal']    = number_format($request->get('harga')*$request->get('qty'), 0, ',', '.');
                return $dataRet;
            }
        }
    }

    public function removeItem(Request $request)
    {
        if( $request->get('id') ){
            $request->session()->forget('data_saleplan.'.$request->get('id'));
        }else{
            return view(config('app.template').'.error.404');
        }
    }
}
