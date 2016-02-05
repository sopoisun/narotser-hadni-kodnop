<div class="form-group">
    <label for="relation_id" class="control-label">Bank</label>
    {{ Form::select('relation_id', $banks, $selected, ['class' => 'form-control', 'id' => 'relation_id']) }}
</div>
