@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Setting <small>General setting</small>
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
            <li><a href="javascript:void(0)">General Setting</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-6">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-cogs"></i> Form Setting
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($setting, ['role' => 'form']) !!}
                    <div class="form-body">
                        <div class="form-group @if($errors->has('title_faktur')) has-error @endif">
                            <label for="title_faktur" class="control-label">Title Faktur</label>
                            {{ Form::text('title_faktur', null, ['class' => 'form-control', 'id' => 'title_faktur']) }}
                            @if($errors->has('title_faktur'))<span class="help-block">{{ $errors->first('title_faktur') }}</span>@endif
                        </div>
                        <div class="form-group @if($errors->has('telp_faktur')) has-error @endif">
                            <label for="telp_faktur" class="control-label">Telp Faktur</label>
                            {{ Form::text('telp_faktur', null, ['class' => 'form-control', 'id' => 'telp_faktur']) }}
                            @if($errors->has('telp_faktur'))<span class="help-block">{{ $errors->first('telp_faktur') }}</span>@endif
                        </div>
                        <div class="form-group @if($errors->has('alamat_faktur')) has-error @endif">
                            <label for="alamat_faktur" class="control-label">Alamat Faktur</label>
                            {{ Form::textarea('alamat_faktur', null, ['class' => 'form-control', 'id' => 'alamat_faktur', 'rows' => '4']) }}
                            @if($errors->has('alamat_faktur'))<span class="help-block">{{ $errors->first('alamat_faktur') }}</span>@endif
                        </div>
                        <div class="form-group @if($errors->has('init_kode')) has-error @endif">
                            <label for="init_kode" class="control-label">Inisial Kode</label>
                            {{ Form::text('init_kode', null, ['class' => 'form-control', 'id' => 'init_kode']) }}
                            @if($errors->has('init_kode'))<span class="help-block">{{ $errors->first('init_kode') }}</span>@endif
                        </div>
                        <div class="form-group @if($errors->has('laba_procentage_warning')) has-error @endif">
                            <label for="laba_procentage_warning" class="control-label">Ambang Batas Prosentase Laba (%)</label>
                            {{ Form::text('laba_procentage_warning', null, ['class' => 'form-control', 'id' => 'laba_procentage_warning']) }}
                            @if($errors->has('laba_procentage_warning'))<span class="help-block">{{ $errors->first('laba_procentage_warning') }}</span>@endif
                        </div>
                        <div class="form-group @if($errors->has('service_cost')) has-error @endif">
                            <label for="service_cost" class="control-label">Biaya Service</label>
                            {{ Form::text('service_cost', null, ['class' => 'form-control', 'id' => 'service_cost']) }}
                            @if($errors->has('service_cost'))<span class="help-block">{{ $errors->first('service_cost') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn yellow">Simpan</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
