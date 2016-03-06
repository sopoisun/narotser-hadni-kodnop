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
            Laporan Produk Terjual [ Sold Item ]
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/report') }}">Laporan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/report/perbulan') }}">Perbulan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/report/perbulan/solditem/produk') }}">Sold Item</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Laporan Produk Terjual {{ $tanggal->format('M Y') }}</a></li>
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
                <div class="caption"><i class="icon-filter"></i>Filter Bulan</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formTanggalFilter']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bulan" class="col-md-3 control-label">Bulan</label>
                                    <div class="col-md-8">
                                    {{ Form::text('bulan', $tanggal->format('Y-m'), ['class' => 'form-control tanggalan', 'id' => 'bulan', 'data-date-format' => 'yyyy-mm']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red">Tampilkan</button>
                                    <a href="{{ url('/report/perbulan/solditem/produk-print?bulan='.$tanggal->format('Y-m')) }}"
                                        target="_blank" class="btn blue">
                                        Print
                                    </a>
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
                <div class="caption"><i class="icon-comments"></i>Daftar Produk Terjual {{ $tanggal->format('M Y') }}</div>
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
                                <th>Nama Produk</th>
                                <th>HPP (Avg)</th>
                                <th>Harga (Avg)</th>
                                <th>Terjual</th>
                                <th>Ttl HPP</th>
                                <th>Subtotal</th>
                                <th>Laba</th>
                                <th>Pros. Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($produks->count())
                            {{--*/ $no = 0; /*--}}
                            @foreach($produks as $produk)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $produk->nama }}</td>
                                <td style="text-align:right;">{{ number_format($produk->hpp, 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $produk->terjual }}</td>
                                <td style="text-align:right;">{{ number_format($produk->total_hpp, 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($produk->subtotal, 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($produk->laba, 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ $produk->laba_procentage.' %' }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td colspan="4">Total</td>
                                <td style="text-align:right;">{{ number_format(collect($produks)->sum('total_hpp'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($produks)->sum('subtotal'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($produks)->sum('laba'), 0, ',', '.') }}</td>
                                <td></td>
                            </tr>

                            @else
                            <tr>
                                <td colspan="10" style="text-align:center;">No Data Here</td>
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
<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <img src="{{ url('/') }}/assets/metronic/img/ajax-modal-loading.gif" alt="" class="loading">
</div>
<!-- END PAGE CONTENT-->
@stop

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker({
        viewMode: 1,
        minViewMode: 1
    });

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });
</script>
@stop
