<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Cancel Order #{{ $order_id }}</h4>
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
                    {!! Form::open(['role' => 'form', 'id' => 'formCancelOrder']) !!}
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        {{ Form::text('tanggal', date('Y-m-d'), ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        {{ Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'keterangan', 'rows' => 3]) }}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn default" data-dismiss="modal">Close</button>
            <button type="button" class="btn blue" id="btnCancelOrder">Save changes</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $(".tanggalan").datepicker();

    $("#formCancelOrder").submit(function(e){
        e.preventDefault();
    });

    $("#btnCancelOrder").click(function(e){
        $(this).addClass('disabled');
        $.ajax({
            url: "{{ url('/ajax/order/'.$order_id.'/cancel') }}",
            type: "POST",
            data: {
                tanggal: $("#tanggal").val(),
                keterangan: $("#keterangan").val(),
                _token: "{{ csrf_token() }}",
            },
            success: function(data){
                if( data == 1 ){
                    location.reload();
                }else{
                    toastr.error('Errorsss....');
                }
            }
        });
    });
</script>
