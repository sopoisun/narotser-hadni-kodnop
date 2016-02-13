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
            Pembelian <small>Tambah Pembelian</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/produk') }}">Pembelian</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Tambah Pembelian</a></li>
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
                    <i class="icon-reorder"></i> Form Pembelian
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formpembelian']) !!}
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
                                <div class="form-group @if($errors->has('total')) has-error @endif">
                                    <label for="total" class="col-md-3 control-label">Total</label>
                                    <div class="col-md-8">
                                    {{ Form::text('total', null, ['class' => 'form-control', 'id' => 'total', 'readonly' => 'readonly']) }}
                                    @if($errors->has('total'))<span class="help-block">{{ $errors->first('total') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('karyawan')) has-error @endif">
                                    <label for="karyawan" class="col-md-3 control-label">Karyawan</label>
                                    <div class="col-md-8">
                                    {{--*/ $karyawan = Auth::check() ? Auth::user()->nama : 'Administrator'; /*--}}
                                    {{ Form::text('karyawan', $karyawan, ['class' => 'form-control', 'id' => 'karyawan', 'readonly' => 'readonly']) }}
                                    @if($errors->has('karyawan'))<span class="help-block">{{ $errors->first('karyawan') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('bayar')) has-error @endif">
                                    <label for="bayar" class="col-md-3 control-label">Bayar</label>
                                    <div class="col-md-8">
                                    {{ Form::text('bayar', null, ['class' => 'form-control number', 'id' => 'bayar']) }}
                                    @if($errors->has('bayar'))<span class="help-block">{{ $errors->first('bayar') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                    <label for="supplier" class="col-md-3 control-label">Supplier</label>
                                    <div class="col-md-8">
                                    {{ Form::text('supplier', null, ['class' => 'form-control', 'id' => 'supplier']) }}
                                    {{ Form::hidden('supplier_id', null, ['id' => 'supplier_id']) }}
                                    @if($errors->has('supplier_id'))<span class="help-block">{{ $errors->first('supplier_id') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('sisa')) has-error @endif">
                                    <label for="sisa" class="col-md-3 control-label">Sisa</label>
                                    <div class="col-md-8">
                                    {{ Form::text('sisa', null, ['class' => 'form-control', 'id' => 'sisa', 'readonly' => 'readonly']) }}
                                    @if($errors->has('sisa'))<span class="help-block">{{ $errors->first('sisa') }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red">Simpan Pembelian</button>
                                    {{ Form::hidden('barangs', null, ['id' => 'barangs']) }}
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
                    <i class="icon-reorder"></i> Form barang yang dibeli
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'formstuff']) !!}
                <div class="form-body">
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="col-md-3 control-label">Type</label>
                                <div class="col-md-8">
                                {{ Form::select('type', ['bahan' => 'Bahan', 'produk' => 'Produk'], null, ['class' => 'form-control', 'id' => 'type']) }}
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
                                <label for="qty" class="col-md-3 control-label">Qty</label>
                                <div class="col-md-8">
                                {{ Form::text('qty', null, ['class' => 'form-control number', 'id' => 'qty']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="satuan" class="col-md-3 control-label">Satuan</label>
                                <div class="col-md-8">
                                {{ Form::text('satuan', null, ['class' => 'form-control', 'id' => 'satuan']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Konversi ke bahan</h4>
                    <hr />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga" class="col-md-3 control-label">Harga</label>
                                <div class="col-md-8">
                                {{ Form::text('harga', null, ['class' => 'form-control number', 'id' => 'harga']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stok" class="col-md-3 control-label" id="satuanBrg">Stok</label>
                                <div class="col-md-8">
                                {{ Form::text('stok', null, ['class' => 'form-control number', 'id' => 'stok']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-offset-3 col-md-4">
                                <button type="button" class="btn yellow" id="btnAddBarang">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption"><i class="icon-coffee"></i>Daftar barang yang dibeli</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tblPembelian">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Nama</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Stok Gdg</th>
                                <th>@harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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

    var supplierSources = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama_perusahaan'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url : "{{ url('/ajax/supplier') }}",
            cache: false,
        },
        remote: {
            url: "{{ url('/ajax/supplier') }}?q=%QUERY",
            wildcard: '%QUERY'
        }
    });

    $('#supplier').typeahead(null, {
        name: 'd-supplier',
        display: 'nama_perusahaan',
        source: supplierSources,
        templates: {
            suggestion: function(data){
                var str = "<div class='supplier_result'><p>"+data.nama_perusahaan+"</p><small>"+data.nama+"</small></div>";
                return str;
            },
            empty: function(){
                return '<div class="empty-message">Nama Supplier Tidak Ada...</div>';
            },
        }
    }).on('typeahead:asyncrequest', function() {
        $('#supplier').addClass('spinner');
    }).on('typeahead:asynccancel typeahead:asyncreceive', function() {
        $('#supplier').removeClass('spinner');
    }).bind('typeahead:select', function(ev, suggestion) {
        //console.log('Selection: ' + suggestion.year);
        $("#supplier_id").val(suggestion.id);
    }).bind('typeahead:active', function(){
        //alert('active');
    });
    //$(".twitter-typeahead").css("width", "100%");

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
                    page: page,
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
        $("#satuanBrg").html("Stok / "+e.added.satuan);
    }

    $(".number").inputmask("integer", {
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    function makeid()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    $("#btnAddBarang").click(addBarang)

    $("#stok").on('keypress', function(e){
        if( e.keyCode == 13){
            addBarang();
        }
    });

    function addBarang(){
        if( $("#relation_id").val() != "" && $("#qty").val() != "" && $("#satuan").val() != ""
                && $("#harga").val() != "" && $("#stok").val() != "" ){
            var _url = "";
            if( $("#type").val() == "bahan" ){
                _url = "{{ url('/ajax/bahan/stok') }}";
            }else{
                _url = "{{ url('/ajax/produk/stok') }}";
            }

            var _data = $("#formstuff").serialize();
            _data  += "&id="+$("#relation_id").val();

            $.ajax({
                url: _url,
                type: "GET",
                data: _data,
                success: function(data){
                    InsertRow(data);
                }
            });
        }else{
            toastr.warning('Mohon lengkapi input dulu');
        }
    }

    function InsertRow(data){
        //console.log(oldData)
        var num = ( $("#tblPembelian tbody tr").length > 1 ) ? $("#tblPembelian tbody tr").length : 1;
        var rowId = makeid();
        var val = (typeof oldData === 'undefined') ? "" : "value='"+oldData.bahan_id+"'";
        var row = "<tr id='"+rowId+"'>";
            row += "<td>"+num+"</td>";
            row += "<td>"+data.type+"</td>";
            row += "<td>"+data.nama+"</td>";
            row += "<td>"+data.qty+"</td>";
            row += "<td align='right'>"+data.harga+"</td>";
            row += "<td>"+data.stok+" "+data.satuan_stok+"</td>";
            row += "<td>"+data.stok_gudang+" "+data.satuan_stok+"</td>";
            row += "<td align='right'>"+data.harga_per_satuan+"</td>";
            row += "<td><a class='btn btn-sm red btn-del-beli' href='javascript:void(0);' data-type='"+data.type
                        +"' data-id='"+data.id+"'><i class='icon-trash'></i></a></td>";
        row += "</tr>";

        var rowTotal = "<tr>";
            rowTotal += "<td></td>";
            rowTotal += "<td colspan='3'>Total</td>";
            rowTotal += "<td align='right' id='colTotalBahan'></td>";
            rowTotal += "<td colspan='4'></td>";
        rowTotal += "</tr>";

        if( num == 1 ){
            row += rowTotal;
        }

        if( $("#tblPembelian tbody tr").length == 0){
            $("#tblPembelian tbody").append(row);
        }else{
            $("#tblPembelian tbody tr:last").before(row);
        }

        //$("#type").val('bahan');
        $("#relation_id").select2('data', null);
        $("#qty").val('');
        $("#satuan").val('');
        $("#harga").val('');
        $("#stok").val('');

        toastr.success('Sukses simpan barang');

        TotalPembelian();
    }

    function TotalPembelian(){
        var total = 0;
        $("#tblPembelian tbody tr").not(':last').each(function(){
            var val = $(this).find('td:eq(4)').text();
            if( val != "" ){
                total += parseFloat(val);
            }
        });
        $("#colTotalBahan").text(total);
        $("#total").val(total);

        // update form components
        if( $("#bayar").val() != "" ){
            var sisa = total - $("#bayar").val() ;
            if( sisa < 0 ){
                sisa = 0;
            }
            $("#sisa").val(sisa);
        }
    }

    $("#bayar").on('input', function(){
        if( $(this).val() != "" && $("#total").val() != "" ){
            var sisa = $("#total").val() - $(this).val();
            if( sisa < 0 ){
                sisa = 0;
            }
            $("#sisa").val(sisa);
        }else{
            $("#sisa").val("");
        }
    });

    $(document).on('click', '.btn-del-beli', function(){
        if( confirm('Yakin Hapus ?? ')){
            var params = "type="+$(this).data('type').toLowerCase()+"&id="+$(this).data('id');
            var success = false;
            $.ajax({
                url: "{{ url('/ajax/pembelian/item/remove') }}",
                data: params,
                type: "GET",
                async: false,
                success: function(data){
                    success = true;
                }
            });

            if( success === true ){
                if( $("#tblPembelian tbody tr").length <= 2 ){
                    $("#tblPembelian tbody").empty();
                }else{
                    $(this).parent().parent().remove();
                }
                TotalPembelian();
                reNumberingRow();
            }
        }
    });

    function existBahan(){
        var bahans = '';
        $("#tblPembelian tbody tr").not(':last').each(function(){
            if( $(this).find('td:eq(8)').find('a.btn-del-beli').data('type').toLowerCase() == $("#type").val() ){
                bahans += $(this).find('td:eq(8)').find('a.btn-del-beli').data('id')+"+";
            }
        });

        bahans = ( bahans.length === 0 ) ? bahans : bahans.slice(0, -1);
        return bahans;
    }

    function reNumberingRow(){
        $("#tblPembelian tbody tr").not(':last').each(function(i){
            $(this).find("td:eq(0)").text(i+1);
        });
    }

    $("#formpembelian").submit(function(e){
        var rowTbl = $.trim($("#tblPembelian tbody").html());
        $("#barangs").val(rowTbl);
        //console.log(rowTbl);
        //e.preventDefault();
    });

    @if( old('barangs') )
        $("#tblPembelian tbody").html('{!! old("barangs") !!}');
    @endif

    @if($errors->has('no_details'))
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-bottom-right";
        toastr.warning("{{ $errors->first('no_details') }}");
    @endif

</script>
@stop
