@if( $data->count() )
<div class="note note-warning">
    <h4 class="block">Warning! Segera isi stok.</h4>
    <p>Daftar produk yang stoknya sudah dibawah ambang batas stok.</p>
</div>
@endif
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama {{ $header }}</th>
                <th>Stok</th>
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
                <td>{{ round($d->sisa_stok, 2) }}</td>
            </tr>
            @endforeach
            @else
            <tr><td colspan="3" style="text-align:center;">No Data Here</td></tr>
            @endif
        </tbody>
    </table>
</div>
