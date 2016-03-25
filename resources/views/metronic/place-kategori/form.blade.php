<div class="form-body">
    <div class="form-group @if($errors->has('nama')) has-error @endif">
        <label for="nama" class="control-label">Nama Kategori</label>
        {{ Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) }}
        @if($errors->has('nama'))<span class="help-block">{{ $errors->first('nama') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
