@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Setting <small>Setting aplikasi</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/supplier') }}">Setting</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Ubah Setting</a></li>
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
