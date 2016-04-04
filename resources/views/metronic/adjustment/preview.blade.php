@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Adjustment <small>Review Adjustment</small>
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
            <li><a href="javascript:void(0)">Review Adjustment</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="invoice">
    <div class="note note-warning">
        <h4 class="block">Warning! Adjustment belum selesai</h4>
        <p>Periksa kembali data bahan / produk yang di adjustment.</p>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <h4>Tanggal Adjustment:</h4>
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
            <h4>keterangan :</h4>
            <ul class="list-unstyled">
                <li style="font-weight:bold;">{{ $info['keterangan'] != '' ? $info['keterangan'] : '--'  }}</li>
            </ul>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="6">Adjustment Pengurangan (-)</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($items['reduction']))
                    {{--*/
                        $no = 0;
                        $total = 0;
                    /*--}}
                    @foreach($items['reduction'] as $item)
                    {{--*/
                        $no++;
                        $total += $item['harga'];
                    /*--}}
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ ucfirst($item['type']) }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td style="text-align:right;">{{ number_format(($item['harga']/$item['qty']), 0, ',', '.') }}</td>
                        <td>{{ $item['qty'].' '.$item['satuan'] }}</td>
                        <td style="text-align:right;">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td colspan="4">Total</td>
                        <td style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                    @else
                    <tr><td colspan="6">Tidak Ada Adjustment Pengurangan</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-xs-12">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="6">Adjustment Penambahan (+)</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($items['increase']))
                    {{--*/
                        $no = 0;
                        $total = 0;
                    /*--}}
                    @foreach($items['increase'] as $item)
                    {{--*/
                        $no++;
                        $total += $item['harga']*$item['qty'];
                    /*--}}
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ ucfirst($item['type']) }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td style="text-align:right;">{{ number_format(($item['harga']/$item['qty']), 0, ',', '.') }}</td>
                        <td>{{ $item['qty'].' '.$item['satuan'] }}</td>
                        <td style="text-align:right;">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td colspan="4">Total</td>
                        <td style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                    @else
                    <tr><td colspan="6">Tidak Ada Adjustment Pengurangan</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 invoice-block" style="float:right;">
            {!! Form::open(['url' => 'adjustment/save', 'id' => 'fpreview']) !!}
                {{ Form::hidden('tanggal', $info['tanggal']) }}
                {{ Form::hidden('karyawan', $info['karyawan']) }}
                {{ Form::hidden('keterangan', $info['keterangan']) }}
                {{ Form::hidden('adjustment_reduction', null, ['id' => 'adjustment_reduction']) }}
                {{ Form::hidden('adjustment_increase', null, ['id' => 'adjustment_increase']) }}
            {!! Form::close() !!}
            <a class="btn btn-lg green hidden-print" id="btnSimpan"
                style="float:right;" href="javascript:void(0)">
                    Simpan Adjustment <i class="icon-ok"></i>
            </a>
        </div>
        <div style="clear:both;"></div>
    </div>

</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_section')
<script>
    $("#adjustment_reduction").val('{!! $info['adjustment_reduction'] !!}');
    $("#adjustment_increase").val('{!! $info['adjustment_reduction'] !!}');

    $("#btnSimpan").click(function(e){
        $(this).addClass('disabled');
        $("#fpreview").submit();
        e.preventDefault();
    });
</script>
@stop
