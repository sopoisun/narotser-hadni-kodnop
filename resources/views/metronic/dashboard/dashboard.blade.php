@extends('metronic.layout')

@section('css_section')
<style>
.dashboard-spinner{
    width: 100%;
    height: 50px;
    background-image: url('assets/metronic/img/dashboard-spinner.gif');
    background-repeat: no-repeat;
    background-position: center;
}

.dashboard-spinner-big{
    width: 100%;
    height: 150px;
    background-image: url('assets/metronic/img/dashboard-spinner-big.gif');
    background-repeat: no-repeat;
    background-position: center;
}
</style>
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Dashboard
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
            </li>
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
                <div class="caption"><i class="icon-comments"></i>Grafik Penjualan 7 Hari Terakhir</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="canvas" style="width:100%;" height="100"></canvas>
                <!--<div class="dashboard-spinner-big"></div>-->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Produk Laba</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body" id="produk-laba">
                <div class="dashboard-spinner"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
    <div class="col-md-4">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Produk Stok</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body" id="produk-stok">
                <div class="dashboard-spinner"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
    <div class="col-md-4">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Bahan Stok</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body" id="bahan-stok">
                <div class="dashboard-spinner"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/chartjs/Chart.min.js"></script>
@stop

@section('js_section')
<script>
$.ajax({
    url: "{{ url('/ajax/dashboard-chart') }}",
    type: "GET",
    success: function(res){
        var lineChartData = {
            labels: res.label,
            datasets: [{
                label: "Mutasi Masuk",
                fillColor: "rgba(0, 153, 255, 0.2)",
                strokeColor: "rgba(0, 138, 230, 1)",
                pointColor: "rgba(0, 138, 230, 1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(0, 0, 255, 1)",
                data: res.data
            }]
        }

        var ctx = $("#canvas").get(0).getContext("2d");
        var chart = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });
    }
});

$.ajax({
    url: "{{ url('/ajax/dashboard-price') }}",
    type: "GET",
    success: function(res){
        $("#produk-laba").html(res);
    }
});

$.ajax({
    url: "{{ url('/ajax/dashboard-price') }}",
    type: "GET",
    success: function(res){
        $("#produk-laba").html(res).fadeIn();
    }
});

$.ajax({
    url: "{{ url('/ajax/dashboard-produk') }}",
    type: "GET",
    success: function(res){
        $("#produk-stok").html(res).fadeIn();
    }
});

$.ajax({
    url: "{{ url('/ajax/dashboard-bahan') }}",
    type: "GET",
    success: function(res){
        $("#bahan-stok").html(res).fadeIn();
    }
});

</script>
@stop
