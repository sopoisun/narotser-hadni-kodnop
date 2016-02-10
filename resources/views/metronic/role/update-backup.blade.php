@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Permission
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
            <li>
                <a href="{{ url('/user/role') }}">Role</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Ubah Role</a></li>
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
                    <i class="icon-reorder"></i> Form ubah role
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($role, ['role' => 'form', 'class' => 'form-horizontal']) !!}
                <div class="form-body">
                    <div class="form-group @if($errors->has('name')) has-error @endif">
                        <label for="name" class="control-label col-md-2">Nama Role</label>
                        <div class="col-md-5">
                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'readonly' => 'readonly']) }}
                            @if($errors->has('name'))<span class="help-block">{{ $errors->first('name') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('display')) has-error @endif">
                        <label for="display" class="control-label col-md-2">Alias</label>
                        <div class="col-md-5">
                            {{ Form::text('display', null, ['class' => 'form-control', 'id' => 'display']) }}
                            @if($errors->has('display'))<span class="help-block">{{ $errors->first('display') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="permissions" class="control-label col-md-2">Permission</label>
                            {{--*/
                                $Cpermissions = $permissions->groupBy('key');
                                $rolePermission = $role->permissions;
                            /*--}}
                        <div class="col-md-10">
                            @foreach($Cpermissions as $key => $Cpermission)
                            <div class="row-fluid">
                                <h4>{{ ucfirst(str_replace('_', ' ', $key)) }}</h4>
                                {{--*/
                                    $pageCount = ceil($Cpermission->count() / 4);
                                /*--}}
                                @for( $i = 0; $i<$pageCount; $i++ )
                                {{--*/ $chunks = $Cpermission->forPage(($i+1), 4) /*--}}
                                    @foreach($chunks as $chunk)
                                    <div class="col-md-3" style="padding-left:0">
                                        <div class="checkbox-list">
                                            <label class="checkbox-inline">
                                                {{ Form::checkbox('permissions[]', $chunk['id'], $rolePermission->contains('name', $chunk['name']), ['id' => 'permissions']) }} {{ $chunk['display'] }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                @endfor
                                <div style="clear:both;"></div>
                            </div>
                            <br />
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" class="btn yellow">Simpan Role</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
