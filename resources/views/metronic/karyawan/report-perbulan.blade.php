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
            Laporan Order Karyawan <small>{{ $tanggal->format('M Y') }}</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/karyawan') }}">Karyawan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Laporan Order Karyawan {{ $tanggal->format('M Y') }}</a></li>
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
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formTanggalFilter']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bulan" class="col-md-3 control-label">Bulan</label>
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
                                    <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}" />
                                    <button type="submit" class="btn red">Tampilkan</button>
                                    <a href="{{ url('/karyawan/report/perbulan-print?bulan='.$tanggal->format('Y-m').'&karyawan_id='.$karyawan->id) }}"
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
                <div class="caption"><i class="icon-comments"></i>Laporan Order {{ $karyawan->nama }} {{ $tanggal->format('M Y') }}</div>
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
                                <th>Tanggal</th>
                                <th>Total Penjualan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($dates as $date)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $date->format('d M Y') }}</td>
                                {{--*/
                                    $idx = array_search($date->format('Y-m-d'), array_column($reports->toArray(), '_date'));
                                /*--}}
                                @if(false !== $idx)
                                    {{--*/ $d = $reports[$idx] /*--}}
                                    <td style="text-align:right;">{{ number_format($d['total_penjualan'], 0, ',', '.') }}</td>
                                @else
                                    <td style="text-align:right;">0</td>
                                @endif
                                <td>
                                    {{--*/ $txt = ( false === $idx ) ? 'disabled' : '' /*--}}
                                    <a href="{{ url('/report/pertanggal/karyawan/detail?tanggal='.$date->format('Y-m-d').'&karyawan_id='.$karyawan->id) }}" data-toggle="modal"
                                        data-target="#ajax" class="btn btn-sm blue" {{ $txt }}>
                                        <i class="icon-search"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            <tr style="background-color:#CCC;">
                                <td></td>
                                <td>Total</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_penjualan'), 0, ',', '.') }}</td>
                                <td></td>
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
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker({
        viewMode: 1,
        minViewMode: 1,
    });

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });
</script>
@stop
