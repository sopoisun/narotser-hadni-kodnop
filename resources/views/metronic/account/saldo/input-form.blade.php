<div class="form-body">
    <div class="form-group @if($errors->has('tanggal')) has-error @endif">
        <label for="tanggal" class="control-label">Tanggal</label>
        {{ Form::text('tanggal', ( isset($accountSaldo) ? $accountSaldo->tanggal->format('Y-m-d') : date('Y-m-d')), ['class' => 'form-control tanggalan', 'id' => 'tanggal', 'data-date-format' => 'yyyy-mm-dd']) }}
        @if($errors->has('tanggal'))<span class="help-block">{{ $errors->first('tanggal') }}</span>@endif
    </div>
    <div class="form-group @if($errors->has('account_id')) has-error @endif">
        <label for="account_id" class="control-label">Nama Akun</label>
        {{ Form::select('account_id', $accounts, null, ['class' => 'form-control', 'id' => 'account_id']) }}
        @if($errors->has('account_id'))<span class="help-block">{{ $errors->first('account_id') }}</span>@endif
    </div>
    <div id="relation-element">
    </div>
    <div class="form-group @if($errors->has('nominal')) has-error @endif">
        <label for="nominal" class="control-label">Nominal</label>
        {{ Form::text('nominal', null, ['class' => 'form-control number', 'id' => 'nominal']) }}
        @if($errors->has('nominal'))<span class="help-block">{{ $errors->first('nominal') }}</span>@endif
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn yellow btnSubmit">Simpan</button>
</div>
