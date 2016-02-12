@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Produk <small>Daftar produk</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('produk.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/produk/add') }}">Tambah Produk</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Produk</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Produk</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Produk</div>
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
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($produks as $produk)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $produk->nama }}</td>
                                <td>{{ $produk->kategori->nama }}</td>
                                <td>{{ number_format(CountPrice($produk), 0, ",", ".") }}</td>
                                <td>
                                    @can('produk.update')
                                    <a href="{{ url('/produk/edit/'.$produk->id) }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                    @can('produk.delete')
                                    <a href="{{ url('/produk/delete/'.$produk->id) }}" onclick="return confirm('Yakin hapus {{ $produk->nama }} ??')" class="btn btn-sm red"><i class="icon-trash"></i></a>
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
