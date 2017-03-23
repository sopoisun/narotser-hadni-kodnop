@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Sale Plan Detail <small>Detail Rencana Penjualan Produk</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/pembelian') }}">Pembelian</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/pembelian/saleplan') }}">Sale Plan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Detail Sale Plan #{{ $salePlan->kode_plan }}</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Daftar Detail Sale Plan #{{ $salePlan->kode_plan }}</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Sold</th>
                                <th>Income</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/
                                $no = 0;
                                $total = 0;
                                $totalIncome = 0;
                            /*--}}
                            @foreach($details as $detail)
                            {{--*/
                                $no++;
                                $subtotal = $detail->harga*$detail->qty;
                                $subtotal = ($subtotal >= 0) ? $subtotal : 0;
                                $total += $subtotal;
                                $totalIncome += $detail->harga*$detail->sold;
                            /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $detail->produk->nama }}</td>
                                <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td style="text-align:right;">{{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td>{{ $detail->sold }}</td>
                                <td style="text-align:right;">{{ number_format($detail->harga*$detail->sold, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr style="background-color:#CCC;">
                                <td></td>
                                <td colspan="3">Total</td>
                                <td style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                                <td style="text-align:right;">{{ number_format($totalIncome, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-xs-4 invoice-block" style="float:right;">
                        <a class="btn btn-lg yellow hidden-print"
                            style="float:right;" href="{{ url('/pembelian/saleplan/'.$salePlan->id.'/detail/bahan') }}">
                                Lihat bahan <i class="icon-search"></i>
                        </a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
