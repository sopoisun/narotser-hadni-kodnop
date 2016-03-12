@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Pembelian <small>Daftar pembelian bahan / produk</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('pembelian.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/pembelian/add') }}">Tambah Pembelian</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Pembelian</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Pembelian</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Pembelian</div>
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
                                <th>Karyawan</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Dibayar</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($pembelians as $pembelian)
                            {{--*/
                                $no++;
                                $txt = '';
                                if(collect($pembelian->detail->toArray())->pluck('harga')->sum() > collect($pembelian->bayar->toArray())->pluck('nominal')->sum()){
                                    $txt = 'class="danger"';
                                }
                            /*--}}
                            <tr {!! $txt !!}>
                                <td>{{ $no }}</td>
                                <td>{{ $pembelian->tanggal->format('d M Y') }}</td>
                                <td>{{ $pembelian->karyawan->nama }}</td>
                                <td>{{ $pembelian->supplier == null ? '-' : $pembelian->supplier->nama_perusahaan }}</td>
                                <td>
                                    {{ number_format(collect($pembelian->detail->toArray())->pluck('harga')->sum(), 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ number_format(collect($pembelian->bayar->toArray())->pluck('nominal')->sum(), 0, ',', '.') }}
                                </td>
                                <td>
                                    @can('pembelian.read.detail')
                                    <a href="{{ url('/pembelian/detail/'.$pembelian->id) }}" class="btn btn-sm yellow" title="detail"><i class="icon-search"></i></a>
                                    @endcan
                                    @can('pembelian.bayar')
                                    <a href="{{ url('/pembelian/bayar/'.$pembelian->id) }}" class="btn btn-sm blue" title="pembayaran"><i class="icon-money"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="float:right;">
                    @include('metronic.paginator',['paginator' => $pembelians])
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
