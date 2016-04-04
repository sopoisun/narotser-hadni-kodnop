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
            Daftar Input Saldo <small>Daftar Input Saldo</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('account.saldo.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/account/saldo/add') }}">Input Saldo</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/account') }}">Akuntansi</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Saldo</a></li>
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
                                    {{ Form::text('tanggal', $tanggal->format('Y-m-d'), ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
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
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Daftar Input Saldo {{ $tanggal->format('d M Y') }}</div>
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
                                <th>Nama Akun</th>
                                <th>Type</th>
                                <th>Nominal</th>
                                <!--<th>Keterangan</th>-->
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($saldos->count())
                            {{--*/ $no = 0; /*--}}
                            @foreach($saldos as $saldo)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>
                                    {{--*/
                                        $txt = $saldo->account->nama_akun;

                                        if( $saldo->account->relation != null ){
                                            $relation = $saldo->account->relation;
                                            $txt .= " ";
                                            $txt .= ( $saldo->$relation->nama_bank ) ? $saldo->$relation->nama_bank : $saldo->$relation->nama ;
                                        }

                                        echo $txt;
                                    /*--}}
                                </td>
                                <td>{{ $types[$saldo->type] }}</td>
                                <td style="text-align:right;">{{ number_format($saldo->nominal, 0, ',', '.') }}</td>
                                <!--<td></td>-->
                                <td>
                                    @can('account.saldo.update')
                                    <a href="{{ url('/account/saldo/edit/'.$saldo->id) }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" style="text-align:center;">No Data Here</td>
                            </tr>
                            @endif
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
