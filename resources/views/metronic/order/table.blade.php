@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/css/typeahead.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Order <small>Daftar Order di Tempat Pelanggan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Order</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Order di Tempat Pelanggan</a></li>
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
                <div class="caption"><i class="icon-filter"></i>Pilih Type Tempat Pelanggan</div>
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
                                    {{ Form::text('tgl', $tgl->format('Y-m-d'), ['class' => 'form-control tanggalan', 'id' => 'tgl', 'data-date-format' => 'yyyy-mm-dd']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="col-md-3 control-label">Type</label>
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
                <div class="caption"><i class="icon-tasks"></i>Daftar Tempat Pelanggan {{ $tgl->format('d M Y') }}</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Tempat</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($places as $place)
                            {{--*/ $no++ /*--}}
                            <tr row="row_{{ $place->id }}">
                                <td>{{ $no }}</td>
                                <td>{{ $place->nama }}</td>
                                <td>
                                    @if( $place->state == 'On Going' )
                                    <span class="label label-danger">On Going</span>
                                    @else
                                    <span class="label label-success">Ready</span>
                                    @endif
                                </td>
                                <td>
                                    @if( $place->state == 'On Going' )
                                        @can('order.change')
                                        <a href="{{ url('/order/'.$place->order_id.'/change') }}" class="btn btn-sm yellow"><i class="icon-cog"></i> Change</a>
                                        @endcan
                                        @can('order.merge')
                                        <!--<a href="{{ url('/ajax/order/'.$place->order_id.'/merge?tanggal='.$tgl) }}" data-toggle="modal"
                                            data-target="#ajax" class="btn btn-sm blue" data-id="{{ $place->id }}">
                                            <i class="icon-sitemap"></i> Gabung
                                        </a>-->
                                        @endcan
                                        @can('order.close')
                                        <a href="{{ url('/order/'.$place->order_id.'/close') }}" class="btn btn-sm green"><i class="icon-ok"></i> Close</a>
                                        @endcan
                                        @can('order.cancel')
                                        <!--<a href="{{ url('/ajax/order/'.$place->order_id.'/cancel') }}" data-toggle="modal"
                                            data-target="#ajax" class="btn btn-sm red" data-id="{{ $place->id }}">
                                            <i class="icon-remove"></i> Cancel
                                        </a>-->
                                        @endcan
                                    @else
                                        @can('order.open')
                                        <a href="{{ url('/order/'.$place->id.'/open?tanggal='.$tgl->format('Y-m-d')) }}" class="btn btn-sm blue"><i class="icon-pencil"></i> Open</a>
                                        @endcan
                                    @endif
                                </td>
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
<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <img src="{{ url('/') }}/assets/metronic/img/ajax-modal-loading.gif" alt="" class="loading">
</div>

<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/jquery.xdomainrequest.min.js"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/typeahead.bundle.min.js" type="text/javascript"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();

    var relatedTarget;
    $("#ajax").on("show.bs.modal", function(e) {
        relatedTarget = $(e.relatedTarget);
        $(this).removeData('bs.modal');
    });

    /*$('#ajax').on('hidden.bs.modal', function (e) {
        var rowId = "row_"+relatedTarget.data('id');
        alert('hidden.bs.modal fired !!!\nWill deleted row '+rowId);
    });*/
</script>
@stop
