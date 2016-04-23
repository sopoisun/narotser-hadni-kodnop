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
        return view(config('app.template').'.dashboard.dashboard');
    }

    public function Chart()
    {
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
        $dataLabelLastWeek = [];
        foreach($dates as $date){
            $idx = array_search($date->format("Y-m-d"), array_column($reports, "tanggal"));
            $val = 0;
            if(false !== $idx){
                $d  = $reports[$idx];
                $val = $d['jumlah'];
            }
            $dataLabelLastWeek[] = $date->format('d M Y');
            $dataLastWeek[] = $val;
        }

        return [
            'label'         => $dataLabelLastWeek,
            'data'          => $dataLastWeek,
        ];
    }

    public function PriceTreshold()
    {
        $setting = setting();

        // Produk harga jual dibawah ambang batas prosentase laba
        $produkLabaWarning = Produk::allWithStokAndPrice()
                    ->having('laba_procentage', '<', $setting->laba_procentage_warning)
                        ->get();

        $data = [
            'data' => $produkLabaWarning,
        ];

        return view(config('app.template').'.dashboard.price', $data);
    }

    public function ProdukStok()
    {
        // Produk stok dibawah ambang batas stok
        $produkStokWarning = Produk::stok()->get()->filter(function($item){
            return $item->sisa_stok < $item->qty_warning;
        });

        $data = [
            'data' => $produkStokWarning,
            'header' => 'Produk',
        ];

        return view(config('app.template').'.dashboard.stok', $data);
    }

    public function BahanStok()
    {
        // Bahan stok dibawah ambang batas stok
        $bahanStokWarning = Bahan::stok()->get()->filter(function($item){
            return $item->sisa_stok < $item->qty_warning;
        });

        $data = [
            'data' => $bahanStokWarning,
            'header' => 'Bahan',
        ];

        return view(config('app.template').'.dashboard.stok', $data);
    }
}
