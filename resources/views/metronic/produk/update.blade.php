@extends('metronic.layout')

@section('css_assets')
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
            Produk <small>ubah Produk</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/produk') }}">Produk</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Ubah Produk</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-6">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-reorder"></i> Form Produk
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($produk, ['role' => 'form', 'class' => 'form-horizontal', 'id' => 'formproduk']) !!}
                    <div class="form-body">
                        <div class="form-group @if($errors->has('nama')) has-error @endif">
                            <label for="nama" class="col-md-3 control-label">Nama Produk</label>
                            <div class="col-md-8">
                            {{ Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) }}
                            @if($errors->has('nama'))<span class="help-block">{{ $errors->first('nama') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('satuan')) has-error @endif">
                            <label for="type" class="col-md-3 control-label">Satuan</label>
                            <div class="col-md-8">
                            {{ Form::text('satuan', null, ['class' => 'form-control', 'id' => 'satuan']) }}
                            @if($errors->has('satuan'))<span class="help-block">{{ $errors->first('satuan') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('satuan_beli')) has-error @endif">
                            <label for="satuan_beli" class="col-md-3 control-label">Satuan Beli</label>
                            <div class="col-md-8">
                            {{ Form::text('satuan_beli', null, ['class' => 'form-control', 'id' => 'satuan_beli']) }}
                            @if($errors->has('satuan_beli'))<span class="help-block">{{ $errors->first('satuan_beli') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('qty_warning')) has-error @endif">
                            <label for="qty_warning" class="col-md-3 control-label">Qty Warning</label>
                            <div class="col-md-8">
                            {{ Form::text('qty_warning', null, ['class' => 'form-control', 'id' => 'qty_warning']) }}
                            @if($errors->has('qty_warning'))<span class="help-block">{{ $errors->first('qty_warning') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('produk_kategori_id')) has-error @endif">
                            <label for="produk_kategori_id" class="col-md-3 control-label">Kategori</label>
                            <div class="col-md-8">
                            {{ Form::select('produk_kategori_id', $kategoris, null, ['class' => 'form-control', 'id' => 'produk_kategori_id']) }}
                            @if($errors->has('produk_kategori_id'))<span class="help-block">{{ $errors->first('produk_kategori_id') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-8">
                                <div class="checkbox-list">
                                    <label class="checkbox-inline">
                                        {{ Form::checkbox('konsinyasi', 'Ya', null, ['id' => 'konsinyasi']) }} Konsinyasi
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                            <label for="supplier" class="col-md-3 control-label">Supplier</label>
                            <div class="col-md-8">
                            {{ Form::text('supplier', null, ['class' => 'form-control', 'id' => 'supplier', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('supplier_id', null, ['id' => 'supplier_id']) }}
                            @if($errors->has('supplier_id'))<span class="help-block">{{ $errors->first('supplier_id') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('hpp')) has-error @endif">
                            <label for="hpp" class="col-md-3 control-label">Biaya Produksi</label>
                            <div class="col-md-8">
                            {{ Form::text('hpp', null, ['class' => 'form-control', 'id' => 'hpp']) }}
                            @if($errors->has('hpp'))<span class="help-block">{{ $errors->first('hpp') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="use_mark_up" class="col-md-3 control-label">Pakai Mark Up</label>
                            <div class="col-md-8">
                                {{ Form::select('use_mark_up', ['Tidak' => 'Tidak', 'Ya' => 'Ya'], null, ['class' => 'form-control', 'id' => 'use_mark_up']) }}
                            </div>
                        </div>
                        <div id="methodeManual">
                            <div class="form-group @if($errors->has('harga')) has-error @endif">
                                <label for="harga" class="col-md-3 control-label">Harga</label>
                                <div class="col-md-8">
                                {{ Form::text('harga', null, ['class' => 'form-control', 'id' => 'harga']) }}
                                @if($errors->has('harga'))<span class="help-block">{{ $errors->first('harga') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="laba" class="col-md-3 control-label">Laba</label>
                                <div class="col-md-8">
                                {{ Form::text('laba', null, ['class' => 'form-control', 'id' => 'laba', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="laba_procentage" class="col-md-3 control-label">Laba ( % )</label>
                                <div class="col-md-8">
                                {{ Form::text('laba_procentage', null, ['class' => 'form-control', 'id' => 'laba_procentage', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                        </div>

                        <div id="methodeMarkUp">
                            <div class="form-group @if($errors->has('mark_up')) has-error @endif">
                                <label for="mark_up" class="col-md-3 control-label">Mark Up ( % )</label>
                                <div class="col-md-8">
                                {{ Form::text('mark_up', null, ['class' => 'form-control', 'id' => 'mark_up']) }}
                                @if($errors->has('mark_up'))<span class="help-block">{{ $errors->first('mark_up') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mark_up_laba" class="col-md-3 control-label">Laba</label>
                                <div class="col-md-8">
                                {{ Form::text('mark_up_laba', null, ['class' => 'form-control', 'id' => 'mark_up_laba', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mark_up_harga" class="col-md-3 control-label">Harga Asli</label>
                                <div class="col-md-8">
                                {{ Form::text('mark_up_harga', null, ['class' => 'form-control', 'id' => 'mark_up_harga', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mark_up_normalize" class="col-md-3 control-label">Normalisasi</label>
                                <div class="col-md-8">
                                {{ Form::text('mark_up_normalize', null, ['class' => 'form-control', 'id' => 'mark_up_normalize', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mark_up_harga_jual" class="col-md-3 control-label">Harga Jual</label>
                                <div class="col-md-8">
                                {{ Form::text('mark_up_harga_jual', null, ['class' => 'form-control', 'id' => 'mark_up_harga_jual', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="col-md-offset-3 col-md-8">
                            <button type="submit" class="btn blue btnSubmit">Simpan</button>
                            {{ Form::hidden('produk_details', null, ['id' => 'produk_details']) }}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption"><i class="icon-coffee"></i>Bahan Produksi</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body">
                <button id="btnAddBahan" class="btn btn-success" style="margin-bottom:15px;"><i class="icon-plus"></i> Tambah Bahan</button>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tblBahan">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Bahan</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
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
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/jquery.xdomainrequest.min.js"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/typeahead.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="{{ url('/') }}/assets/metronic/plugins/select2-3.5/select2.js"></script>
@stop

@section('js_section')
<script>
    var supplierSources = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama_perusahaan'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: "{{ url('/ajax/supplier') }}",
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

    $("#qty_warning").inputmask("integer", {
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    $("#harga").inputmask("integer", {
        onUnMask: function(maskedValue, unmaskedValue) {
            //do something with the value
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    $("#mark_up").inputmask("integer", {
        min: 0,
        max: 100,
        onUnMask: function(maskedValue, unmaskedValue) {
            //do something with the value
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    $("#konsinyasi").change(function() {
        if(this.checked) {
            $("#supplier").removeAttr('disabled');
        }else{
            $("#supplier").attr('disabled', 'disabled');
            $("#supplier").val('');
            $("#supplier_id").val('');
        }
    });

    @if( old('konsinyasi') )
        $("#supplier").removeAttr('disabled');
    @else
        @if( $produk->konsinyasi )
            $("#supplier").removeAttr('disabled');
        @endif
    @endif

    $("#use_mark_up").change(function() {
        if( $(this).val() == 'Tidak' ){
            $('#methodeManual').css('display', 'inline');
            setComponentEmpty("methodeMarkUp");
        }else{
            $('#methodeMarkUp').css('display', 'inline');
            setComponentEmpty("methodeManual");
        }
    });

    function setComponentEmpty(id){
        $('#'+id).css('display', 'none');
        $('#'+id+' :input').val('');
    }

    @if( old('use_mark_up') )
        @if( old('use_mark_up') == 'Tidak' )
            $('#methodeMarkUp').css('display', 'none');
        @else
            $('#methodeManual').css('display', 'none');
        @endif
    @else
        @if($produk->use_mark_up == 'Tidak')
            $('#methodeMarkUp').css('display', 'none');
        @else
            $('#methodeManual').css('display', 'none');
        @endif
    @endif

    $("#hpp").on('input', function(){
        if( $("#use_mark_up").val() == 'Tidak' ){
            CountManual();
        }else{
            CountMarkUp();
        }
    });

    $("#harga").on('input', CountManual);

    $("#mark_up").on('input', CountMarkUp);

    function makeid()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    @if( old('produk_details') && count(json_decode(old('produk_details'))) )
        var data = {!! old('produk_details') !!};
    @else
        @if( $produk->detail->count() )
        var data = {!! $produk->detail !!};
        @endif
    @endif

    if( typeof data !== 'undefined'){
        $.each(data, function(i, val){
            //console.log(val);
            var rowId = "rowbahan_"+makeid();
            var row = createRowBahanElement(rowId, (i+1), val);
            //console.log(row)
            if( i == 0 ){
                $("#tblBahan tbody").append(row);
            }else{
                $("#tblBahan tbody tr:last").before(row);
            }

            var currentRow = $("#tblBahan tbody tr#"+rowId);
            setPluginRowComponents(currentRow, val);
        });
        //TotalBiayaProduksi();
    }else{
        CountManual();
    }

    $("#btnAddBahan").click(methodSelect2);

    function createRowBahanElement(rowId, num, oldData){
        //console.log(oldData)
        var val = (typeof oldData === 'undefined') ? "" : "value='"+oldData.bahan_id+"'";
        var row = "<tr id='"+rowId+"'>";
            row += "<td>"+num+"</td>";
            row += "<td><input type='hidden' "+val+" class='bahan_id' /></td>";
            row += "<td><input type='text' class='form-control input-xsmall qty-bahan' /></td>";
            row += "<td></td>";
            row += "<td align='right'></td>";
            row += "<td align='right'></td>";
            row += "<td><a class='btn btn-sm red btn-del-bahan' href='javascript:void(0);'><i class='icon-trash'></i></a></td>";
        row += "</tr>";

        var rowTotal = "<tr>";
            rowTotal += "<td></td>";
            rowTotal += "<td colspan='4'>Total Biaya Produksi</td>";
            rowTotal += "<td align='right' id='colTotalBahan'></td>";
            rowTotal += "<td></td>";
        rowTotal += "</tr>";

        if( num == 1 ){
            row += rowTotal;
        }

        return row;
    }

    function existBahan(){
        var bahans = '';
        $("#tblBahan tbody tr").not(':last').each(function(){
            if( $(this).find('td:eq(1)').find('input.bahan_id').val() != "" ){
                bahans += $(this).find('td:eq(1)').find('input.bahan_id').val()+"+";
            }
        });

        bahans = ( bahans.length === 0 ) ? bahans : bahans.slice(0, -1);

        return bahans;
    }

    function setPluginRowComponents(currentRow, oldData){
        // Drop Down [ Nama Bahan ] input
        //console.log("xx => "+currentRow.attr('id'))
        currentRow.find('td:eq(1)').find('input.bahan_id').select2({
            placeholder: "Cari Bahan",
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
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    var row = $(element).parent().parent();
                    //console.log(row.attr('id')+" "+id)
                    var subtotal = (typeof oldData.subtotal !== 'undefined') ? oldData.subtotal : (Math.round(oldData.harga * oldData.qty));
                    row.find('td:eq(3)').text(oldData.satuan);
                    row.find('td:eq(4)').text(oldData.harga);
                    row.find('td:eq(5)').text(subtotal);
                    var selected = {
                        id: oldData.bahan_id,
                        nama: ((typeof oldData.bahan_text !== 'undefined') ? oldData.bahan_text : oldData.nama)
                    };
                    //console.log(selected)
                    TotalBiayaProduksi();
                    callback(selected);
                }
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
        }).on("change", function (e){
            var data = e.added;
            var row = $(this).parent().parent();
            var qty = row.find('td:eq(2)').find('input').val();
            row.find('td:eq(3)').text(data.satuan);
            row.find('td:eq(4)').text(data.harga);
            if( qty != "" ){
                row.find('td:eq(5)').text( Math.round(data.harga * qty) );
            }
            TotalBiayaProduksi();
        });

        // Qty input
        if (typeof oldData !== 'undefined'){
            currentRow.find('td:eq(2)').find('input').val(oldData.qty);
        }
        currentRow.find('td:eq(2)').find('input').inputmask("decimal", {
            onUnMask: function(maskedValue, unmaskedValue) {
                return unmaskedValue;
            },
        }).css('text-align', 'left')
        .on('input', function(){
            var row = $(this).parent().parent();
            var val = $(this).val();
            var harga = row.find('td:eq(4)').text();
            var subtotal = row.find('td:eq(5)');
            if( val != '' ){
                subtotal.text(Math.round(harga*val));
            }else{
                subtotal.empty();
            }

            TotalBiayaProduksi();
        });

        // btn delete
        currentRow.find('td:eq(6)').find('a').on('click', function(e){
            if( confirm('Yakin ?') ){
                $(this).parent().parent().remove();
                if( $("#tblBahan tbody tr").length == 1 ){
                    $("#tblBahan tbody").empty();
                }else{
                    reNumberingRow();
                }
            }

            TotalBiayaProduksi();
            e.preventDefault();
        });
    }

    function reNumberingRow(){
        $("#tblBahan tbody tr").not(':last').each(function(i){
            $(this).find("td:eq(0)").text(i+1);
        });
    }

    function methodSelect2(){
        var rowId = "rowbahan_"+makeid();
        if( $("#tblBahan tbody tr").length == 0 ){
            num = 1;
            var row = createRowBahanElement(rowId, num);
            $("#tblBahan tbody").append(row);
        }else{
            num = $("#tblBahan tbody tr").length;
            var row = createRowBahanElement(rowId, num);
            //console.log(row);
            $("#tblBahan tbody tr:last").before(row);
        }

        var currentRow = $("#tblBahan tbody tr#"+rowId);
        setPluginRowComponents(currentRow);
    }

    function TotalBiayaProduksi(){
        var total = 0;
        $("#tblBahan tbody tr").not(':last').each(function(){
            var val = $(this).find('td:eq(5)').text();
            if( val != "" ){
                total += parseFloat(val);
            }
        });
        $("#colTotalBahan").text(total);
        $("#hpp").val(total);

        // update form components
        if( $("#use_mark_up").val() == 'Tidak' ){
            CountManual();
        }else{
            CountMarkUp();
        }
    }

    function CountManual(){
        if( $("#harga").val() != "" && $("#hpp").val() != "" ){
            var laba = $("#harga").val() - $("#hpp").val();
            $("#laba").val(laba);
            var laba_procentage = 0;
            if( $("#hpp").val() > 0 ){
                laba_procentage = (( $("#harga").val() - $("#hpp").val() )/$("#hpp").val())*100;
            }
            $("#laba_procentage").val(laba_procentage);
        }else{
            $("#laba").val("");
        }
    }

    function CountMarkUp(){
        if( $("#mark_up").val() != "" && $("#hpp").val() != "" ){
            var laba = $("#hpp").val() * ( $("#mark_up").val() / 100 );
            $("#mark_up_laba").val(laba);
            var harga = parseInt($("#hpp").val()) + parseInt(laba);
            $("#mark_up_harga").val(harga);
            var jumBulat = {{ config('app.bilangan_bulat') }};
            var sisaBagi = harga % jumBulat;
            var jumNormalize = 0;
            if( sisaBagi != 0 ){
                jumNormalize = jumBulat - sisaBagi;
            }
            $("#mark_up_normalize").val(jumNormalize);
            var hargaJual = harga + jumNormalize;
            $("#mark_up_harga_jual").val(hargaJual);
        }else{
            $("#mark_up_laba").val("");
            $("#mark_up_harga").val("");
            $("#mark_up_normalize").val("");
            $("#mark_up_harga_jual").val("");
        }
    }

    $("#formproduk").submit(function(e){
        var data = [];
        $("#tblBahan tbody tr").not(':last').each(function(){
            if( $(this).find('td:eq(2)').find('input').val() != "" ){
                var obj = {
                    'bahan_id': $(this).find('td:eq(1)').find('input.bahan_id').val(),
                    'bahan_text': $(this).find('td:eq(1)').find('span.select2-chosen').text(),
                    'qty': $(this).find('td:eq(2)').find('input').val(),
                    'satuan': $(this).find('td:eq(3)').text(),
                    'harga': $(this).find('td:eq(4)').text(),
                    'subtotal': $(this).find('td:eq(5)').text(),
                };
                data.push(obj);
            }
        });

        //console.log(data);
        data = JSON.stringify(data);
        $("#produk_details").val(data);
        //e.preventDefault();
    });
</script>
@stop
