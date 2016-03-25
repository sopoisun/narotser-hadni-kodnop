<div class="form-body">
    <div class="form-group @if($errors->has('nama')) has-error @endif">
        <label for="nama" class="control-label">Nama Tempat</label>
        {{ Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) }}
        @if($errors->has('nama'))<span class="help-block">{{ $errors->first('nama') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('kategori_id')) has-error @endif">
        <label for="kategori_id" class="control-label">Kategori</label>
        {{ Form::select('kategori_id', $types, null, ['class' => 'form-control', 'id' => 'kategori_id']) }}
        @if($errors->has('kategori_id'))<span class="help-block">{{ $errors->first('kategori_id') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('harga')) has-error @endif">
        <label for="harga" class="control-label">Harga</label>
        {{ Form::text('harga', null, ['class' => 'form-control', 'id' => 'harga']) }}
        @if($errors->has('harga'))<span class="help-block">{{ $errors->first('harga') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
