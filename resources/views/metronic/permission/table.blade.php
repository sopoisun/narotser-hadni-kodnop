@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Permission
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            @can('permission.create')
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/user/permission/add') }}">Tambah Permission</a></li>
                </ul>
            </li>
            @endcan
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/user') }}">User</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Permission</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Permission</div>
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
                                <th>Opsi</th>
                                <th>Key</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = $limit * ( $permissions->currentPage() - 1 ); /*--}}
                            @foreach($permissions as $permission)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $permission->display }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    @can('permission.update')
                                    <a href="{{ url('/user/permission/edit/'.$permission->id) }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                    @can('permission.delete')
                                    <a href="{{ url('/user/permission/delete/'.$permission->id) }}" onclick="return confirm('Yakin hapus {{ $permission->display }} ??')" class="btn btn-sm red"><i class="icon-trash"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="float:right;">
                    @include('metronic.paginator',['paginator' => $permissions])
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
