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
            Adjustment <small>Tambah Adjustment</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/adjustment') }}">Adjustment</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Tambah Adjustment</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-reorder"></i> Form Adjustment
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formpembelian', 'url' => 'adjustment/preview']) !!}
                    <div class="form-body">
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('tanggal')) has-error @endif">
                                    <label for="tanggal" class="col-md-3 control-label">Tanggal</label>
                                    <div class="col-md-8">
                                    {{--*/ $tanggal = old('tanggal')  ? old('tanggal') : date('Y-m-d'); /*--}}
                                    {{ Form::text('tanggal', $tanggal, ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
                                    @if($errors->has('tanggal'))<span class="help-block">{{ $errors->first('tanggal') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('karyawan')) has-error @endif">
                                    <label for="karyawan" class="col-md-3 control-label">Karyawan</label>
                                    <div class="col-md-8">
                                    {{--*/ $karyawan = Auth::check() ? Auth::user()->karyawan->nama : 'Administrator'; /*--}}
                                    {{ Form::text('karyawan', $karyawan, ['class' => 'form-control', 'id' => 'karyawan', 'readonly' => 'readonly']) }}
                                    @if($errors->has('karyawan'))<span class="help-block">{{ $errors->first('karyawan') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('keterangan')) has-error @endif">
                                    <label for="keterangan" class="col-md-3 control-label">Keterangan</label>
                                    <div class="col-md-8">
                                    {{ Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'keterangan', 'rows' => 3]) }}
                                    @if($errors->has('keterangan'))<span class="help-block">{{ $errors->first('keterangan') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red btnSubmit">Simpan Adjustment</button>
                                    {{ Form::hidden('adjustment_reduction', null, ['id' => 'adjustment_reduction']) }}
                                    {{ Form::hidden('adjustment_increase', null, ['id' => 'adjustment_increase']) }}
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
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-reorder"></i> Form barang yang di adjustment
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formstuff']) !!}
                <div class="form-body">
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="col-md-3 control-label">Type</label>
                                <div class="col-md-8">
                                {{ Form::select('type', $types, null, ['class' => 'form-control', 'id' => 'type']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty" class="col-md-3 control-label">Nama</label>
                                <div class="col-md-8">
                                {{ Form::hidden('relation_id', null, ['class' => 'form-control', 'id' => 'relation_id']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_keterangan" class="col-md-3 control-label">Status</label>
                                <div class="col-md-8">
                                {{ Form::select('state', $states, null, ['class' => 'form-control', 'id' => 'state']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga" class="col-md-3 control-label">@Harga</label>
                                <div class="col-md-8">
                                {{ Form::text('harga', null, ['class' => 'form-control number', 'id' => 'harga']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty" class="col-md-3 control-label" id="QtyLabel">Qty</label>
                                <div class="col-md-8">
                                {{ Form::text('qty', null, ['class' => 'form-control number', 'id' => 'qty']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_keterangan" class="col-md-3 control-label">Keterangan</label>
                                <div class="col-md-8">
                                {{ Form::textarea('item_keterangan', null, ['class' => 'form-control', 'id' => 'item_keterangan', 'rows' => 3]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-offset-3 col-md-4">
                                <button class="btn yellow" id="btnAddBarang">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<ul  class="nav nav-tabs">
    <li class="active"><a href="#stok_reduction" data-toggle="tab">Pengurangan Stok</a></li>
    <li class=""><a href="#stok_increase" data-toggle="tab">Penambahan Stok</a></li>
</ul>

<div  class="tab-content">
    <div class="tab-pane fade active in" id="stok_reduction">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-reorder"></i>Daftar Barang Yang Dikurangi</div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tblAdjustmentReduction">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Keterangan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="stok_increase">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-reorder"></i>Daftar Barang Yang Ditambah</div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tblAdjustmentIncrease">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Keterangan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    $(".number").inputmask("decimal", {
        min: 0,
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    var optSelect2 = {
        placeholder: "Cari...",
        minimumInputLength: 1,
        ajax: {
            url: "{{ url('/ajax/bahan') }}",
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term, // search term
                    //page: page,
                    except: existBahan(),
                };
            },
            results: function (data, page) { // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to alter the remote JSON data
                return { results: data };
            },
            cache: true
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (markup) { return markup; },
        formatSelection: function(e) {
            return e.nama || e.text
        },
        formatResult: function(e) {
            if (e.loading) return e.text;
            return "<div>"+e.nama+"</div>";
        },
    };
    var txtBarang = $("#relation_id").select2(optSelect2).on('change', select2OnChange);

    $("#type").change(function(e){
        if( $(this).val() == "bahan"){
            optSelect2.ajax.url = "{{ url('/ajax/bahan') }}";
        }else{
            optSelect2.ajax.url = "{{ url('/ajax/produk') }}?without_has_bahan=Ya";
        }
        txtBarang.select2(optSelect2).on('change', select2OnChange);
    });

    function select2OnChange(e){
        $("#harga").focus();
        $("#QtyLabel").html("Qty ( "+e.added.satuan+" )");
    }

    function makeid()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $("#formstuff").submit(function(e){
        if( $("#relation_id").val() != "" && $("#qty").val() != "" && $("#harga").val() != "" ){
            var dataForm = $("#formstuff").serializeObject();
            $.ajax({
                url: "{{ url('/ajax/adjustment/item_save') }}",
                type: "POST",
                data: dataForm,
                success: function(data){
                    InsertRow(data);
                }
            });
        }else{
            toastr.warning('Mohon lengkapi input dulu');
        }
        e.preventDefault();
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function InsertRow(data){
        //console.log(oldData)
        var state = capitalizeFirstLetter(data.state);
        var num = ( $("#tblAdjustment"+state+" tbody tr").length > 1 ) ? $("#tblAdjustment"+state+" tbody tr").length : 1;
        var rowId = makeid();
        var row = "<tr id='"+rowId+"'>";
            row += "<td>"+num+"</td>";
            row += "<td>"+capitalizeFirstLetter(data.type)+"</td>";
            row += "<td>"+$("span.select2-chosen").text()+"</td>";
            row += "<td style='text-align:right;'>"+data.harga+"</td>";
            row += "<td>"+data.qty+"</td>";
            row += "<td style='text-align:right;'>"+data.subtotal+"</td>";
            row += "<td>"+data.keterangan+"</td>";
            row += "<td><a class='btn btn-sm red btn-del-adjusment' href='javascript:void(0);' data-type='"+data.type
                        +"' data-id='"+data.relation_id+"' data-state='"+data.state+"'><i class='icon-trash'></i></a></td>";
        row += "</tr>";

        if( num == 1 ){
            row += "<tr>";
                row += "<td></td>";
                row += "<td colspan='4'></td>";
                row += "<td id='total"+state+"' style='text-align:right;'></td>";
                row += "<td></td>";
                row += "<td></td>";
            row += "</tr>";

            $("#tblAdjustment"+state+" tbody").append(row);
        }else{
            $("#tblAdjustment"+state+" tbody tr:last").before(row);
        }

        //$("#type").val('bahan');
        $("#relation_id").select2('data', null);
        $("#harga").val('');
        $("#qty").val('');
        $("#item_keterangan").val('');
        $("#QtyLabel").html("Qty");

        HitungTotal(state);

        toastr.success('Sukses simpan barang');
    }

    $(document).on('click', '.btn-del-adjusment', function(){
        if( confirm('Yakin Hapus ?? ')){
            var state   = capitalizeFirstLetter($(this).data('state'));
            var params  = "type="+$(this).data('type').toLowerCase()+"&state="+$(this).data('state')+"&id="+$(this).data('id');
            var success = false;
            $.ajax({
                url: "{{ url('/ajax/adjustment/item_remove') }}",
                data: params,
                type: "GET",
                async: false,
                success: function(data){
                    success = true;
                }
            });

            if( success === true ){
                if( $("#tblAdjustment"+state+" tbody tr").length == 2 ){
                    $("#tblAdjustment"+state+" tbody").empty();
                }else{
                    $(this).parent().parent().remove();
                    reNumberingRow(state);
                }

                toastr.success('Sukses hapus barang');
            }else{
                toastr.error('Gagal simpan barang');
            }
        }
    });

    function existBahan(){
        return '';
    }

    function HitungTotal(state){
        var total = 0;
        $("#tblAdjustment"+state+" tbody tr").not(':last').each(function(){
            var val = $(this).find('td:eq(5)').text().replace(/\./g, "");
            if( val != "" ){
                total += parseFloat(val);
            }
        });
        total = total.toFixed(0).replace(/./g, function(c, i, a) {
            return i && c !== "." && ((a.length - i) % 3 === 0) ? '.' + c : c;
        });
        $("#total"+state).text(total);
    }

    function reNumberingRow(state){
        $("#tblAdjustment"+state+" tbody tr").not(':last').each(function(i){
            $(this).find("td:eq(0)").text(i+1);
        });
        HitungTotal(state);
    }

    $("#formpembelian").submit(function(e){
        var rowTbl = $.trim($("#tblAdjustmentReduction tbody").html());
        $("#adjustment_reduction").val(rowTbl);
        //console.log(rowTbl);
        rowTbl = $.trim($("#tblAdjustmentIncrease tbody").html());
        $("#adjustment_increase").val(rowTbl);
        //console.log(rowTbl);
        //e.preventDefault();
    });

    @if( old('adjustment_reduction') )
        $("#tblAdjustmentReduction tbody").html('{!! old("adjustment_reduction") !!}');
    @endif

    @if( old('adjustment_increase') )
        $("#tblAdjustmentIncrease tbody").html('{!! old("adjustment_increase") !!}');
    @endif

    @if($errors->has('no_details'))
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-bottom-right";
        toastr.warning("{{ $errors->first('no_details') }}");
    @endif

</script>
@stop
