<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>Pondok Indah | Software Restoran</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ url('/') }}/assets/metronic/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/metronic/plugins/bootstrap-toastr/toastr.min.css" />
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES -->
    <link href="{{ url('/') }}/assets/metronic/css/style-metronic.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/style.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/style-responsive.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ url('/') }}/assets/metronic/css/pages/login.css" rel="stylesheet" type="text/css"/>
    <link href="{{ url('/') }}/assets/metronic/css/custom.css" rel="stylesheet" type="text/css" />
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<img src="assets/img/logo-big.png" alt="" />
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
        {{ Form::open(['class' => 'login-form', 'url' => '/login']) }}
			<h3 class="form-title">Login to your account</h3>
			<div class="alert alert-error hide">
				<button class="close" data-dismiss="alert"></button>
				<span>Enter any username and password.</span>
			</div>
			<div class="form-group @if($errors->has('username')) has-error @endif">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<div class="input-icon">
					<i class="icon-user"></i>
					{{ Form::text('username', null, ['class' => 'form-control placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => 'Username']) }}
				</div>
				@if($errors->has('username'))<span class="help-block">{{ $errors->first('username') }}</span>@endif
			</div>
			<div class="form-group @if($errors->has('password')) has-error @endif">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					{{ Form::password('password', ['class' => 'form-control placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => 'Password']) }}
				</div>
				@if($errors->has('password'))<span class="help-block">{{ $errors->first('password') }}</span>@endif
			</div>
			<div class="form-actions">
				<label class="checkbox">
					{{ Form::checkbox('remember', '1') }} Remember me
				</label>
				<button type="submit" class="btn green pull-right">
					Login <i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>
		{{ Form::close() }}
		<!-- END LOGIN FORM -->
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		2013 &copy; Metronic. Admin Dashboard Template.
	</div>
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.min.js"></script>
	<script src="assets/plugins/excanvas.min.js"></script>
	<![endif]-->
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery.cookie.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-toastr/toastr.min.js"></script>
	<script src="{{ url('/') }}/assets/metronic/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="{{ url('/') }}/assets/metronic/scripts/app.js"></script>
	<script src="{{ url('/') }}/assets/metronic/scripts/login.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
	<script>
		jQuery(document).ready(function() {
		  App.init();
		  Login.init();

          toastr.options.closeButton = true;
          toastr.options.positionClass = "toast-bottom-right";
          @if(Session::has('succcess'))
          toastr.success('{{ Session::get("succcess") }}');
          @endif
          @if($errors->has('failed'))
          toastr.error('{{ $errors->first("failed") }}');
          @endif
		});
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
