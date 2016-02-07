<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Account;
use App\AccountSaldo;
use App\Bank;
use Validator;
use Carbon\Carbon;
use App\Order;
use App\PembelianBayar;
use DB;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::all();

        $data = [
            'accounts' => $accounts,
            'states'   => Account::$data_states,
            'types'    => Account::$types
        ];

        return view(config('app.template').'.account.table', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = ['types' => Account::$types];
        return view(config('app.template').'.account.create', $data);
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
            'nama_akun' => 'required',
        ], [
            'nama_akun.required' => 'Nama akun tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all() + ['data_state' => 'input'];

        if( Account::create($input) ){
            return redirect('/account')->with('succcess', 'Sukses simpan data akun.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan data akun.']);
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
        $account = Account::find($id);

        if( !$account ){
            abort(404);
        }

        $data = [
            'account'   => $account,
            'types'     => Account::$types,
        ];
        return view(config('app.template').'.account.update', $data);
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
            'nama_akun' => 'required',
        ], [
            'nama_akun.required' => 'Nama akun tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if( Account::find($id)->update($request->all()) ){
            return redirect('/account')->with('succcess', 'Sukses ubah data akun.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah data akun.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function inputSaldo(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $saldos = AccountSaldo::with('account', 'bank')->where('tanggal', $tanggal)->get();

        $data = [
            'saldos' => $saldos,
            'types' => Account::$types,
            'tanggal' => Carbon::parse($tanggal),
        ];
        return view(config('app.template').'.account.saldo.input-table', $data);
    }

    public function inputManual()
    {
        $data = [
            'accounts' => Account::where('data_state', 'input')->lists('nama_akun', 'id'),
        ];
        return view(config('app.template').'.account.saldo.input', $data);
    }

    public function check(Request $request)
    {
        $account = Account::find($request->get('account_id'));

        if( $account ){
            if( $account->relation == 'bank' ){
                $data = [
                    'banks'     => Bank::lists('nama_bank', 'id'),
                    'selected'  => ( $request->get('selected') ? $request->get('selected') : null )
                ];
                return view(config('app.template').'.account.saldo.bank-element', $data);
            }
        }

        return '';
    }

    public function saveInputManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'nominal' => 'required',
        ], [
            'tanggal.required'  => 'Tanggal tidak boleh kosong.',
            'tanggal.date'      => 'Input harus tanggal.',
            'nominal.required'  => 'Nominal tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $account_id = $request->get('account_id');
        $account    = Account::find($account_id);

        $input      = $request->all() + ['type' => $account->type];

        if( AccountSaldo::create($input) ){
            return redirect('/account/saldo?tanggal='.$request->get('tanggal'))
                        ->with('succcess', 'Sukses simpan saldo.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal simpan saldo.']);
    }

    public function editInputManual($id)
    {
        $accountSaldo = AccountSaldo::find($id);

        if( !$accountSaldo ){
            abort(404);
        }

        $data = [
            'accounts' => Account::where('data_state', 'input')->lists('nama_akun', 'id'),
            'accountSaldo' => $accountSaldo
        ];
        return view(config('app.template').'.account.saldo.update', $data);
    }

    public function saveEditInputManual(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'nominal' => 'required',
        ], [
            'tanggal.required'  => 'Tanggal tidak boleh kosong.',
            'tanggal.date'      => 'Input harus tanggal.',
            'nominal.required'  => 'Nominal tidak boleh kosong.',
        ]);

        if( $validator->fails() ){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $account_id = $request->get('account_id');
        $account    = Account::find($account_id);

        $input      = $request->all() + ['type' => $account->type];

        if( AccountSaldo::find($id)->update($input) ){
            return redirect('/account/saldo?tanggal='.$request->get('tanggal'))
                        ->with('succcess', 'Sukses ubah saldo.');
        }

        return redirect()->back()->withErrors(['failed' => 'Gagal ubah saldo.']);
    }

    public function jurnal(Request $request)
    {
        $type       = $request->get('type') ? $request->get('type') : 'cash';
        $tanggal    = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');
        $CTanggal   = Carbon::createFromFormat('Y-m-d', $tanggal);
        $to_tanggal = $request->get('to_tanggal') ? $request->get('to_tanggal') : date('Y-m-d');
        $CToTanggal = Carbon::createFromFormat('Y-m-d', $to_tanggal);
        $CYesterday = $CTanggal->copy()->addDays(-1);
        $yesterday  = $CYesterday->format('Y-m-d');

        $start  = $CTanggal->copy();
        $end    = $CToTanggal->copy();

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        if( $type == 'cash' ){
            // Penjualan for sisa saldo
            $firstDate  = Order::where('state', 'Closed')->orderBy('tanggal')->limit(1)->first()->tanggal->format('Y-m-d');
            $where      = "(orders.tanggal between '$firstDate' AND '$yesterday') AND order_bayars.type_bayar = 'tunai' AND";
            $totalPenjualan = ConvertRawQueryToArray(Account::TotalPenjualan($where))[0]['total'];

            // Pembelian for sisa saldo
            $firstDate  = PembelianBayar::orderBy('tanggal')->limit(1)->first()->tanggal->format('Y-m-d');
            $where      = "(pembelian_bayars.`tanggal` BETWEEN '$firstDate' AND '$yesterday')";
            $totalPembelian = ConvertRawQueryToArray(Account::TotalPembelian($where))[0]['total'];

            // Account Saldo for sisa saldo
            $firstDate  = AccountSaldo::orderBy('tanggal')->limit(1)->first()->tanggal->format('Y-m-d');
            $where      = "(account_saldos.`tanggal` BETWEEN '$firstDate' AND '$yesterday')";
            $column     = "IF(account_saldos.`type` = 'debet', account_saldos.`nominal`, -ABS(account_saldos.`nominal`))";
            $totalAccountSaldo = ConvertRawQueryToArray(Account::TotalAccountSaldo($column, $where))[0]['total'];

            $tableTemp = [];

            // Sisa Saldo Pertanggal $tanggal (-1)
            $sisaSaldo = array_sum([
                'total_penjualan'       => $totalPenjualan,
                'total_pembelian'       => -abs($totalPembelian),
                'total_account_saldo'   => $totalAccountSaldo,
            ]);
            $saldo = $sisaSaldo;
            array_push($tableTemp, [
                'tanggal'   => $CYesterday->format('Y-m-d'),
                'keterangan' => 'Sisa Saldo '.$CYesterday->format('d M Y'),
                'debet'     => '',
                'kredit'    => '',
                'saldo'     => $sisaSaldo,
            ]);

            // Penjualan range $tanggal s/d $to_tanggal
            $where      = "(orders.tanggal BETWEEN '$tanggal' AND '$to_tanggal' ) AND order_bayars.type_bayar = 'tunai' AND";
            $groupBy    = "GROUP BY orders.tanggal";
            $penjualans = ConvertRawQueryToArray(Account::TotalPenjualan($where, $groupBy));
            $penjualanGroup = collect($penjualans)->groupBy('tanggal');

            // Pembelian range $tanggal s/d $to_tanggal
            $where      = "(pembelian_bayars.`tanggal` BETWEEN '$tanggal' AND '$to_tanggal' ) GROUP BY pembelian_bayars.tanggal";
            $pembelians = ConvertRawQueryToArray(Account::TotalPembelian($where));
            $pembelianGroup = collect($pembelians)->groupBy('tanggal');

            // Account Saldo range $tanggal s/d $to_tanggal
            $accountSaldos  = AccountSaldo::with(['account', 'bank'])->whereBetween('tanggal', [$tanggal, $to_tanggal])
                ->select(['account_saldos.*', DB::raw('tanggal as _date')])->get()->groupBy('_date');

            foreach($dates as $date){
                // Penjualan
                if( isset($penjualanGroup[$date->format('Y-m-d')]) ){
                    $pjl = $penjualanGroup[$date->format('Y-m-d')];
                    foreach($pjl as $p){
                        $saldo += $p['total'];
                        array_push($tableTemp, [
                            'tanggal'       => $date->format('Y-m-d'),
                            'keterangan'    => 'Penjualan',
                            'debet'         => $p['total'],
                            'kredit'        => '',
                            'saldo'         => $saldo,
                        ]);
                    }
                }
                // Pembelian
                if( isset($pembelianGroup[$date->format('Y-m-d')]) ){
                    $pbl = $pembelianGroup[$date->format('Y-m-d')];
                    foreach($pbl as $p){
                        $saldo -= $p['total'];
                        array_push($tableTemp, [
                            'tanggal'       => $date->format('Y-m-d'),
                            'keterangan'    => 'Pembelian',
                            'debet'         => '',
                            'kredit'        => $p['total'],
                            'saldo'         => $saldo,
                        ]);
                    }
                }
                // Account Saldo
                if( isset($accountSaldos[$date->format('Y-m-d')]) ){
                    $acs = $accountSaldos[$date->format('Y-m-d')];
                    foreach($acs as $as){
                        if( $as['type'] == 'kredit' ){
                            $saldo -= $as['nominal'];
                            array_push($tableTemp, [
                                'tanggal'       => $date->format('Y-m-d'),
                                'keterangan'    => $as['account']['nama_akun'].' '.$as['bank']['nama_bank'],
                                'debet'         => '',
                                'kredit'        => $as['nominal'],
                                'saldo'         => $saldo,
                            ]);
                        }else{ // debet
                            $saldo += $as['nominal'];
                            array_push($tableTemp, [
                                'tanggal'       => $date->format('Y-m-d'),
                                'keterangan'    => $as['account']['nama_akun'].' '.$as['bank']['nama_bank'],
                                'debet'         => $as['nominal'],
                                'kredit'        => '',
                                'saldo'         => $saldo,
                            ]);
                        }
                    }
                }
            }

            $tableTemp = collect($tableTemp)->groupBy('tanggal');
        }else{
            // Penjualan for sisa saldo
            $firstDate  = Order::where('state', 'Closed')->orderBy('tanggal')->limit(1)->first()->tanggal->format('Y-m-d');
            $where      = "(orders.tanggal between '$firstDate' AND '$yesterday') AND order_bayars.type_bayar != 'tunai' AND";
            $totalPenjualan = ConvertRawQueryToArray(Account::TotalPenjualan($where))[0]['total'];

            // Account Saldo for sisa saldo
            $firstDate  = AccountSaldo::orderBy('tanggal')->limit(1)->first()->tanggal->format('Y-m-d');
            $where      = "(account_saldos.`tanggal` BETWEEN '$firstDate' AND '$yesterday') and relation_id is not null";
            $column     = "IF(account_saldos.`type` = 'kredit', account_saldos.`nominal`, -ABS(account_saldos.`nominal`))";
            $totalAccountSaldo = ConvertRawQueryToArray(Account::TotalAccountSaldo($column, $where))[0]['total'];

            $tableTemp = [];

            // Sisa Saldo Pertanggal $tanggal (-1)
            $sisaSaldo = array_sum([
                'total_penjualan'       => $totalPenjualan,
                'total_account_saldo'   => $totalAccountSaldo,
            ]);

            $saldo = $sisaSaldo;
            array_push($tableTemp, [
                'tanggal'   => $CYesterday->format('Y-m-d'),
                'keterangan' => 'Sisa Saldo '.$CYesterday->format('d M Y'),
                'debet'     => '',
                'kredit'    => '',
                'saldo'     => $sisaSaldo,
            ]);

            // Penjualan range $tanggal s/d $to_tanggal
            $where      = "(orders.tanggal BETWEEN '$tanggal' AND '$to_tanggal' ) AND order_bayars.type_bayar != 'tunai' AND";
            $groupBy    = "GROUP BY orders.tanggal";
            $penjualans = ConvertRawQueryToArray(Account::TotalPenjualan($where, $groupBy));
            $penjualanGroup = collect($penjualans)->groupBy('tanggal');

            // Account Saldo range $tanggal s/d $to_tanggal
            $accountSaldos  = AccountSaldo::with(['account', 'bank'])->whereNotNull('relation_id')
                ->whereBetween('tanggal', [$tanggal, $to_tanggal])
                ->select(['account_saldos.*', DB::raw('tanggal as _date')])->get()
                ->groupBy('_date');

            foreach($dates as $date){
                // Penjualan
                if( isset($penjualanGroup[$date->format('Y-m-d')]) ){
                    $pjl = $penjualanGroup[$date->format('Y-m-d')];
                    foreach($pjl as $p){
                        $saldo += $p['total'];
                        array_push($tableTemp, [
                            'tanggal'       => $date->format('Y-m-d'),
                            'keterangan'    => 'Penjualan',
                            'debet'         => $p['total'],
                            'kredit'        => '',
                            'saldo'         => $saldo,
                        ]);
                    }
                }
                // Account Saldo
                if( isset($accountSaldos[$date->format('Y-m-d')]) ){
                    $acs = $accountSaldos[$date->format('Y-m-d')];
                    foreach($acs as $as){
                        if( $as['type'] == 'debet' ){
                            $saldo -= $as['nominal'];
                            array_push($tableTemp, [
                                'tanggal'       => $date->format('Y-m-d'),
                                'keterangan'    => $as['account']['nama_akun'].' '.$as['bank']['nama_bank'],
                                'debet'         => '',
                                'kredit'        => $as['nominal'],
                                'saldo'         => $saldo,
                            ]);
                        }else{ // kredit
                            $saldo += $as['nominal'];
                            array_push($tableTemp, [
                                'tanggal'       => $date->format('Y-m-d'),
                                'keterangan'    => $as['account']['nama_akun'].' '.$as['bank']['nama_bank'],
                                'debet'         => $as['nominal'],
                                'kredit'        => '',
                                'saldo'         => $saldo,
                            ]);
                        }
                    }
                }
            }

            $tableTemp = collect($tableTemp)->groupBy('tanggal');
        }

        $data = [
            'tanggal'   => $CTanggal,
            'to_tanggal'=> $CToTanggal,
            'type'      => $type,
            'types'     => ['cash' => 'Kas', 'bank' => 'Bank'],
            'table'     => $tableTemp,
        ];

        return view(config('app.template').'.account.saldo.jurnal', $data);
    }
}
