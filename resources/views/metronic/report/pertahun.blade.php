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
            Laporan Penjualan<small>Laporan {{ $tanggal->format('Y') }}</small>
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
                <a href="{{ url('/report/pertahun') }}">Pertahun</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Laporan Penjualan {{ $tanggal->format('Y') }}</a></li>
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
                <div class="caption"><i class="icon-filter"></i>Filter Tahun</div>
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
                                    <label for="tahun" class="col-md-3 control-label">Tahun</label>
                                    <div class="col-md-8">
                                    {{ Form::text('tahun', $tanggal->format('Y'), ['class' => 'form-control tanggalan', 'id' => 'tahun', 'data-date-format' => 'yyyy']) }}
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
                <div class="caption"><i class="icon-comments"></i>Laporan {{ $tanggal->format('Y') }}</div>
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
                                <th>Bulan</th>
                                <th>Ttl Sale</th>
                                <th>Ttl Rsv</th>
                                <th>Ttl Pajak</th>
                                <th>Ttl Pjk Sale</th>
                                <th>Total</th>
                                <th>Ttl Diskon</th>
                                <th>Jumlah</th>
                                <th>Ttl HPP</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--*/ $no = 0; /*--}}
                            @foreach($months as $month)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>
                                    <a href="{{ url('/report/perbulan?bulan='.$month->format('Y-m')) }}" style="text-decoration:underline;">
                                        {{ $month->format('M Y') }}
                                    </a>
                                </td>
                                {{--*/
                                    $idx = array_search($month->format('Y-m'), array_column($reports, 'bulan'));
                                /*--}}
                                @if(false !== $idx)
                                    {{--*/ $d = $reports[$idx] /*--}}
                                    <td style="text-align:right;">{{ number_format($d['total_penjualan'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['total_reservasi'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['pajak'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['pajak_pembayaran'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['total_akhir'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['diskon'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['jumlah'], 0, ',', '.') }}</td>
                                    <td style="text-align:right;">{{ number_format($d['total_hpp'], 0, ',', '.') }}</td>
                                @else
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                    <td style="text-align:right;">0</td>
                                @endif
                            </tr>
                            @endforeach

                            <tr style="background-color:#CCC;">
                                <td></td>
                                <td>Total</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_penjualan'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_reservasi'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('pajak'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('pajak_pembayaran'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_akhir'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('diskon'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('jumlah'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_hpp'), 0, ',', '.') }}</td>
                            </tr>
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
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker({
        viewMode: 2,
        minViewMode: 2,
    });

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });
</script>
@stop
