@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Detail Pembayaran <small>Daftar pembayaran pembelian bahan / produk</small>
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
            <li><a href="javascript:void(0)">Pembayaran Pembelian #{{ $id }}</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-reorder"></i> Form Pembelian
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formpembelian']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('tanggal')) has-error @endif">
                                    <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                    {{--*/ $tanggal = old('tanggal')  ? old('tanggal') : date('Y-m-d'); /*--}}
                                    {{ Form::text('tanggal', $tanggal, ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
                                    @if($errors->has('tanggal'))<span class="help-block">{{ $errors->first('tanggal') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('harus_dibayar')) has-error @endif">
                                    <label for="harus_dibayar" class="col-md-3 control-label">Harus dibayar</label>
                                    <div class="col-md-8">
                                    {{ Form::text('harus_dibayar', null, ['class' => 'form-control', 'id' => 'harus_dibayar', 'readonly' => 'readonly']) }}
                                    @if($errors->has('harus_dibayar'))<span class="help-block">{{ $errors->first('harus_dibayar') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('total')) has-error @endif">
                                    <label for="total" class="col-md-3 control-label">Total</label>
                                    <div class="col-md-8">
                                    {{ Form::text('total', $total, ['class' => 'form-control', 'id' => 'total', 'readonly' => 'readonly']) }}
                                    @if($errors->has('total'))<span class="help-block">{{ $errors->first('total') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('nominal')) has-error @endif">
                                    <label for="nominal" class="col-md-3 control-label">Bayar</label>
                                    <div class="col-md-8">
                                    {{ Form::text('nominal', null, ['class' => 'form-control number', 'id' => 'nominal']) }}
                                    @if($errors->has('nominal'))<span class="help-block">{{ $errors->first('nominal') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('dibayar')) has-error @endif">
                                    <label for="dibayar" class="col-md-3 control-label">Di Bayar</label>
                                    <div class="col-md-8">
                                    {{ Form::text('dibayar', null, ['class' => 'form-control', 'id' => 'dibayar', 'readonly' => 'readonly']) }}
                                    @if($errors->has('dibayar'))<span class="help-block">{{ $errors->first('dibayar') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('sisa')) has-error @endif">
                                    <label for="sisa" class="col-md-3 control-label">Sisa</label>
                                    <div class="col-md-8">
                                    {{ Form::text('sisa', null, ['class' => 'form-control', 'id' => 'sisa', 'readonly' => 'readonly']) }}
                                    @if($errors->has('sisa'))<span class="help-block">{{ $errors->first('sisa') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn yellow">Simpan Pembayaran</button>
                                    {{ Form::hidden('barangs', null, ['id' => 'barangs']) }}
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Daftar Pembayaran Pembelian #{{ $id }}</div>
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
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/
                                $no = 0;
                                $xtotal = 0;
                            /*--}}
                            @foreach($bayars as $bayar)
                            {{--*/
                                $no++;
                                $xtotal += $bayar->nominal;
                            /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $bayar->tanggal->format('d M Y') }}</td>
                                <td>{{ $bayar->karyawan->nama }}</td>
                                <td style="text-align:right;">{{ number_format($bayar->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr style="background-color:#CCC;">
                                <td></td>
                                <td colspan="2">Total</td>
                                <td style="text-align:right;">{{ number_format($xtotal, 0, ',', '.') }}</td>
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

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();
    $(".number").inputmask("integer", {
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    $("#dibayar").val("{{ $xtotal }}");
    $("#harus_dibayar").val("{{ ($total - $xtotal) }}");
    $("#nominal").on('keyup', function(){
        if( $(this).val() != "" ){
            sisa = parseInt($("#harus_dibayar").val()) - parseInt($(this).val());
            if( sisa < 0 ){
                sisa = 0;
            }
            $("#sisa").val(sisa);
        }else{
            $("#sisa").val("");
        }
    });
</script>
@stop
