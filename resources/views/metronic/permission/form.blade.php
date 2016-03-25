<div class="form-body">
    <div class="form-group @if($errors->has('display')) has-error @endif">
        <label for="display" class="control-label">Opsi</label>
        {{ Form::text('display', null, ['class' => 'form-control', 'id' => 'display']) }}
        @if($errors->has('display'))<span class="help-block">{{ $errors->first('display') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('name')) has-error @endif">
        <label for="name" class="control-label">Key</label>
        {{--*/
            $opt = ['class' => 'form-control', 'id' => 'name'];
            if( isset($permission) ){
                $opt['readonly'] = 'readonly';
            }
        /*--}}
        {{ Form::text('name', null, $opt) }}
        @if($errors->has('name'))<span class="help-block">{{ $errors->first('name') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
