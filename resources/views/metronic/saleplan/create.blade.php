@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/css/typeahead.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2-bootstrap.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Sale Plan <small>Rencana Penjualan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/pembelian') }}">Pembelian</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/pembelian/saleplan') }}">Sale Plan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Tambah Sale Plan</a></li>
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
                <div class="caption"><i class="icon-asterisk"></i>Form Tambah Sale Plan</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['url' => '/pembelian/saleplan/add', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formSalePlan']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group @if($errors->has('tanggal')) has-error @endif">
                                    <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                        {{--*/ $tanggal = old('tanggal')  ? old('tanggal') : date('Y-m-d'); /*--}}
                                        {{ Form::text('tanggal', $tanggal, ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
                                        @if($errors->has('tanggal'))<span class="help-block">{{ $errors->first('tanggal') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-4 col-md-9">
                                    <button type="submit" class="btn red btnSubmit" id="btnSaveOrder">Simpan Sale Plan</button>
                                    {{ Form::hidden('saleplan_details', null, ['id' => 'saleplan_details']) }}
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
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption"><i class="icon-filter"></i>Input Produk</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formInputProduk']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_produk_saleplan" class="col-md-3 control-label">Nama Produk</label>
                                    <div class="col-md-8">
                                        {{ Form::text('nama_produk_saleplan', null, ['class' => 'form-control', 'id' => 'nama_produk_saleplan']) }}
                                        {{ Form::hidden('produk_id_saleplan', null, ['id' => 'produk_id_saleplan']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty_saleplan" class="col-md-3 control-label">Qty</label>
                                    <div class="col-md-8">
                                        {{ Form::text('qty_saleplan', null, ['class' => 'form-control number', 'id' => 'qty_saleplan']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_saleplan" class="col-md-3 control-label">Harga</label>
                                    <div class="col-md-8">
                                        {{ Form::text('harga_saleplan', null, ['class' => 'form-control number', 'id' => 'harga_saleplan', 'readonly' => 'readonly']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red" id="btnSaveitem">Simpan</button>
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
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-tasks"></i>Daftar Produk Yang Akan Dijual</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tblProduk">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/jquery.xdomainrequest.min.js"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/typeahead.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2.js"></script>
@stop

@section('js_section')
<script>
    $(".tanggalan").datepicker();
    $(".number").inputmask("integer", {
        min: 1,
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    /* Search Produk */
    var produkSources = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: "{{ url('/ajax/order/produk') }}",
            cache: false,
        },
        remote: {
            url: "{{ url('/ajax/order/produk') }}?q=%QUERY",
            wildcard: '%QUERY',
        }
    });

    $('#nama_produk_saleplan').typeahead(null, {
        name: 'd-produk',
        display: 'nama',
        source: produkSources,
        templates: {
            suggestion: function(data){
                var str = "<div class='supplier_result'><p>"+data.nama+"</p><small>"+data.harga+"</small></div>";
                return str;
            },
            empty: function(){
                return '<div class="empty-message">Nama Produk Tidak Ada...</div>';
            },
        }
    }).on('typeahead:asyncrequest', function() {
        $('#nama_produk_saleplan').addClass('spinner');
    }).on('typeahead:asynccancel typeahead:asyncreceive', function() {
        $('#nama_produk_saleplan').removeClass('spinner');
    }).bind('typeahead:select', function(ev, suggestion) {
        //console.log('Selection: ' + suggestion.year);
        $("#produk_id_saleplan").val(suggestion.id);
        $("#harga_saleplan").val(suggestion.harga);
        $("#qty_saleplan").focus();
    }).bind('typeahead:active', function(){
        //alert('active');
    });
    //$(".twitter-typeahead").css("width", "100%");
    /* End Search Produk */

    /* Input produk Save */
    $("#formInputProduk").submit(function(e){
        e.preventDefault();
        var submit = false;
        if( $("#qty_saleplan").val() != "" && $("#qty_saleplan").val() != 0 && $("#produk_id_saleplan").val() != "" ){
            var num = $("#tblProduk tbody tr").length == 0 ? 1 : $("#tblProduk tbody tr").length;
            var success = false;
            var _data   = {
                num:    num,
                id:     $("#produk_id_saleplan").val(),
                nama:   $("#nama_produk_saleplan").val(),
                harga:  $("#harga_saleplan").val(),
                qty:    $("#qty_saleplan").val(),
                action: "<a class='btn btn-sm red btn-del-produk' href='javascript:void(0);' data-id='"+$("#produk_id_saleplan").val()+"'><i class='icon-trash'></i></a>",
                _token: "{{ csrf_token() }}",
            };

            $.ajax({
                url:    "{{ url('/ajax/saleplan/item/save') }}",
                type:   "POST",
                async:  false,
                data:   _data,
                success: function(res, status, xhr){
                    var ct = xhr.getResponseHeader("content-type") || "";
                    if (ct.indexOf('json') > -1) {
                        success = res;
                    }
                }
            })

            if( success !== false ){
                var html = "<tr id='row_"+num+"'>";
                    html += "<td>"+num+"</td>";
                    html += "<td>"+success.nama+"</td>";
                    html += "<td style='text-align:right;'>"+success.harga+"</td>";
                    html += "<td>"+success.qty+"</td>";
                    html += "<td style='text-align:right;'>"+success.subtotal+"</td>";
                    html += "<td>"+success.action+"</td>";
                html += "</tr>";

                if( num == 1){
                    html += "<tr>";
                        html += "<td></td>";
                        html += "<td colspan='3'>Total</td>";
                        html += "<td id='totalOrder' style='text-align:right;'></td>";
                        html += "<td></td>";
                    html += "</tr>";
                }


                if( num == 1 ){
                    $("#tblProduk tbody").html(html);
                }else{
                    $("#tblProduk tbody tr:last").before(html);
                }

                HitungTotal();

                $("#nama_produk_saleplan").val("");
                $("#produk_id_saleplan").val("");
                $("#harga_saleplan").val("");
                $("#qty_saleplan").val("");
                setTimeout(function(){
                    $("#nama_produk_saleplan").focus();
                }, 1000);
            }else{
                toastr.error('Terjadi kesalahan !!');
            }
        }else{
            toastr.warning('Mohon lengkapi input dulu');
        }

        e.preventDefault();
    });
    /* End Input produk Save */

    $(document).on('click', '.btn-del-produk', function(){
        if( confirm('Yakin Hapus ?? ')){
            var params = "id="+$(this).data('id');
            var success = false;
            $.ajax({
                url: "{{ url('/ajax/saleplan/item/remove') }}",
                data: params,
                type: "GET",
                async: false,
                success: function(data){
                    success = true;
                }
            });

            if( success === true ){
                if( $("#tblProduk tbody tr").length <= 2 ){
                    $("#tblProduk tbody").empty();
                }else{
                    $(this).parent().parent().remove();
                }
                HitungTotal();
                reNumberingRow();
            }
        }
    });

    function HitungTotal(){
        var total = 0;
        $("#tblProduk tbody tr").not(':last').each(function(){
            var val = $(this).find('td:eq(4)').text().replace(/\./g, "");
            if( val != "" ){
                total += parseFloat(val);
            }
        });
        total = total.toFixed(0).replace(/./g, function(c, i, a) {
            return i && c !== "." && ((a.length - i) % 3 === 0) ? '.' + c : c;
        });
        $("#totalOrder").text(total);
    }

    function reNumberingRow(){
        $("#tblProduk tbody tr").not(':last').each(function(i){
            $(this).find("td:eq(0)").text(i+1);
        });
    }

    $("#formSalePlan").submit(function(e){
        var rowTbl = $.trim($("#tblProduk tbody").html());
        $("#saleplan_details").val(rowTbl);
    });

    @if( old('saleplan_details') )
        $("#tblProduk tbody").html('{!! old("saleplan_details") !!}');
    @endif

    @if($errors->has('no_details'))
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-bottom-right";
        toastr.warning("{{ $errors->first('no_details') }}");
    @endif
</script>
@stop
