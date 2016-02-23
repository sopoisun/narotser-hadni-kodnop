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
            Laporan Penjualan<small>Laporan {{ $tanggal->format('d M Y') }}</small>
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
                <a href="{{ url('/report/pertanggal') }}">Pertanggal</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Laporan Penjualan {{ $tanggal->format('d M Y') }}</a></li>
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
                <div class="caption"><i class="icon-filter"></i>Filter Tanggal</div>
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
                                    <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                    {{ Form::text('tanggal', $tanggal->format('Y-m-d'), ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
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
                                    <a href="{{ url('/report/pertanggal-print?tanggal='.$tanggal->format('Y-m-d')) }}"
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
                <div class="caption"><i class="icon-comments"></i>Daftar Transaksi Tanggal {{ $tanggal->format('d M Y') }}</div>
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
                                <th>No. Nota</th>
                                <th>Status</th>
                                <th>Type Tax</th>
                                <th>Type Bayar</th>
                                <th>Ttl Sale</th>
                                <th>Ttl Rsv</th>
                                <th>Srv Cost</th>
                                <th>Pajak</th>
                                <th>Pjk Byr</th>
                                <!--<th>Total</th>-->
                                <th>Diskon</th>
                                <th>Jumlah</th>
                                <th>Ttl HPP</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($reports))
                            {{--*/ $no = 0; /*--}}
                            @foreach($reports as $report)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $report['nota'] }}</td>
                                <td>{{ $report['state'] }}</td>
                                <td>{{ $report['type_tax'] }}</td>
                                <td>{{ ucwords($report['type_bayar']) }}</td>
                                <td style="text-align:right;">{{ number_format($report['total_penjualan'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($report['total_reservasi'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($report['total_service'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($report['pajak'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($report['pajak_pembayaran'], 0, ',', '.') }}</td>
                                <!--<td style="text-align:right;">{{ number_format($report['total_akhir'], 0, ',', '.') }}</td>-->
                                <td style="text-align:right;">{{ number_format($report['diskon'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($report['jumlah'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($report['total_hpp'], 0, ',', '.') }}</td>
                                <td>
                                    {{--*/ $disabled = ( $report['state'] != 'Closed' && $report['state'] != 'Merged' ) ? 'disabled' : '' /*--}}
                                    <a href="{{ url('/report/pertanggal/detail/'.$report['id']) }}" data-toggle="modal"
                                        data-target="#ajax" class="btn btn-sm blue {{ $disabled }}">
                                        <i class="icon-search"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td colspan="4">Total</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_penjualan'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_reservasi'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_service'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('pajak'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('pajak_pembayaran'), 0, ',', '.') }}</td>
                                <!--<td style="text-align:right;">{{ number_format(collect($reports)->sum('total_akhir'), 0, ',', '.') }}</td>-->
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('diskon'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('jumlah'), 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format(collect($reports)->sum('total_hpp'), 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="14" style="text-align:center;">No Data Here</td>
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
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();

    $("#ajax").on("show.bs.modal", function(e) {
        $(this).removeData('bs.modal');
    });
</script>
@stop
