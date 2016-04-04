@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Detail Pembelian <small>Daftar detail pembelian bahan / produk</small>
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
            <li><a href="javascript:void(0)">Detail Pembelian #{{ $id }}</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Detail Pembelian #{{ $id }}</div>
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
                                <th>Qty</th>
                                <th>Stok</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/
                                $no = 0;
                                $total = 0;
                            /*--}}
                            @foreach($details as $detail)
                            {{--*/
                                $no++;
                                $total += $detail->harga;
                                $satuan_stok = $detail->type == 'bahan' ? $detail->bahan->satuan : $detail->produk->satuan;
                            /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ ucfirst($detail->type) }}</td>
                                <td>{{ $detail->type == 'bahan' ? $detail->bahan->nama : $detail->produk->nama }}</td>
                                <td>{{ $detail->qty.' '.$detail->satuan }}</td>
                                <td>{{ $detail->stok.' '.$satuan_stok }}</td>
                                <td style="text-align:right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr style="background-color:#CCC;">
                                <td></td>
                                <td colspan="4">Total</td>
                                <td style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
