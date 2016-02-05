<div class="form-body">
    <div class="form-group @if($errors->has('nama_bank')) has-error @endif">
        <label for="nama_bank" class="control-label">Nama Bank</label>
        {{ Form::text('nama_bank', null, ['class' => 'form-control', 'id' => 'nama_bank']) }}
        @if($errors->has('nama_bank'))<span class="help-block">{{ $errors->first('nama_bank') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('credit_card_tax')) has-error @endif">
        <label for="credit_card_tax" class="control-label">Pajak Kartu Kredit ( % )</label>
        {{ Form::text('credit_card_tax', null, ['class' => 'form-control number', 'id' => 'credit_card_tax']) }}
        @if($errors->has('credit_card_tax'))<span class="help-block">{{ $errors->first('credit_card_tax') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow">Simpan</button>
</div>
