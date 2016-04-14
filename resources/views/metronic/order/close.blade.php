@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/css/typeahead.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2-bootstrap.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Close Order <small>Close Pelanggan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/order') }}">Order</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Close Order Pelanggan</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<ul  class="nav nav-tabs">
    <li class="active"><a href="#tab_new_order" data-toggle="tab">Close Order</a></li>
    <li class=""><a href="#tab_produks" data-toggle="tab">Daftar Produk</a></li>
</ul>

{{--*/
    $settingServiceCost = setting()->service_cost;
/*--}}

<div  class="tab-content">
    <div class="tab-pane fade active in" id="tab_new_order">
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE TABLE PORTLET-->
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-filter"></i>Input Pembayaran</div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formInputPembayaran']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('tanggal')) has-error @endif">
                                            <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                            <div class="col-md-8">
                                                {{--*/ $tanggal = old('tanggal')  ? old('tanggal') : $tanggal; /*--}}
                                                {{ Form::text('tanggal', $tanggal, ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
                                                @if($errors->has('tanggal'))<span class="help-block">{{ $errors->first('tanggal') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total" class="col-md-3 control-label">Total</label>
                                            <div class="col-md-8">
                                                {{ Form::text('total', null, ['class' => 'form-control number', 'id' => 'total', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cmb_service_cost" class="col-md-3 control-label">Use Srv</label>
                                            <div class="col-md-8">
                                                {{--*/ $cmb_service_cost = old('cmb_service_cost')  ? old('cmb_service_cost') : null; /*--}}
                                                {{ Form::select('cmb_service_cost', ['Ya' => 'Ya', 'Tidak' => 'Tidak'], $cmb_service_cost, ['class' => 'form-control', 'id' => 'cmb_service_cost']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_cost" class="col-md-3 control-label">Srv Cost</label>
                                            <div class="col-md-8">
                                                {{--*/ $service_cost = old('service_cost')  ? old('service_cost') : $settingServiceCost; /*--}}
                                                {{ Form::text('service_cost', $service_cost, ['class' => 'form-control number', 'id' => 'service_cost',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="places" class="col-md-3 control-label">Type Pajak</label>
                                            <div class="col-md-8">
                                                {{--*/ $tax_id = old('tax_id')  ? old('tax_id') : $init_tax->id; /*--}}
                                                {{ Form::select('tax_id', $taxes, $tax_id, ['class' => 'form-control', 'id' => 'tax_id']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tax" class="col-md-3 control-label" id="tax-label">Tax</label>
                                            <div class="col-md-8">
                                                {{ Form::text('tax', null, ['class' => 'form-control number', 'id' => 'tax',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_bayar" class="col-md-3 control-label">Type Bayar</label>
                                            <div class="col-md-8">
                                                {{ Form::select('type_bayar', ['tunai' => 'Tunai', 'debit' => 'Debit', 'credit_card' => 'Credit Card'], null, ['class' => 'form-control', 'id' => 'type_bayar']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tax_bayar" class="col-md-3 control-label" id="tax-bayar-label">Tax Bayar 0 %</label>
                                            <div class="col-md-8">
                                                {{ Form::text('tax_bayar', null, ['class' => 'form-control number', 'id' => 'tax_bayar',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank" class="col-md-3 control-label">Bank</label>
                                            <div class="col-md-8">
                                                {{--*/
                                                    $opt = ['class' => 'form-control', 'id' => 'bank_id', 'disabled' => 'disabled'];
                                                    if( old('type_bayar') && old('type_bayar') != 'tunai'  ){
                                                        unset($opt['disabled']);
                                                    }
                                                /*--}}
                                                {{ Form::select('bank_id', $banks, null, $opt) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_akhir" class="col-md-3 control-label">Total Akhir</label>
                                            <div class="col-md-8">
                                                {{ Form::text('total_akhir', null, ['class' => 'form-control number', 'id' => 'total_akhir',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="places" class="col-md-3 control-label">Tempat</label>
                                            <div class="col-md-8">
                                                {{ Form::text('places', $current_place, ['class' => 'form-control', 'id' => 'places', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="diskon" class="col-md-3 control-label">Diskon</label>
                                            <div class="col-md-8">
                                                {{ Form::text('diskon', null, ['class' => 'form-control number', 'id' => 'diskon', 'autocomplete' => 'off']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="places" class="col-md-3 control-label">Kasir</label>
                                            <div class="col-md-8">
                                                {{--*/ $kasir = Auth::check() ? Auth::user()->karyawan->nama : 'Ahmad Rizal Afani' /*--}}
                                                {{ Form::text('kasir', $kasir, ['class' => 'form-control', 'id' => 'kasir', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jumlah" class="col-md-3 control-label">Jumlah</label>
                                            <div class="col-md-8">
                                                {{ Form::text('jumlah', null, ['class' => 'form-control number', 'id' => 'jumlah',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('customer_id')) has-error @endif">
                                            <label for="customer_id" class="col-md-3 control-label">ID Customer</label>
                                            <div class="col-md-8">
                                                {{ Form::text('customer_kode', null, ['class' => 'form-control', 'id' => 'customer_kode']) }}
                                                {{ Form::hidden('customer_id', null, ['id' => 'customer_id']) }}
                                                @if($errors->has('customer_id'))<span class="help-block">{{ $errors->first('customer_id') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6  @if($errors->has('bayar')) has-error @endif">
                                        <div class="form-group">
                                            <label for="bayar" class="col-md-3 control-label">Bayar</label>
                                            <div class="col-md-8">
                                                {{ Form::text('bayar', null, ['class' => 'form-control number', 'id' => 'bayar', 'autocomplete' => 'off']) }}
                                                @if($errors->has('bayar'))<span class="help-block">{{ $errors->first('bayar') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_customer" class="col-md-3 control-label">Nama Cstmr</label>
                                            <div class="col-md-8">
                                                {{ Form::text('nama_customer', null, ['class' => 'form-control', 'id' => 'nama_customer', 'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kembali" class="col-md-3 control-label">Kembali</label>
                                            <div class="col-md-8">
                                                {{ Form::text('kembali', null, ['class' => 'form-control number', 'id' => 'kembali',  'readonly' => 'readonly']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn red btnSubmit" id="btnSaveitem">Simpan Pembayaran</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{--*/ $tax_procentage = old('tax_procentage') ? old('tax_procentage') : $init_tax->procentage; /*--}}
                                        {{ Form::hidden('tax_procentage', $tax_procentage, ['id' => 'tax_procentage']) }}
                                        {{--*/ $tax_bayar_procentage = old('tax_bayar_procentage') ? old('tax_bayar_procentage') : 0; /*--}}
                                        {{ Form::hidden('tax_bayar_procentage', $tax_bayar_procentage, ['id' => 'tax_bayar_procentage']) }}
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
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i>Daftar Produk Yang Dipesan</div>
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
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{--*/ $i = 0; /*--}}
                                    @foreach($orderDetail as $od)
                                    {{--*/ $i++; /*--}}
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $od['nama'] }}</td>
                                        <td style="text-align:right;">{{ number_format($od['harga_jual'], 0, ',', '.') }}</td>
                                        <td>{{ $od['qty'] }}</td>
                                        <td style="text-align:right;">{{ number_format($od['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach

                                    @foreach($orderPlaces as $op)
                                    {{--*/ $i++; /*--}}
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td colspan="3">{{ "Reservasi ".$op['nama'] }}</td>
                                        <td style="text-align:right;">{{ number_format($op['harga'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td></td>
                                        <td colspan="3">Total</td>
                                        <td id="totalDetail" style="text-align:right;">{{ number_format((collect($orderDetail)->sum('subtotal') + collect($orderPlaces)->sum('harga')), 0, ',', '.') }}</td>
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
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/jquery.xdomainrequest.min.js"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/typeahead.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();
    $(".number").inputmask("integer", {
        min: 0,
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    });

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });

    /* Search Karyawan */
    var customerSources = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: "{{ url('/ajax/customer') }}",
            cache: false,
        },
        remote: {
            url: "{{ url('/ajax/customer') }}?q=%QUERY",
            wildcard: '%QUERY'
        }
    });

    $('#customer_kode').typeahead(null, {
        name: 'd-customer',
        display: 'kode',
        source: customerSources,
        templates: {
            suggestion: function(data){
                var str = "<div class='supplier_result'><p>"+data.kode+"</p><small>"+data.nama+"</small></div>";
                return str;
            },
            empty: function(){
                return '<div class="empty-message">ID Customer Tidak Ada...</div>';
            },
        }
    }).on('typeahead:asyncrequest', function() {
        $('#customer_kode').addClass('spinner');
    }).on('typeahead:asynccancel typeahead:asyncreceive', function() {
        $('#customer_kode').removeClass('spinner');
    }).bind('typeahead:select', function(ev, suggestion) {
        //console.log('Selection: ' + suggestion.year);
        $("#customer_id").val(suggestion.id);
        $("#nama_customer").val(suggestion.nama);
    }).bind('typeahead:active', function(){
        //alert('active');
    });
    /* End Search Karyawan */

    $("#total").val("{{ (collect($orderDetail)->sum('subtotal') + collect($orderPlaces)->sum('harga')) }}");
    $("#tax-label").html("Tax "+$("#tax_procentage").val()+" %");
    $("#tax-bayar-label").html("Tax "+$("#tax_bayar_procentage").val()+" %");

    HitungTotalAkhir();

    $("#diskon").on('keyup', HitungTotalAkhir);
    $("#bayar").on('keyup', HitungKembalian);
    $("#cmb_service_cost").change(function(){
        if($(this).val() == 'Ya'){
            $("#service_cost").val("{{ $settingServiceCost }}");
        }else{
            $("#service_cost").val("0");
        }
        HitungTotalAkhir();
    });
    $("#tax_id").change(function(){
        $.ajax({
            url: "{{ url('/ajax/tax') }}",
            type: "GET",
            data: { id : $(this).val() },
            success: function(res){
                $("#tax_procentage").val(res.procentage);
                $("#tax-label").html("Tax "+res.procentage+" %");
                HitungTotalAkhir();
            }
        })
    });
    $("#type_bayar").change(function(){
        if($(this).val() == 'debit' || $(this).val() == 'credit_card'){
            $("#bank_id").removeAttr('disabled');
            LoadBank();
        }else{
            $("#tax_bayar_procentage").val(0);
            $("#tax-bayar-label").html("Tax Bayar 0 %");
            $("#bank_id").attr('disabled', 'disabled');
        }

        HitungTotalAkhir();
    });

    $("#bank_id").change(function(){
        LoadBank();
        HitungTotalAkhir();
    });

    function LoadBank()
    {
        $.ajax({
            url: "{{ url('/ajax/bank') }}",
            type: "GET",
            async: false,
            data: { id: $("#bank_id").val(), type: $("#type_bayar").val() },
            success: function(res){
                $("#tax_bayar_procentage").val(res.tax);
                $("#tax-bayar-label").html("Tax Bayar "+res.tax+" %");
            }
        })
    }

    function HitungTotalAkhir()
    {
        var total               = $("#total").val();
        var service_cost        = $("#service_cost").val();
        total                   = parseInt(total) + parseInt(service_cost);
        var taxProcentage       = $("#tax_procentage").val();
        var taxNominal          = parseInt(total) * ( taxProcentage / 100 );
        var taxBayarProcentage  = $("#tax_bayar_procentage").val();
        var taxBayar            = ( parseInt(total) + parseInt(taxNominal) ) * ( taxBayarProcentage / 100 );
        var totalAkhir          = parseInt(total) + parseInt(taxNominal) + parseInt(taxBayar);
        var diskon              = $("#diskon").val() != "" ? $("#diskon").val() : 0;
        var jumlah              = totalAkhir - diskon;

        $("#tax").val(taxNominal);
        $("#tax_bayar").val(taxBayar);
        $("#total_akhir").val(totalAkhir);
        $("#jumlah").val(jumlah);

        HitungKembalian();
    }

    function HitungKembalian()
    {
        var bayar = $("#bayar").val();
        var jumlah  = $("#jumlah").val();
        if( bayar != "" && bayar != " " ){
            var kembalian = bayar - jumlah;
            if( kembalian > 0 ){
                $("#kembali").val(kembalian);
            }else{
                $("#kembali").val(0);
            }
        }else{
            $("#kembali").val("");
        }
    }
</script>
@stop
