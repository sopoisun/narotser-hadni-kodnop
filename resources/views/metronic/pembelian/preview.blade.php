@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Pembelian <small>Review Pembelian</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('pembelian') }}">Pembelian</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Review Pembelian</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="invoice">
    <div class="note note-warning">
        <h4 class="block">Warning! Pembelian belum selesai</h4>
        <p>Periksa kembali data bahan / produk yang dibeli.</p>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <h4>Tanggal Pembelian:</h4>
            <ul class="list-unstyled">
                <li style="font-weight:bold;">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $info['tanggal'])->format('d M Y') }}</li>
            </ul>
        </div>
        <div class="col-xs-4">
            <h4>Karyawan:</h4>
            <ul class="list-unstyled">
                <li style="font-weight:bold;">{{ $info['karyawan'] }}</li>
            </ul>
        </div>
        <div class="col-xs-4 invoice-payment">
            <h4>Supplier :</h4>
            <ul class="list-unstyled">
                <li style="font-weight:bold;">{{ $info['supplier'] != '' ? $info['supplier'] : '--'  }}</li>
            </ul>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
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
                    {{--*/ $no = 0; /*--}}
                    @foreach($items as $item)
                    {{--*/ $no++; /*--}}
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ ucfirst($item['type']) }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['qty'].' '.$item['satuan'] }}</td>
                        <td>{{ $item['stok'].' '.$item['satuan_stok'] }}</td>
                        <td style="text-align:right;">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td colspan="4">Total</td>
                        <td style="text-align:right;">{{ number_format(collect($items)->sum('harga'), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 invoice-block" style="float:right;">
            {!! Form::open(['url' => 'pembelian/save', 'id' => 'fpreview']) !!}
                {{ Form::hidden('tanggal', $info['tanggal']) }}
                {{ Form::hidden('total', $info['total']) }}
                {{ Form::hidden('karyawan', $info['karyawan']) }}
                {{ Form::hidden('bayar', $info['bayar']) }}
                {{ Form::hidden('supplier', $info['supplier']) }}
                {{ Form::hidden('supplier_id', $info['supplier_id']) }}
                {{ Form::hidden('sisa', $info['sisa']) }}
                {{ Form::hidden('barangs', null, ['id' => 'barangs']) }}
            {!! Form::close() !!}
            <a class="btn btn-lg green hidden-print" id="btnSimpan"
                style="float:right;" href="javascript:void(0)">
                    Simpan Pembelian <i class="icon-ok"></i>
            </a>
        </div>
        <div style="clear:both;"></div>
    </div>

</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_section')
<script>
    $("#barangs").val('{!! $info['barangs'] !!}');

    $("#btnSimpan").click(function(e){
        $(this).addClass('disabled');
        $("#fpreview").submit();
        e.preventDefault();
    });
</script>
@stop
