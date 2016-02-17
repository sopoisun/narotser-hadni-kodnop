<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Produk;
use App\Bahan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = \App\Setting::first();

        // Produk harga jual dibawah ambang batas prosentase laba
        $produkLabaWarning = Produk::allWithStokAndPrice()
                    ->having('laba_procentage', '<', $setting->laba_procentage_warning)
                        ->get();

        // Produk stok dibawah ambang batas stok
        $produkStokWarning = Produk::Stok()->get()->filter(function($item){
            return $item->sisa_stok < $item->qty_warning;
        });

        // Bahan stok dibawah ambang batas stok
        $bahanStokWarning = Bahan::stok()->get()->filter(function($item){
            return $item->sisa_stok < $item->qty_warning;
        });

        $yesterday  = Carbon::now();
        $start      = $yesterday->copy()->addDays(-7);
        $end        = $yesterday->copy();

        // for query
        $startBetween   = $start->format('Y-m-d');
        $endBetween     = $end->format('Y-m-d');

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        $dates = collect($dates)->forPage(1, 7);

        $reports = \App\Order::ReportGroup("(orders.`tanggal` BETWEEN '$startBetween' AND '$endBetween')", "GROUP BY tanggal");
        $reports = ConvertRawQueryToArray($reports);

        $dataLastWeek = [];
        foreach($dates as $date){
            $idx = array_search($date->format("Y-m-d"), array_column($reports, "tanggal"));
            $val = 0;
            if(false !== $idx){
                $d  = $reports[$idx];
                $val = $d['jumlah'];
            }

            array_push($dataLastWeek, [
                $date->format('d M Y'),
                $val,
            ]);
        }

        $data = [
            'dataLastWeek'      => $dataLastWeek,
            'produkLabaWarning' => $produkLabaWarning,
            'produkStokWarning' => $produkStokWarning,
            'bahanStokWarning'  => $bahanStokWarning,
        ];

        return view(config('app.template').'.dashboard.dashboard', $data);
    }
}
