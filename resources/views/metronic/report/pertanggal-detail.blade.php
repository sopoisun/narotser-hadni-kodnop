<div class="modal-dialog modal-wide">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Order #{{ $order->nota }}</h4>
        </div>
        <div class="modal-body">
            @if( $order->state == 'Closed' )
            <div class="row" style="padding: 5px 10px;">
                <ul  class="nav nav-tabs">
                    <li class="active"><a href="#tab_produks" data-toggle="tab">Daftar Produk</a></li>
                    <li class=""><a href="#tab_places" data-toggle="tab">Daftar Tempat</a></li>
                </ul>
                <div  class="tab-content">
                    <div class="tab-pane fade active in" id="tab_produks">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN SAMPLE TABLE PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption"><i class="icon-tasks"></i>Daftar Produk Yang Dipesan</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="tblDetailOrder">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Produk</th>
                                                        <th>Harga</th>
                                                        <th>Qty</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{--*/ $i = 0; /*--}}
                                                    @foreach($orderDetail as $od)
                                                    {{--*/ $i++; /*--}}
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $od->nama }}</td>
                                                        <td style="text-align:right;">{{ number_format($od->harga_jual, 0, ',', '.') }}</td>
                                                        <td>{{ $od->qty }}</td>
                                                        <td style="text-align:right;">{{ number_format($od->subtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                    @endforeach

                                                    <tr>
                                                        <td></td>
                                                        <td colspan="3">Total</td>
                                                        <td id="totalDetail" style="text-align:right;">{{ number_format(collect($orderDetail)->sum('subtotal'), 0, ',', '.') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE TABLE PORTLET-->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab_places">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN SAMPLE TABLE PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption"><i class="icon-tasks"></i>Daftar Tempat</div>
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
                                                        <th>No</th>
                                                        <th>Nama Tempat</th>
                                                        <th>Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{--*/ $i = 0; /*--}}
                                                    @foreach($orderPlaces as $op)
                                                    {{--*/ $i++; /*--}}
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ "Reservasi ".$op['place']['nama'].' - '.$op['place']['kategori']['nama'] }}</td>
                                                        <td style="text-align:right;">{{ number_format($op['harga'], 0, ',', '.') }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td>Total</td>
                                                        <td id="totalDetail" style="text-align:right;">{{ number_format(collect($orderPlaces)->sum('harga'), 0, ',', '.') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE TABLE PORTLET-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row" style="padding:5px 10px;">
                <p>Order #{{ $order->nota }} digabung dengan order #{{ $order->merge->orderRef->nota }}.</p>
            </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn blue" data-dismiss="modal">Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $(".number").css('text-align', 'right');

    function currency(bil)
    {
        return bil.toFixed(0).replace(/./g, function(c, i, a) {
            return i && c !== "." && ((a.length - i) % 3 === 0) ? '.' + c : c;
        });
    }

</script>
