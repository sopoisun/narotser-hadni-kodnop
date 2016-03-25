<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Return Item Order #{{ $orderDetail->order->nota }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-body">
                    {!! Form::open(['role' => 'form', 'id' => 'formCancelOrder']) !!}
                    <div class="form-group">
                        <label for="qty">Qty</label>
                        {{ Form::text('qty', (( $orderDetail->detailReturn != null ) ? $orderDetail->detailReturn->qty : 0), ['class' => 'form-control number', 'id' => 'qty']) }}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn default" data-dismiss="modal">Close</button>
            <button type="button" class="btn blue" id="btnReturnItem">Save changes</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $(".number").inputmask("integer", {
        min: 0,
        max: {{ $orderDetail->qty }},
        onUnMask: function(maskedValue, unmaskedValue) {
            return unmaskedValue;
        },
    }).css('text-align', 'left');

    $("#formCancelOrder").submit(function(e){
        e.preventDefault();
    });

    $("#btnReturnItem").click(function(e){
        $(this).addClass('disabled');
        $.ajax({
            url: "{{ url('/ajax/order/detail/return') }}",
            type: "POST",
            data: {
                id: "{{ $orderDetail->id }}",
                qty: $("#qty").val(),
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
