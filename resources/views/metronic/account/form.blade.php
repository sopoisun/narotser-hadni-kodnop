<div class="form-body">
    <div class="form-group @if($errors->has('nama_akun')) has-error @endif">
        <label for="nama_akun" class="control-label">Nama Akun</label>
        {{ Form::text('nama_akun', null, ['class' => 'form-control', 'id' => 'nama_akun']) }}
        @if($errors->has('nama_akun'))<span class="help-block">{{ $errors->first('nama_akun') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('type')) has-error @endif">
        <label for="type" class="control-label">Kategori</label>
        {{ Form::select('type', $types, null, ['class' => 'form-control', 'id' => 'type']) }}
        @if($errors->has('type'))<span class="help-block">{{ $errors->first('type') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow">Simpan</button>
</div>
