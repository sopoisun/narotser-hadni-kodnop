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
    <div class="form-group @if($errors->has('reports')) has-error @endif">
        <label for="reports" class="control-label">Jenis Laporan</label>
        <div class="checkbox-list">
            @foreach($reports as $report)
            <label class="checkbox-inline">
                {{ Form::checkbox('reports[]', $report['id'], null, ['id' => 'reports']) }} {{ $report['display'] }}
            </label>
            @endforeach
        </div>
        @if($errors->has('reports'))<span class="help-block" style="margin-top:10px;">{{ $errors->first('reports') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
