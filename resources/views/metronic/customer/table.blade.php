@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Customer <small>Daftar Customer</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li class="btn-group">
                <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                    <span>Actions</span> <i class="icon-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    @can('customer.create')
                    <li><a href="{{ url('/customer?type=unregistered') }}"> Buat Customer Baru</a></li>
                    @endcan
                    @can('customer.create-list')
                    <li><a href="{{ url('/customer/add') }}"> Buat ID Customer Baru</a></li>
                    @endcan
                </ul>
            </li>
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0)">Customer</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Daftar Customer</a></li>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Customer</div>
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
                                <th>ID</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Kunjungan</th>
                                <th>Total Pembelian</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( $customers->count() )
                            {{--*/ $no = 0; /*--}}
                            @foreach($customers as $customer)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $customer->kode }}</td>
                                <td>{{ $customer->nama }}</td>
                                <td>{{ $customer->no_hp }}</td>
                                <td>{{ $customer->jumlah_kunjungan }}</td>
                                <td>{{ number_format($customer->total, 0, ',', '.') }}</td>
                                <td>
                                    @can('customer.update')
                                    <a href="{{ url('/customer/edit/'.$customer->id) }}" class="btn btn-sm yellow"><i class="icon-edit"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="6" style="text-align:center;">No Data Here</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div style="float:right;">
                    @include('metronic.paginator',['paginator' => $customers])
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
