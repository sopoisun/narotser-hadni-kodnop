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
            Jurnal {{ $types[$type] }}
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
            <li><a href="{{ url('/account/saldo/jurnal') }}">Jurnal {{ $types[$type] }}</a></li>
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
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formTypeFilter']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                    {{ Form::text('tanggal', $tanggal->format('Y-m-d'), ['class' => 'form-control tanggalan', 'id' => 'tgl', 'data-date-format' => 'yyyy-mm-dd']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="col-md-3 control-label">Type Jurnal</label>
                                    <div class="col-md-8">
                                    {{ Form::select('type', $types, $type, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red">Tampilkan</button>
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
                <div class="caption"><i class="icon-tasks"></i>Jurnal {{ $types[$type] }} {{ $tanggal->format('d M Y') }}</div>
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
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($table as $t)
                            {{--*/ $no++ /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $t['keterangan'] }}</td>
                                <td>{{ is_numeric($t['debet']) ? number_format($t['debet'], 0, ',', '.') : $t['debet'] }}</td>
                                <td>{{ is_numeric($t['kredit']) ? number_format($t['kredit'], 0, ',', '.') : $t['kredit'] }}</td>
                                <td>{{ is_numeric($t['saldo']) ? number_format($t['saldo'], 0, ',', '.') : $t['saldo'] }}</td>
                            </tr>
                            @endforeach
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
    $(".tanggalan").datepicker();
</script>
@stop
