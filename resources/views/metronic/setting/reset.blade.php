@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/css/typeahead.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Reset Aplikasi
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/setting') }}">Setting</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Reset Aplikasi</a></li>
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
                    <i class="icon-reorder"></i> Form reset aplikasi
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div style="padding:10px;">
                    <div class="note note-warning">
                        <h4 class="block">Warning! Semua Data Akan Hilang</h4>
                        <p>Data yang dipilih akan dikosongkan.</p>
                    </div>
                </div>
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formResetApp']) !!}
                <div class="form-body">
                    <div class="form-group">
                        <label for="roles" class="control-label col-md-2">Table Data</label>
                        <div class="col-md-10">
                            <div class="row-fluid">
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'orders' , true, ['disabled' => 'disabled']) }} Data Penjualan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'pembelians' , true, ['disabled' => 'disabled'] ) }} Data Pembelian
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'adjustments', true, ['disabled' => 'disabled']) }} Data Adjustment
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'accounts', false) }} Data Akuntansi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'bahans', false) }} Data Bahan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'banks', false) }} Data Bank
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'customers', false) }} Data Customer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'karyawans', false) }} Data Karyawan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'place_kategoris', false) }} Kategori Tempat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'places', false) }} Data Tempat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'produk_kategoris', false) }} Kategori Produk
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'produks', false) }} Data Produk
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'settings', false) }} Setting
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'suppliers', false) }} Data Supplier
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'taxes', false) }} Data Pajak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('tables[]', 'users', false) }} Data User
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" class="btn yellow btnSubmit">Reset Aplikasi</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_section')
<script>
    $("#formResetApp").submit(function(e){
        if( !confirm('Yakin reset data ??') ){
            e.preventDefault();
        }
    });
</script>
@stop
