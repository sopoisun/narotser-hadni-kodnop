@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/css/pages/error.css" rel="stylesheet" type="text/css"/>
@stop

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
                  404 Page
               </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Error 404</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12 page-404">
        <div class="number">
            404
        </div>
        <div class="details">
            <h3>Oops!  You're lost.</h3>
            <p>
                Halaman yang anda akses tidak ada !<br />
                Silahkan pilih menu yang lain.<br /><br />
            </p>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
