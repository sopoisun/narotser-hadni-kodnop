@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Auto Reader Data
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/report') }}">Auto Reader Data</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Periode</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption"><i class="icon-filter"></i>Filter Tanggal</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formTanggalFilter']) !!}
                    <div class="form-body">
                        @if(Session::has('message'))
                        <div class="note note-info">
                            <h4 class="block">Info! Harap sabar :)</h4>
                            <p>{{ Session::get("message") }}</p>
                        </div>
                        @endif
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="col-md-3 control-label">Type</label>
                                    <div class="col-md-8">
                                        {{
                                            Form::select('type',
                                                ['sale' => 'Penjualan', 'purchase' => 'Pembelian'],
                                                null, ['class' => 'form-control', 'id' => 'type']
                                            )
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('tanggal')) has-error @endif">
                                    <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-large tanggalan input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                                            <input type="text" class="form-control" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}" />
                                            <span class="input-group-addon">s/d</span>
                                            <input type="text" class="form-control" name="to_tanggal" value="{{ $to_tanggal->format('Y-m-d') }}" />
                                         </div>
                                        @if($errors->has('tanggal'))<span class="help-block">{{ $errors->first('tanggal') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red">Baca Data Akun</button>
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
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();
</script>
@stop
