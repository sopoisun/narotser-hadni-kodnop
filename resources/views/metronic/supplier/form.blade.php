<div class="form-body">
    <div class="form-group @if($errors->has('nama')) has-error @endif">
        <label for="nama" class="control-label">Nama Supplier</label>
        {{ Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) }}
        @if($errors->has('nama'))<span class="help-block">{{ $errors->first('nama') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('no_hp')) has-error @endif">
        <label for="no_hp" class="control-label">No HP</label>
        {{ Form::text('no_hp', null, ['class' => 'form-control', 'id' => 'no_hp']) }}
        @if($errors->has('no_hp'))<span class="help-block">{{ $errors->first('no_hp') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('alamat')) has-error @endif">
        <label for="alamat" class="control-label">Alamat</label>
        {{ Form::textarea('alamat', null, ['class' => 'form-control', 'id' => 'alamat', 'rows' => '4']) }}
        @if($errors->has('alamat'))<span class="help-block">{{ $errors->first('alamat') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('nama_perusahaan')) has-error @endif">
        <label for="nama_perusahaan" class="control-label">Nama Perusahaan</label>
        {{ Form::text('nama_perusahaan', null, ['class' => 'form-control', 'id' => 'nama_perusahaan']) }}
        @if($errors->has('nama_perusahaan'))<span class="help-block">{{ $errors->first('nama_perusahaan') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
