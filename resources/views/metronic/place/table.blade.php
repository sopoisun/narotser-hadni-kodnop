@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Tempat <small>Daftar Tempat Pelanggan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('place.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/place/add') }}">Tambah Tempat</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Tempat Pelanggan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Tempat Pelanggan</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Tempat Pelanggan</div>
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
                                <th>Type</th>
                                <th>Harga</th>
                                <th>Jumlah Reservasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($places as $place)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $place->nama }}</td>
                                <td>{{ $place->kategori->nama }}</td>
                                <td>{{ number_format($place->harga, 0, ",", ".") }}</td>
                                <td>{{ $place->orderPlace->count() }}</td>
                                <td>
                                    @can('place.update')
                                    <a href="{{ url('/place/edit/'.$place->id) }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                    @can('place.delete')
                                    <a href="{{ url('/place/delete/'.$place->id) }}" onclick="return confirm('Yakin hapus {{ $place->nama }} ??')" class="btn btn-sm red"><i class="icon-trash"></i></a>
                                    @endcan
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
<!-- END PAGE CONTENT-->
@stop
