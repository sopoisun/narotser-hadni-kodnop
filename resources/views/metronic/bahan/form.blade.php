<div class="form-body">
    <div class="form-group @if($errors->has('nama')) has-error @endif">
        <label for="nama" class="control-label">Nama Bahan Produksi</label>
        {{ Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) }}
        @if($errors->has('nama'))<span class="help-block">{{ $errors->first('nama') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('satuan')) has-error @endif">
        <label for="type" class="control-label">Satuan</label>
        {{ Form::text('satuan', null, ['class' => 'form-control', 'id' => 'satuan']) }}
        @if($errors->has('satuan'))<span class="help-block">{{ $errors->first('satuan') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('satuan_beli')) has-error @endif">
        <label for="satuan_beli" class="control-label">Satuan Pembelian</label>
        {{ Form::text('satuan_beli', null, ['class' => 'form-control', 'id' => 'satuan_beli']) }}
        @if($errors->has('satuan_beli'))<span class="help-block">{{ $errors->first('satuan_beli') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('harga')) has-error @endif">
        <label for="harga" class="control-label">Harga</label>
        {{ Form::text('harga', null, ['class' => 'form-control', 'id' => 'harga']) }}
        @if($errors->has('harga'))<span class="help-block">{{ $errors->first('harga') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('qty_warning')) has-error @endif">
        <label for="qty_warning" class="control-label">Qty Warning</label>
        {{ Form::text('qty_warning', null, ['class' => 'form-control', 'id' => 'qty_warning']) }}
        @if($errors->has('qty_warning'))<span class="help-block">{{ $errors->first('qty_warning') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
