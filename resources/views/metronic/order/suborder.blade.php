<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Detail Sub Order #{{ $id }}</h4>
        </div>
        <div class="modal-body">
            <div class="row" style="padding:5px 10px;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--*/
                            $no = 0;
                            $total = 0;
                        /*--}}
                        @foreach($data->detail as $d)
                        {{--*/
                            $no++;
                            $total += ( $d->harga_jual * $d->qty );
                        /*--}}
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $d->produk->nama }}</td>
                            <td>{{ number_format($d->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $d->qty }}</td>
                            <td>{{ number_format($d->harga_jual * $d->qty, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach

                        <tr>
                            <td></td>
                            <td colspan="3">Total</td>
                            <td>{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn blue" data-dismiss="modal">Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
