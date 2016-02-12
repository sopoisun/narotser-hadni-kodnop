@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Ubah Input Nominal Akun
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/account') }}">Akuntansi</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/account/saldo') }}">Saldo</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Ubah Input Saldo</a></li>
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
                    <i class="icon-reorder"></i> Form Ubah Nominal Akun
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($accountSaldo, ['role' => 'form', 'id' => 'formNominalAkun']) !!}
                    @include('metronic.account.saldo.input-form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@include('metronic.account.saldo.form-js')
