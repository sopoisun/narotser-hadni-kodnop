@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Sale Plan <small>Rencana Penjualan Produk</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('saleplan.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/pembelian/saleplan/add') }}">Tambah Sale Plan</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/pembelian') }}">Pembelian</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/pembelian/saleplan') }}">Sale Plan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Sale Plan</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Sale Plan</div>
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
                                <th>Kode Plan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($sale_plans as $sale_plan)
                            {{--*/
                                $no++;
                            /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $sale_plan->tanggal->format('d M Y') }}</td>
                                <td>{{ $sale_plan->kode_plan }}</td>
                                <td>
                                    @can('saleplan.update')
                                    <a href="{{ url('/pembelian/saleplan/'.$sale_plan->id.'/edit') }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                    @can('saleplan.detail')
                                    <a href="{{ url('/pembelian/saleplan/'.$sale_plan->id.'/detail') }}" class="btn btn-sm green" title="detail"><i class="icon-search"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="float:right;">
                    @include('metronic.paginator',['paginator' => $sale_plans])
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
