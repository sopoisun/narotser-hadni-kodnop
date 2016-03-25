@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            ID Customer<small>Buat ID Customer</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/place') }}">Customer</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Buat ID Customer</a></li>
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
                    <i class="icon-reorder"></i> Form ID Customer Generator
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form']) !!}
                    <div class="form-body">
                        <div class="form-group @if($errors->has('jumlah')) has-error @endif">
                            <label for="jumlah" class="control-label">Jumlah</label>
                            {{ Form::text('jumlah', null, ['class' => 'form-control number', 'id' => 'jumlah']) }}
                            @if($errors->has('jumlah'))<span class="help-block">{{ $errors->first('jumlah') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn yellow btnSubmit">Simpan</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
$(".number").inputmask("integer", {
    onUnMask: function(maskedValue, unmaskedValue) {
        return unmaskedValue;
    },
}).css('text-align', 'left');
</script>
@stop
