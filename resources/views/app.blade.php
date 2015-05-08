<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('colorpicker/css/colorpicker.css') }}">
	@yield('styles')
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar main-navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">新闻后台管理</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/admin') }}">新闻管理</a></li>
					<li><a href="{{ url('/ads/index') }}">广告管理</a></li>
					<li><a href="{{ url('/message/index') }}">消息中心</a></li>
					<li><a href="{{ url('/comment/index') }}">侃吧管理</a></li>
					<li><a href="{{ url('/feedback/index') }}">反馈意见</a></li>
					<li><a href="{{ url('/app/index') }}">APP设置</a></li>
					<li><a href="{{ url('/stat/index') }}">数据统计</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">登陆</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->username }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">注销</a></li>
							</ul>
						</li>
					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="{{ url('/auth/logout') }}">返回前台</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<button id="colors" class="btn btn-success navbar-btn">背景</button>
					<button id="forcolor" class="btn btn-success navbar-btn">前景</button>
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('colorpicker/js/colorpicker.js') }}"></script>
	<script src="{{ asset('js/admin.js') }}"></script>
	<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
	@yield('scripts')
</body>
</html>
