<div class="form-body">
    <div class="form-group @if($errors->has('type')) has-error @endif">
        <label for="type" class="control-label">Status</label>
        {{ Form::text('type', null, ['class' => 'form-control', 'id' => 'type']) }}
        @if($errors->has('type'))<span class="help-block">{{ $errors->first('type') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('procentage')) has-error @endif">
        <label for="procentage" class="control-label">Prosentase ( % )</label>
        {{ Form::text('procentage', null, ['class' => 'form-control number', 'id' => 'procentage']) }}
        @if($errors->has('procentage'))<span class="help-block">{{ $errors->first('procentage') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
