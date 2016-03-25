@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Customer<small>Data Customer</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/customer') }}">Customer</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Data Customer  #{{ $customer->kode }}</a></li>
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
                    <i class="icon-reorder"></i> Form Customer #{{ $customer->kode }}
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($customer, ['role' => 'form', 'class' => 'form-horizontal']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('nama')) has-error @endif">
                                    <label for="nama" class="control-label col-md-3">Nama</label>
                                    <div class="col-md-8">
                                    {{ Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) }}
                                    @if($errors->has('nama'))<span class="help-block">{{ $errors->first('nama') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('no_hp')) has-error @endif">
                                    <label for="no_hp" class="control-label col-md-3">No HP</label>
                                    <div class="col-md-8">
                                    {{ Form::text('no_hp', null, ['class' => 'form-control', 'id' => 'no_hp']) }}
                                    @if($errors->has('no_hp'))<span class="help-block">{{ $errors->first('no_hp') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('alamat')) has-error @endif">
                                    <label for="alamat" class="control-label col-md-3">Alamat</label>
                                    <div class="col-md-8">
                                    {{ Form::textarea('alamat', null, ['class' => 'form-control', 'id' => 'alamat', 'rows' => 2]) }}
                                    @if($errors->has('alamat'))<span class="help-block">{{ $errors->first('alamat') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('keterangan')) has-error @endif">
                                    <label for="keterangan" class="control-label col-md-3">Keterangan</label>
                                    <div class="col-md-8">
                                    {{ Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'keterangan', 'rows' => 2]) }}
                                    @if($errors->has('keterangan'))<span class="help-block">{{ $errors->first('keterangan') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn yellow btnSubmit">Simpan Data Customer</button>
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
