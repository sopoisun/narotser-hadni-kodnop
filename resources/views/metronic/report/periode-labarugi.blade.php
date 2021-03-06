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
            Laporan Laba/Rugi
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/report') }}">Laporan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/report/periode/solditem/produk') }}">Periode</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Laporan Laba/Rugi {{ $tanggal->format('d M Y').' s/d '.$to_tanggal->format('d M Y') }}</a></li>
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
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formTanggalFilter']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-large tanggalan input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                                            <input type="text" class="form-control" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}" />
                                            <span class="input-group-addon">s/d</span>
                                            <input type="text" class="form-control" name="to_tanggal" value="{{ $to_tanggal->format('Y-m-d') }}" />
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red">Tampilkan</button>
                                    <a href="{{ url('/report/periode/labarugi-print?tanggal='.$tanggal->format('Y-m-d').'&to_tanggal='.$to_tanggal->format('Y-m-d')) }}"
                                        target="_blank" class="btn blue">
                                        Print
                                    </a>
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
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Laporan Laba/Rugi {{ $tanggal->format('d M Y').' s/d '.$to_tanggal->format('d M Y') }}</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Keterangan</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        {{--*/
                            $tableGroup = collect($tableTemp)->groupBy('type');
                        /*--}}
                        <tbody>
                            @foreach($tableGroup as $key => $val)
                            <tr style="font-weight:bold;">
                                <td colspan="3">{{ strtoupper($key) }}</td>
                            </tr>
                            {{--*/ $no = 0; /*--}}
                            @foreach($val as $v)
                            {{--*/ $no++ /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $v['keterangan'] }}</td>
                                <td style="text-align:right;">{{ number_format($v['nominal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr style="font-weight:bold;">
                                <td></td>
                                <td>Total {{ ucfirst($key) }}</td>
                                <td style="text-align:right;">{{ number_format(collect($val)->sum('nominal'), 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr style="font-weight:bold;">
                                <td colspan="2">Laba/Rugi</td>
                                <td style="text-align:right;">{{ number_format(collect($tableTemp)->sum('sum'), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <img src="{{ url('/') }}/assets/metronic/img/ajax-modal-loading.gif" alt="" class="loading">
</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });
</script>
@stop
