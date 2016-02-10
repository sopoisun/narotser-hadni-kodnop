@extends('metronic.layout')

@section('css_assets')
<link href="{{ url('/') }}/assets/metronic/css/typeahead.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Tambah User Aplikasi
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
            <li><a href="javascript:void(0)">Tambah User</a></li>
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
                    <i class="icon-reorder"></i> Form tambah user
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
                    <div class="form-group @if($errors->has('karyawan_id')) has-error @endif">
                        <label for="karyawan_id" class="control-label col-md-2">Karyawan</label>
                        <div class="col-md-5">
                            {{ Form::text('karyawan', null, ['class' => 'form-control', 'id' => 'karyawan']) }}
                            {{ Form::hidden('karyawan_id', null, ['id' => 'karyawan_id']) }}
                            @if($errors->has('karyawan_id'))<span class="help-block">{{ $errors->first('karyawan_id') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('username')) has-error @endif">
                        <label for="username" class="control-label col-md-2">Username</label>
                        <div class="col-md-5">
                            {{ Form::text('username', null, ['class' => 'form-control', 'id' => 'username']) }}
                            @if($errors->has('username'))<span class="help-block">{{ $errors->first('username') }}</span>@endif
                        </div>
                    </div>
                    <div class="form-group @if($errors->has('password')) has-error @endif">
                        <label for="password" class="control-label col-md-2">Password</label>
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

@section('js_assets')
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/jquery.xdomainrequest.min.js"></script>
<script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/typeahead.bundle.min.js" type="text/javascript"></script>
@stop

@section('js_section')
<script>
/* Search Karyawan */
var karyawanSources = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: "{{ url('/ajax/karyawan?foruser=Ya') }}",
    remote: {
        url: "{{ url('/ajax/karyawan?foruser=Ya&') }}?q=%QUERY",
        wildcard: '%QUERY'
    }
});

$('#karyawan').typeahead(null, {
    name: 'd-karyawan',
    display: 'nama',
    source: karyawanSources,
    templates: {
        suggestion: function(data){
            var str = "<div class='supplier_result'><p>"+data.nama+"</p><small>"+data.jabatan+"</small></div>";
            return str;
        },
        empty: function(){
            return '<div class="empty-message">Nama Karyawan Tidak Ada...</div>';
        },
    }
}).on('typeahead:asyncrequest', function() {
    $('#karyawan').addClass('spinner');
}).on('typeahead:asynccancel typeahead:asyncreceive', function() {
    $('#karyawan').removeClass('spinner');
}).bind('typeahead:select', function(ev, suggestion) {
    //console.log('Selection: ' + suggestion.year);
    $("#karyawan_id").val(suggestion.id);
}).bind('typeahead:active', function(){
    //alert('active');
});
/* End Search Karyawan */
</script>
@stop
