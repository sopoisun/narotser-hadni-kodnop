@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Kategori Tempat <small>Daftar kategori Tempat Pelanggan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('place_kategori.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/place/kategori/add') }}">Tambah Kategori Tempat</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Kategori Tempat Pelanggan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Kategori Tempat Pelanggan</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Kategori Tempat Pelanggan</div>
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
                                <th>Nama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($kategoris as $kategori)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $kategori->nama }}</td>
                                <td>
                                    @can('place_kategori.update')
                                    <a href="{{ url('/place/kategori/edit/'.$kategori->id) }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                    @can('place_kategori.delete')
                                    <a href="{{ url('/place/kategori/delete/'.$kategori->id) }}" onclick="return confirm('Yakin hapus {{ $kategori->nama }} ??')" class="btn btn-sm red"><i class="icon-trash"></i></a>
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
