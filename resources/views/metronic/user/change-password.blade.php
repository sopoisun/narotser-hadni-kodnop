@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Ubah Password
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Ubah Password</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-reorder"></i> Form ubah
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form', 'class' => 'form-horizontal']) !!}
                <div class="form-body">
                    <div class="form-group @if($errors->has('karyawan')) has-error @endif">
                        <label for="karyawan" class="control-label col-md-2">Karyawan</label>
                        <div class="col-md-5">
                            {{ Form::text('karyawan', $user->karyawan->nama, ['class' => 'form-control', 'id' => 'karyawan', 'readonly' => 'readonly']) }}
                            @if($errors->has('karyawan'))<span class="help-block">{{ $errors->first('karyawan') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('username')) has-error @endif">
                        <label for="username" class="control-label col-md-2">Username</label>
                        <div class="col-md-5">
                            {{ Form::text('username', $user->username, ['class' => 'form-control', 'id' => 'username', 'readonly' => 'readonly']) }}
                            @if($errors->has('username'))<span class="help-block">{{ $errors->first('username') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('old_password')) has-error @endif">
                        <label for="old_password" class="control-label col-md-2">Old Password</label>
                        <div class="col-md-5">
                            {{ Form::password('old_password', ['class' => 'form-control', 'id' => 'old_password']) }}
                            @if($errors->has('old_password'))<span class="help-block">{{ $errors->first('old_password') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('password')) has-error @endif">
                        <label for="password" class="control-label col-md-2">New Password</label>
                        <div class="col-md-5">
                            {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                            @if($errors->has('password'))<span class="help-block">{{ $errors->first('password') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                        <label for="password_confirmation" class="control-label col-md-2">Password Konfirm</label>
                        <div class="col-md-5">
                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) }}
                            @if($errors->has('password_confirmation'))<span class="help-block">{{ $errors->first('password_confirmation') }}</span>@endif
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" class="btn yellow">Simpan Perubahan</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
