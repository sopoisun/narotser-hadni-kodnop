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
                <a href="{{ url('/report/perbulan') }}">Perbulan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Laporan Laba/Rugi {{ $tanggal->format('M Y') }}</a></li>
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
                <div class="caption"><i class="icon-filter"></i>Filter Bulan</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formTypeFilter']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl" class="col-md-3 control-label">Bulan</label>
                                    <div class="col-md-8">
                                        {{ Form::text('bulan', $tanggal->format('Y-m'), ['class' => 'form-control tanggalan', 'id' => 'bulan', 'data-date-format' => 'yyyy-mm']) }}
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
                                    <a href="{{ url('/report/perbulan/labarugi-print?bulan='.$tanggal->format('Y-m')) }}"
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
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-tasks"></i>Laporan Laba/Rugi {{ $tanggal->format('M Y') }}</div>
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
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker({
        viewMode: 1,
        minViewMode: 1
    });
</script>
@stop
