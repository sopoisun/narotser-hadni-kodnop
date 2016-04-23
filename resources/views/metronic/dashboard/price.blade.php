@if( $data->count() )
<div class="note note-warning">
    <h4 class="block">Warning! Segera atur harga jual</h4>
    <p>Daftar produk yang prosentase pengambila labanya sudah dibawah ambang batas prosentase.</p>
</div>
@endif
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Laba (%)</th>
            </tr>
        </thead>
        <tbody>
            @if( $data->count() )
            {{--*/ $no = 0; /*--}}
            @foreach($data as $d)
            {{--*/ $no++; /*--}}
            <tr>
                <td>{{ $no }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->laba_procentage.' %' }}</td>
            </tr>
            @endforeach
            @else
            <tr><td colspan="3" style="text-align:center;">No Data Here</td></tr>
            @endif
        </tbody>
    </table>
</div>
