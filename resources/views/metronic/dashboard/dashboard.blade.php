@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Dashboard
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row-fluid">
    <div class="col-md-4">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Produk Laba</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                @if( $produkLabaWarning->count() )
                <div class="note note-warning">
                    <h4 class="block">Warning! Segera atur harga jual</h4>
                    <p>Daftar produk yang prosentase pengambila labanya sudah dibawah ambang batas prosentase.</p>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Laba (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( $produkLabaWarning->count() )
                            {{--*/ $no = 0; /*--}}
                            @foreach($produkLabaWarning as $d)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->laba_procentage.' %' }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="3" style="text-align:center;">No Data Here</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
    <div class="col-md-4">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Produk Stok</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                @if( $produkStokWarning->count() )
                <div class="note note-warning">
                    <h4 class="block">Warning! Segera isi stok.</h4>
                    <p>Daftar produk yang stoknya sudah dibawah ambang batas stok.</p>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( $produkStokWarning->count() )
                            {{--*/ $no = 0; /*--}}
                            @foreach($produkStokWarning as $d)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->sisa_stok }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="3" style="text-align:center;">No Data Here</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
    <div class="col-md-4">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>Bahan Stok</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                @if( $bahanStokWarning->count() )
                <div class="note note-warning">
                    <h4 class="block">Warning! Segera isi stok.</h4>
                    <p>Daftar bahan yang stoknya sudah dibawah ambang batas stok.</p>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Bahan</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( $bahanStokWarning->count() )
                            {{--*/ $no = 0; /*--}}
                            @foreach($bahanStokWarning as $d)
                            {{--*/ $no++; /*--}}
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->sisa_stok }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="3" style="text-align:center;">No Data Here</td></tr>
                            @endif
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

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/chartjs/Chart.min.js"></script>
@stop

@section('js_section')
<script>
var lineChartData = {
    labels: ["Jul", "Ags", "Sep", "Okt", "Nov", "Des", "Jan", "Feb", "Mar", "Apr", "May", "Jun"],
    datasets: [{
        label: "Mutasi Masuk",
        fillColor: "rgba(88,255,110,0.2)",
        strokeColor: "rgba(88,255,110,1)",
        pointColor: "rgba(88,255,110,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(88,255,110,1)",
        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    }, {
        label: "Mutasi Keluar",
        fillColor: "rgba(255,117,117,0.2)",
        strokeColor: "rgba(255,117,117,1)",
        pointColor: "rgba(255,117,117,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(255,117,117,1)",
        data: [0, "1", 0, "3", "1", "1", 0, 0, 0, 0, 0, 0]
    }]
}
window.onload = function() {
    var ctx = $("#canvas").get(0).getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
        responsive: true
    });
}
</script>
@stop
