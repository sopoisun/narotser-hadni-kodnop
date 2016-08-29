@extends('metronic.layout')

@section('css_section')
<style>
.dashboard-spinner{
    width: 100%;
    height: 50px;
    background-image: url('{{ url("/") }}/assets/metronic/img/dashboard-spinner.gif');
    background-repeat: no-repeat;
    background-position: center;
}

.dashboard-spinner-big{
    width: 100%;
    height: 150px;
    background-image: url('{{ url("/") }}/assets/metronic/img/dashboard-spinner-big.gif');
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
            Qty Warning
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/bahan-produksi/qty-warning-print') }}" onClick="return sendSession(this)">Print</a></li>
                </ul>
            </li>
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Bahan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar stok bahan dibawah qty warning</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Daftar Stok bahan dibawah qty warning</div>
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

@section('js_section')
<script>
    $.ajax({
        url: "{{ url('/ajax/dashboard-bahan') }}",
        type: "GET",
        success: function(res){
            $("#bahan-stok").html(res).fadeIn();
        }
    });

    function sendSession(_this){
        var __this = $(_this);
        var __url = __this.attr('href');
        var tbody = $("#bahan-stok").find('table').find('tbody');
        var rowCount = tbody.find('tr').length;
        var print = false;
        if( rowCount > 0 ){
            if( rowCount == 1 && $(tbody.find('tr')[0]).find('td').length > 1 ){
                print = true;
            }else if (rowCount > 1) {
                print = true;
            }
        }

        if( !print ){
            toastr.warning('Tidak ada data yang diprint!');
        }else{
            var _data = "[";
            tbody.find('tr').each(function(i, v){
                _data += "{";
                    _data += '"no": "'+$(this).find('td').eq('0').text()+'",';
                    _data += '"nama": "'+$(this).find('td').eq('1').text()+'",';
                    _data += '"stok": "'+$(this).find('td').eq('2').text()+'"';
                _data += "},";
            });
            _data = _data.substring(0,_data.length-1);
            _data += "]";

            $.ajax({
                type: "POST",
                url: "{{ url('/') }}/bahan-produksi/qty-warning-session",
                data: {data: _data, _token: "{{ csrf_token() }}"},
                async: false,
                success: function(res){
                    window.open(__url, '_blank');
                }
            })
        }

        return false;
    }
</script>
@stop
