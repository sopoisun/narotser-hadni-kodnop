<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\KaryawanRequest;
use App\Karyawan;
use Carbon\Carbon;
use DB;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'karyawans' => Karyawan::with('user.roles')->get(),
        ];

        return view(config('app.template').'.karyawan.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(config('app.template').'.karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KaryawanRequest $request)
    {
        if( Karyawan::create($request->all()) ){
            return redirect('/karyawan')->with('succcess', 'Sukses simpan data karyawan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data karyawan.']);
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
        $karyawan = Karyawan::find($id);

        if( !$karyawan ){
            abort(404);
        }

        $data = ['karyawan' => $karyawan];

        return view(config('app.template').'.karyawan.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KaryawanRequest $request, $id)
    {
        if( Karyawan::find($id)->update($request->all()) ){
            return redirect('/karyawan')->with('succcess', 'Sukses ubah data karyawan.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data karyawan.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);

        if( $karyawan && $karyawan->delete() ){
            return redirect()->back()->with('succcess', 'Sukses hapus data '.$karyawan->nama.'.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal hapus data karyawan.']);
    }

    public function ajaxLoad(Request $request)
    {
        if( $request->get('id') ){
            return Karyawan::find($request->get('id'));
        }elseif($request->get('ids')){
            return Karyawan::whereIn('id', explode('+', $request->get('ids')))->get();
        }else{
            $karyawan = Karyawan::where('nama', 'like', '%'.$request->get('q').'%');
            if( $request->get('foruser') ){
                $karyawan = $karyawan->whereNull('user_id');
            }
            return $karyawan->get();
        }
    }

    public function reportPerbulan(Request $request)
    {
        if( $request->get('karyawan_id') ){
            $id = $request->get('karyawan_id');
            $bulan = $request->get('bulan') ? $request->get('bulan') : date('Y-m');
            $karyawan = Karyawan::find($id);

            if( !$karyawan ){
                abort(404);
            }

            $start      = Carbon::parse($bulan)->startOfMonth();
            $end        = Carbon::parse($bulan)->endOfMonth();

            $dates = [];
            while ($start->lte($end)) {
                $dates[] = $start->copy();
                $start->addDay();
            }

            $reports = \App\Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->where('orders.state', 'Closed')
                ->where('orders.karyawan_id', $id)
                ->where(DB::raw('SUBSTRING(orders.tanggal, 1, 7)'), $bulan)
                ->select(['orders.tanggal', 'orders.tanggal as _date',
                    DB::raw('SUM(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))total_penjualan')
                ])
                ->groupBy('orders.tanggal')
                ->get();

            $data = [
                'tanggal'   => Carbon::parse($bulan),
                'karyawan'  => $karyawan,
                'dates'     => $dates,
                'reports'   => $reports,
            ];

            return view(config('app.template').'.karyawan.report-perbulan', $data);
        }else{
            abort(404);
        }
    }

    public function reportPertahun(Request $request)
    {
        if( $request->get('karyawan_id') ){
            $id     = $request->get('karyawan_id');
            $tahun  = $request->get('tahun') ? $request->get('tahun') : date('Y');
            $karyawan = Karyawan::find($id);

            if( !$karyawan ){
                abort(404);
            }

            $start      = Carbon::createFromFormat('Y', $tahun)->startOfYear();
            $end        = Carbon::createFromFormat('Y', $tahun)->endOfYear();

            $months = [];
            while ($start->lte($end)) {
                $months[] = $start->copy();
                $start->addMonth();
            }

            $reports = \App\Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->where('orders.state', 'Closed')
                ->where('orders..karyawan_id', $id)
                ->where(DB::raw('SUBSTRING(orders.tanggal, 1, 4)'), $tahun)
                ->select(['orders.tanggal', DB::raw('SUBSTRING(orders.tanggal, 1, 7) as bulan'),
                    DB::raw('SUM(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))total_penjualan')
                ])
                ->groupBy(DB::raw('SUBSTRING(orders.tanggal, 1, 7)'))
                ->get();

            $data = [
                'tanggal'   => Carbon::createFromFormat('Y', $tahun),
                'karyawan'  => $karyawan,
                'months'    => $months,
                'reports'   => $reports,
            ];

            return view(config('app.template').'.karyawan.report-pertahun', $data);
        }else{
            abort(404);
        }
    }
}
