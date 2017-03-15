@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Produk / Bahan Yang Dipakai dari Sale Plan #{{ $salePlan->kode_plan }}
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/pembelian/saleplan/'.$salePlan->id.'/detail/bahan/print') }}" target="_blank">Print</a></li>
                </ul>
            </li>
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
            <li>
                <a href="{{ url('/pembelian/saleplan/'.$salePlan->id.'/detail') }}">Detail Sale Plan #{{ $salePlan->kode_plan }}</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Bahan / Produk Yang Dipakai</a>
            </li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Produk / Bahan Yang Dipakai</div>
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
                                <th>Nama</th>
                                <th>Type</th>
                                <th>Qty Diperlukan</th>
                                <th>Stok yg Ada</th>
                                <th>Qty Harus Dibeli</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/
                                $no = 0;
                                $total = 0;
                            /*--}}
                            @foreach($display as $d)
                            {{--*/
                                $no++;
                                $total += $d['harga']*$d['stok_yg_dibeli'];
                                $txt = '';
                                if( $d['stok_yg_dibeli'] <= 0 ){
                                    $txt = 'class="danger"';
                                }
                            /*--}}
                            <tr {!! $txt !!}>
                                <td>{{ $no }}</td>
                                <td>{{ $d['nama'] }}</td>
                                <td>{{ $d['type'] }}</td>
                                <td>{{ $d['qty_diperlukan'].' '.$d['satuan_pakai'] }}</td>
                                <td>{{ $d['stok'].' '.$d['satuan_pakai'] }}</td>
                                <td>{{ $d['stok_yg_dibeli'].' '.$d['satuan_pakai'] }}</td>
                                <td style="text-align:right;">{{ number_format($d['harga'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($d['harga']*$d['stok_yg_dibeli'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr style="background-color:#CCC;">
                                <td></td>
                                <td colspan="6">Total</td>
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
