<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\Setting;
use DB;

class ApiController extends Controller
{
    public function transaksi(Request $request)
    {
        $tanggal = $request->get('tanggal') ? $request->get('tanggal') : date('Y-m-d');

        $orders = Order::with('karyawan')->where(DB::raw('SUBSTRING(tanggal, 1, 10)'), $tanggal)->get();

        $data = [];
        $i = 0;
        foreach($orders as $order)
        {
            $i++;
            array_push($data, [
                'no'        => $i,
                'id'        => $order->id,
                'nota'      => $order->nota,
                'status'    => $order->state,
                'karyawan'  => $order->karyawan->nama,
            ]);
        }

        $display['penjualan'] = $data;

        return $display;
    }

    public function detail(Request $request)
    {
        if( $request->get('id') ){
            $id = $request->get('id');

            $orderDetails = OrderDetail::with('order')->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->join('produks', 'order_details.produk_id', '=', 'produks.id')
                ->where('order_details.order_id', $id)->select([
                    'order_details.id', 'order_details.produk_id', 'produks.nama', 'order_details.harga_jual',
                    'order_details.qty as qty_ori', DB::raw('ifnull(order_detail_returns.qty, 0) as qty_return'),
                    DB::raw('(order_details.qty - ifnull(order_detail_returns.qty, 0))qty'),
                    DB::raw('(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))subtotal'),
                ])->get();

            $order = Order::with('place.place')->find($id);

            $data = [];
            $i = 0;
            foreach($orderDetails as $od)
            {
                $i++;
                array_push($data, [
                    'no'            => $i,
                    'nama_produk'   => $od->nama,
                    'harga'         => number_format($od->harga_jual, 0, ",", "."),
                    'qty'           => $od->qty,
                    'subtotal'      => number_format($od->subtotal, 0, ",", "."),
                ]);
            }

            foreach($order->place as $op){
                if( $op->harga > 0 ){
                    $i++;
                    array_push($data, [
                        'no'            => $i,
                        'nama_produk'   => "Reservasi ".$op->place->nama,
                        'harga'         => number_format($op->harga, 0, ",", "."),
                        'qty'           => 1,
                        'subtotal'      => number_format($op->harga, 0, ",", "."),
                    ]);
                }
            }

            $display['detail_penjualan'] = $data;

            return $display;
        }else{
            abort(500);
        }
    }

    public function bayar(Request $request)
    {
        if( $request->get('id') ){
            $id = $request->get('id');

            $order = Order::with('bayar', 'tax.tax', 'bayarBank.bank', 'place.place')->find($id);

            $total = $orderDetails = OrderDetail::with('order')->leftJoin('order_detail_returns', 'order_details.id', '=', 'order_detail_returns.order_detail_id')
                ->join('produks', 'order_details.produk_id', '=', 'produks.id')
                ->where('order_details.order_id', $id)->select([
                    'order_details.id', 'order_details.produk_id', 'produks.nama', 'order_details.harga_jual',
                    'order_details.qty as qty_ori', DB::raw('ifnull(order_detail_returns.qty, 0) as qty_return'),
                    DB::raw('(order_details.qty - ifnull(order_detail_returns.qty, 0))qty'),
                    DB::raw('(order_details.harga_jual * (order_details.qty - ifnull(order_detail_returns.qty, 0)))subtotal'),
                ])->get()->sum('subtotal');

            foreach($order->place as $op){
                if( $op->harga > 0 ){
                    $total += $op->harga;
                }
            }

            $tax_procentage = round($order->tax->procentage);
            $tax            = round($total * ( $tax_procentage / 100 ));
            $tax_bayar_procentage = ( $order->bayarBank != null ) ? round($order->bayarBank->tax_procentage) : 0;
            $tax_bayar  = round(( $total + $tax ) * ( $tax_bayar_procentage / 100 ));
            $jumlah     = round($total + $tax + $tax_bayar);
            $sisa       = round($jumlah - $order->bayar->diskon);
            $kembali    = round($order->bayar->bayar - $sisa);

            return [
                'total'         => number_format($total, 0, ",", "."),
                'tax_pro'       => $order->tax->procentage,
                'tax'           => number_format($tax, 0, ",", "."),
                'tax_bayar_pro' => $tax_bayar_procentage,
                'tax_bayar'     => number_format($tax_bayar, 0, ",", "."),
                'jumlah'        => number_format($jumlah, 0, ",", "."),
                'diskon'        => number_format($order->bayar->diskon, 0, ",", "."),
                'sisa'          => number_format($sisa, 0, ",", "."),
                'bayar'         => number_format($order->bayar->bayar, 0, ",", "."),
                'kembali'       => number_format($kembali, 0, ",", "."),
            ];

        }else{
            abort(500);
        }
    }

    public function setting()
    {
        return Setting::first();
    }
}
