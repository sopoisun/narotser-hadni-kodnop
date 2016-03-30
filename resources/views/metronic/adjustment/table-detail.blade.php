@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Detail Adjustment <small>Daftar detail adjustment</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/adjustment') }}">Adjustment</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Detail Adjustment #{{ $id }}</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
@if( collect($details)->where('state', 'increase')->count() )
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Daftar Detail Adjustment #{{ $id }} [ Penambahan Stok ]</div>
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
                                <th>Type</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/
                                $no = 0;
                                $total = 0;
                            /*--}}
                            @foreach(collect($details)->where('state', 'increase') as $detail)
                            {{--*/
                                $no++;
                                $total += $detail->harga*$detail->qty;
                            /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ ucfirst($detail->type) }}</td>
                                <td>{{ $detail->type == "bahan" ? $detail->bahan->nama : $detail->produk->nama }}</td>
                                {{--*/ $satuan = ( $detail->type == "bahan" ) ? $detail->bahan->satuan : $detail->produk->satuan  /*--}}
                                <td style="text-align:right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->qty.' '.$satuan }}</td>
                                <td style="text-align:right;">{{ number_format($detail->harga*$detail->qty, 0, ',', '.') }}</td>
                                <td>{{ $detail->keterangan }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td colspan="4">Total</td>
                                <td style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
@endif

@if( collect($details)->where('state', 'reduction')->count() )
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Daftar Detail Adjustment #{{ $id }} [ Pengurangan Stok ]</div>
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
                                <th>Type</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/
                                $no = 0;
                                $total = 0;
                            /*--}}
                            @foreach(collect($details)->where('state', 'reduction') as $detail)
                            {{--*/
                                $no++;
                                $total += $detail->harga*$detail->qty;
                            /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ ucfirst($detail->type) }}</td>
                                <td>{{ $detail->type == "bahan" ? $detail->bahan->nama : $detail->produk->nama }}</td>
                                {{--*/ $satuan = ( $detail->type == "bahan" ) ? $detail->bahan->satuan : $detail->produk->satuan  /*--}}
                                <td style="text-align:right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->qty.' '.$satuan }}</td>
                                <td style="text-align:right;">{{ number_format($detail->harga*$detail->qty, 0, ',', '.') }}</td>
                                <td>{{ $detail->keterangan }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td colspan="4">Total</td>
                                <td style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
@endif
<!-- END PAGE CONTENT-->
@stop
