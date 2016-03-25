
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Merge Order #{{ $order_id }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-body">
                    <h4 style="margin-top:0;">
                        @foreach($places as $place)
                            {{ $place->nama.", " }}
                        @endforeach
                    </h4>
                    <br />
                    {!! Form::open(['role' => 'form', 'id' => 'formMergeOrder']) !!}
                    <div class="form-group">
                        <label for="tanggal">Tempat Customer</label>
                        {{ Form::text('place', null, ['class' => 'form-control', 'id' => 'place']) }}
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Order</label>
                        {{ Form::text('order_id', null, ['class' => 'form-control', 'id' => 'order_id', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="form-group">
                        <div class="checkbox-list">
                            <label>
                                <input type="checkbox" name="use_place" id="use_place" value="Ya" /> Pakai Tempat
                            </label>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn default" data-dismiss="modal">Close</button>
            <button type="button" class="btn blue" id="btnMergeOrder">Save changes</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    var orderSources = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: "{{ url('/ajax/place') }}?ready=Tidak&date={{ $tanggal }}",
            cache: false,
        },
        remote: {
            url: "{{ url('/ajax/place') }}?ready=Tidak&date={{ $tanggal }}&q=%QUERY",
            wildcard: '%QUERY'
        }
    });

    $('#place').typeahead(null, {
        name: 'd-order',
        display: 'nama',
        source: orderSources,
        templates: {
            suggestion: function(data){
                var str = "<div class='supplier_result'><p>"+data.nama+"</p><small>Order #"+data.order_id+"</small></div>";
                return str;
            },
            empty: function(){
                return '<div class="empty-message">Nama Tempat Tidak Ada...</div>';
            },
        }
    }).on('typeahead:asyncrequest', function() {
        $('#nama_produk_order').addClass('spinner');
    }).on('typeahead:asynccancel typeahead:asyncreceive', function() {
        $('#nama_produk_order').removeClass('spinner');
    }).bind('typeahead:select', function(ev, suggestion) {
        //console.log('Selection: ' + suggestion.year);
        $("#order_id").val(suggestion.order_id);
    }).bind('typeahead:active', function(){
        //alert('active');
    });

    $("#formMergeOrder").submit(function(e){
        e.preventDefault();
    })

    $("#btnMergeOrder").click(function(e){
        $(this).addClass('disabled');
        var order_id = $("#order_id").val();
        if( order_id != "" ){
            var _use_place = $("#use_place").is(":checked") ? 1 : 0;
            $.ajax({
                url: "{{ url('/ajax/order/'.$order_id.'/merge') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    to_order_id: order_id,
                    use_place: _use_place,
                },
                success: function(data){
                    if( data == 1 ){
                        location.reload();
                    }else if(data == 2){
                        toastr.info('Tidak bisa digabung !!!');
                    }else{
                        toastr.error('Errorsss....');
                    }
                }
            });
        }else{
            toastr.error('Mohon lengkapi input dulu');
        }
    });
</script>
