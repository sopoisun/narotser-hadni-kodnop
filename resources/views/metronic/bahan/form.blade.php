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
    <!--<div class="form-group @if($errors->has('harga')) has-error @endif">
        <label for="harga" class="control-label">Harga</label>
        {{ Form::text('harga', null, ['class' => 'form-control', 'id' => 'harga']) }}
        @if($errors->has('harga'))<span class="help-block">{{ $errors->first('harga') }}</span>@endif
    </div>-->
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow">Simpan</button>
</div>
