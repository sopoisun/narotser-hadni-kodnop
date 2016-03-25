<div class="form-body">
    <div class="form-group @if($errors->has('nama_bank')) has-error @endif">
        <label for="nama_bank" class="control-label">Nama Bank</label>
        {{ Form::text('nama_bank', null, ['class' => 'form-control', 'id' => 'nama_bank']) }}
        @if($errors->has('nama_bank'))<span class="help-block">{{ $errors->first('nama_bank') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('tax_debit')) has-error @endif">
        <label for="tax_debit" class="control-label">Pajak Debit ( % )</label>
        {{ Form::text('tax_debit', null, ['class' => 'form-control number', 'id' => 'tax_debit']) }}
        @if($errors->has('tax_debit'))<span class="help-block">{{ $errors->first('tax_debit') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('tax_credit_card')) has-error @endif">
        <label for="tax_credit_card" class="control-label">Pajak Kartu Credit ( % )</label>
        {{ Form::text('tax_credit_card', null, ['class' => 'form-control number', 'id' => 'tax_credit_card']) }}
        @if($errors->has('tax_credit_card'))<span class="help-block">{{ $errors->first('tax_credit_card') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
