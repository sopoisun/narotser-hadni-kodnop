<div class="modal-dialog modal-wide">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Daftar Produk Yang Ditangani {{ $karyawan->nama }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN SAMPLE TABLE PORTLET-->
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption"><i class="icon-tasks"></i>Daftar Produk</div>
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
                                        {{--*/
                                            $i = 0;
                                            $total = 0;
                                        /*--}}
                                        @foreach($orderProduks as $op)
                                        {{--*/
                                            $i++;
                                            $total += $op->total_penjualan;
                                        /*--}}
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $op->nama }}</td>
                                            <td style="text-align:right;">{{ number_format($op->harga_jual, 0, ',', '.') }}</td>
                                            <td>{{ $op->qty }}</td>
                                            <td style="text-align:right;">{{ number_format($op->total_penjualan, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td colspan="3">Total</td>
                                            <td id="totalDetail" style="text-align:right;">{{ number_format($total, 0, ',', '.') }}</td>
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
        <div class="modal-footer">
            <button type="button" class="btn blue" data-dismiss="modal">Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
