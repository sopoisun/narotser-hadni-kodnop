@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Detail Transaksi<small> #{{ $order->nota }}</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Transaksi / Order</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Detail Transaksi #{{ $order->nota }}</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<ul  class="nav nav-tabs">
    <li class="active"><a href="#tab_form_bayar_order" data-toggle="tab">Close Order</a></li>
    <li class=""><a href="#tab_produks" data-toggle="tab">Daftar Produk</a></li>
</ul>

<div  class="tab-content">
    <div class="tab-pane fade active in" id="tab_form_bayar_order">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-reorder"></i> Form Pembayaran Order
                        </div>
                        <div class="tools">
                            <a href="" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="" class="reload"></a>
                            <a href="" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formInputPembayaran']) !!}
                            <div class="form-body">
                                {{--*/ $total = (collect($orderDetail)->sum('subtotal') + collect($orderPlaces)->sum('harga') + $order->bayar->service_cost); /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                            <div class="col-md-8">
                                                {{ Form::text('tanggal', $order->tanggal->format('d M Y'), ['class' => 'form-control', 'id' => 'tanggal', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total" class="col-md-3 control-label">Total</label>
                                            <div class="col-md-8">
                                                {{ Form::text('total', $total, ['class' => 'form-control number_f', 'id' => 'total', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--*/
                                    $tax_procentage = $order->tax->procentage;
                                    $tax = ROUND($total * ( $tax_procentage / 100 ));
                                /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="places" class="col-md-3 control-label">Type Pajak</label>
                                            <div class="col-md-8">
                                                {{ Form::text('type_pajak', $order->tax->tax->type, ['class' => 'form-control', 'id' => 'type_pajak', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tax" class="col-md-3 control-label" id="tax-label">Tax {{ $tax_procentage }} %</label>
                                            <div class="col-md-8">
                                                {{ Form::text('tax', $tax, ['class' => 'form-control number_f', 'id' => 'tax', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--*/
                                    $tax_bayar_procentage = ( $order->bayarBank != NULL ) ? $order->bayarBank->tax_procentage : 0;
                                    $tax_bayar = ROUND(( $total + $tax ) * ( $tax_bayar_procentage / 100 ));
                                /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_bayar" class="col-md-3 control-label">Type Bayar</label>
                                            <div class="col-md-8">
                                                {{ Form::text('type_bayar', ucwords(str_replace('_', ' ', $order->bayar->type_bayar)), ['class' => 'form-control', 'id' => 'type_bayar', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tax_bayar" class="col-md-3 control-label" id="tax-bayar-label">Tax Bayar {{ $tax_bayar_procentage }} %</label>
                                            <div class="col-md-8">
                                                {{ Form::text('tax_bayar', $tax_bayar, ['class' => 'form-control number_f', 'id' => 'tax_bayar',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--*/
                                    $bank = ( $order->bayarBank != "" ) ? $order->bayarBank->bank->nama_bank : '--';
                                    $total_akhir = ( $total + $tax + $tax_bayar );
                                /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank" class="col-md-3 control-label">Bank</label>
                                            <div class="col-md-8">
                                                {{ Form::text('bank', $bank, ['class' => 'form-control', 'id' => 'bank',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_akhir" class="col-md-3 control-label">Total Akhir</label>
                                            <div class="col-md-8">
                                                {{ Form::text('total_akhir', $total_akhir, ['class' => 'form-control number_f', 'id' => 'total_akhir',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--*/
                                    $tempat = implode(', ', array_column(array_column($order->place->toArray(), 'place'), 'nama'));
                                /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="places" class="col-md-3 control-label">Tempat</label>
                                            <div class="col-md-8">
                                                {{ Form::text('places', $tempat, ['class' => 'form-control', 'id' => 'places', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('diskon')) has-error @endif">
                                            <label for="diskon" class="col-md-3 control-label">Diskon</label>
                                            <div class="col-md-8">
                                                {{--*/
                                                    $opt = ['class' => 'form-control number_f', 'id' => 'diskon'];
                                                    if( Gate::denies('order.update') ){
                                                        $opt['readonly'] = 'readonly';
                                                    }
                                                /*--}}
                                                {{ Form::text('diskon', $order->bayar->diskon, $opt) }}
                                                @if($errors->has('diskon'))<span class="help-block">{{ $errors->first('diskon') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--*/ $jumlah = $total_akhir - $order->bayar->diskon; /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="places" class="col-md-3 control-label">Kasir</label>
                                            <div class="col-md-8">
                                                {{--*/ $kasir = $order->bayar->karyawan->nama /*--}}
                                                {{ Form::text('kasir', $kasir, ['class' => 'form-control', 'id' => 'kasir', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jumlah" class="col-md-3 control-label">Jumlah</label>
                                            <div class="col-md-8">
                                                {{ Form::text('jumlah', $jumlah, ['class' => 'form-control number_f', 'id' => 'jumlah',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer_id" class="col-md-3 control-label">ID Customer</label>
                                            <div class="col-md-8">
                                                {{ Form::text('customer_kode', ( $order->customer != null ? $order->customer->kode : ''), ['class' => 'form-control', 'id' => 'customer_kode', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bayar" class="col-md-3 control-label">Bayar</label>
                                            <div class="col-md-8">
                                                {{ Form::text('bayar', $order->bayar->bayar, ['class' => 'form-control number_f', 'id' => 'bayar', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--*/ $kembali = $order->bayar->bayar - $jumlah; /*--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_customer" class="col-md-3 control-label">Nama Cstmr</label>
                                            <div class="col-md-8">
                                                {{ Form::text('nama_customer', ( $order->customer != null ? $order->customer->nama : ''), ['class' => 'form-control', 'id' => 'nama_customer', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kembali" class="col-md-3 control-label">Kembali</label>
                                            <div class="col-md-8">
                                                {{ Form::text('kembali', $kembali, ['class' => 'form-control number_f', 'id' => 'kembali',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-offset-3 col-md-9">
                                            @can('order.update')
                                            <button type="submit" class="btn red btnSubmit" id="btnSaveitem">Simpan Pembayaran</button>
                                            <a href="{{ url('/order/'.$id.'/rechange') }}" class="btn yellow" onclick="return confirm('Atur Ulang Order ??')">Rechange Order</a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{ Form::hidden('id', $id) }}
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab_produks">
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE TABLE PORTLET-->
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-comments"></i>Detail Transaksi / Order #{{ $order->nota }}</div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tblDetailOrder">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Qty Return</th>
                                        <th>Qty Sisa</th>
                                        <th>Subtotal</th>
                                        @can('order.update')
                                        <th>Act</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    {{--*/ $i = 0; /*--}}
                                    @foreach($orderDetail as $od)
                                    {{--*/ $i++; /*--}}
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $od->nama }}</td>
                                        <td style="text-align:right;">{{ number_format($od->harga_jual, 0, ',', '.') }}</td>
                                        <td>{{ $od->qty_ori }}</td>
                                        <td>{{ $od->qty_return }}</td>
                                        <td>{{ $od->qty }}</td>
                                        <td style="text-align:right;">{{ number_format($od->subtotal, 0, ',', '.') }}</td>
                                        @can('order.update')
                                        <td>
                                            <a href="{{ url('/ajax/order/detail/return?id='.$od->id) }}" data-toggle="modal"
                                                data-target="#ajax" class="btn btn-sm yellow">
                                                    <i class="icon-refresh"></i>
                                            </a>
                                        </td>
                                        @endcan
                                    </tr>
                                    @endforeach

                                    @foreach($orderPlaces as $place)
                                    {{--*/ $i++; /*--}}
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td colspan="5">{{ $place['nama'] }}</td>
                                        <td style="text-align:right;">{{ number_format($place['harga'], 0, ',', '.') }}</td>
                                        @can('order.update')
                                        <td></td>
                                        @endcan
                                    </tr>
                                    @endforeach

                                    {{--*/ $i++; /*--}}
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td colspan="5">Service</td>
                                        <td style="text-align:right;">{{ number_format($order->bayar->service_cost, 0, ',', '.') }}</td>
                                        @can('order.update')
                                        <td></td>
                                        @endcan
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td colspan="5">Total</td>
                                        <td id="totalDetail" style="text-align:right;">{{ number_format((collect($orderDetail)->sum('subtotal') + collect($orderPlaces)->sum('harga') + $order->bayar->service_cost), 0, ',', '.') }}</td>
                                        @can('order.update')
                                        <td></td>
                                        @endcan
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END SAMPLE TABLE PORTLET-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <img src="{{ url('/') }}/assets/metronic/img/ajax-modal-loading.gif" alt="" class="loading">
</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".number_f").inputmask("integer", {
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'right');

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });

    $("#diskon").on('keyup', Hitung);

    function Hitung()
    {
        var totalAkhir  = $("#total_akhir").val();
        var diskon      = $("#diskon").val() != "" ? $("#diskon").val() : 0;
        var jumlah      = totalAkhir - diskon;
        var bayar       = $("#bayar").val();
        var kembalian   = bayar - jumlah;


        $("#jumlah").val(jumlah);
        $("#kembali").val(kembalian);
    }
</script>
@stop
