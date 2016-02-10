@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Ubah User Aplikasi
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/user') }}">User</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Ubah User</a></li>
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
                    <i class="icon-reorder"></i> Form ubah user
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($user, ['role' => 'form', 'class' => 'form-horizontal']) !!}
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
                            {{ Form::text('username', null, ['class' => 'form-control', 'id' => 'username', 'readonly' => 'readonly']) }}
                            @if($errors->has('username'))<span class="help-block">{{ $errors->first('username') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('roles')) has-error @endif">
                        <label for="roles" class="control-label col-md-2">Role</label>
                        <div class="col-md-10">
                            {{--*/
                                $pageCount = ceil($roles->count() / 4);
                            /*--}}
                            <div class="row-fluid">
                            @for( $i = 0; $i<$pageCount; $i++ )
                            {{--*/ $chunks = $roles->forPage(($i+1), 4) /*--}}
                                @foreach($chunks as $chunk)
                                <div class="col-md-3" style="padding-left:0">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            {{ Form::checkbox('roles[]', $chunk['id'], null, ['id' => 'roles']) }} {{ $chunk['display'] }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            @endfor
                                <div style="clear:both;"></div>
                            </div>
                            @if($errors->has('roles'))<span class="help-block" style="margin-top:10px;">{{ $errors->first('roles') }}</span>@endif
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" class="btn yellow">Simpan User</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
