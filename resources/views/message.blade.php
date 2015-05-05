@extends('app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('switch/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('content')
<div class="container">
	<hr>
    <!-- Create Form -->
	<div class="row">
		
		<div class="panel panel-primary">
			<div class="panel-heading">
				<a class="btn btn-default" data-toggle="collapse" href="#collapseOne" aria-controls="collapseOne">新增消息</a>

			</div>
			<div class="panel-body collapse" id="collapseOne">
			<form action="{{ url('/message/create') }}" method="post" class="form-horizontal">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label class="control-label col-md-2">消息名称</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">消息内容</label>
				<div class="col-md-8">
					<textarea class="form-control"  name="body" id="body"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">推送IOS</label>
				<div class="col-md-8">
					<div class="switch">
						<input class="form-control" checked type="checkbox" name="ios" id="ios" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">推送Android</label>
				<div class="col-md-8">
					<div class="switch">
						<input class="form-control" checked type="checkbox" name="android" id="android" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-2">
					<button class="btn btn-primary" type="submit">推送</button>
				</div>
			</div>
			</form>
		</div>
		</div>
		</div>
	<!-- Ads List -->
	<div class="row">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <?php $cnt = 0; ?>
		  @foreach ($messages as $ad)
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="heading{{ $ad->id }}">
		      <h4 class="panel-title">
		        <div class="row">
		        <div class="col-md-2"> 
		        	{{ $ad->created_at }}
		        </div>
		        
		        <div class="col-md-3">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $ad->id }}" aria-expanded="true" aria-controls="collapse{{ $ad->id }}">
		          {{ $ad->name }} # {{ ++$cnt }}
		        </a>
		        </div>

		        <div class="col-md-5">
		        </div>
		        <div class="col-md-2">
		        	<a href="{{ url('/message/delete') }}/{{ $ad->id }}" class="btn btn-primary">删除</a>
		        </div>
		      </h4>
		    </div>
		    <div id="collapse{{ $ad->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $ad->id }}">
		      <div class="panel-body">
		        <form action="{{ url('/message/modify') }}" enctype="multipart/form-data" method="post" class="form-horizontal">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
		        <input type="hidden" name="id" value="{{ $ad->id }}" >
		        <div class="form-group">
				<label class="control-label col-md-2">消息名称</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" value="{{ $ad->name }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">消息内容</label>
				<div class="col-md-8">
					<textarea class="form-control" name="body" id="body">{{ $ad->body }}</textarea>
				</div>
			</div>
				  <?php
				  	date_default_timezone_set("PRC");
				  	$start = strtotime($ad->created_at);
				  	$now = strtotime('now');
				  	$sub = $now - $start;
				  ?>
			<div class="form-group">
				<label class="control-label col-md-1">Android: </label>
				<div class="col-md-2">
					{{ $ad->created_at }}
				</div>
				<div class="col-md-6">
					<div class="progress">
					  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" @if ($ad->android_time == 0) style="width:100%;" @else style="width: {{ ($sub > $ad->android_time) ? 100 : $sub * 100000 / $ad->android_time }}%" @endif>
					  </div>
					</div>
				</div>
				<div class="col-md-2">
					<?php 
						$end = $start + $ad->android_time / 1000;
						echo date("Y-m-d H:i:s",$end);
					?>
				</div>
				@if ($ad->android_time != 0)
				<div class="col-md-1">
					<a href="{{ url('/message/stop') }}?id={{ $ad->id }}&fn=1" class="btn btn-success">
						<i class="glyphicon glyphicon-stop"></i>
					</a>
				</div>
				@endif
			</div>
			<div class="form-group">
				<label class="control-label col-md-1">IOS：</label>
				<div class="col-md-2">
					{{ $ad->created_at }}
				</div>
				<div class="col-md-6">
					<div class="progress">
					  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" @if ($ad->ios_time == 0) style="width:100%;" @else style="width: {{ ($sub > $ad->ios_time) ? 100 : $sub * 100000 / $ad->ios_time }}%" @endif >
					  </div>
					</div>
				</div>
				<div class="col-md-2">
					<?php 
						$end = $start + ($ad->ios_time / 1000);
						echo date("Y-m-d H:i:s",$end);
					?>
				</div>
				@if ($ad->ios_time != 0)
				<div class="col-md-1">
					<a href="{{ url('/message/stop') }}?id={{ $ad->id }}&fn=0" class="btn btn-success">
						<i class="glyphicon glyphicon-stop"></i>
					</a>
				</div>
				@endif
			</div>	
			<div class="form-group">
				@if ($ad->android_time != 0 || $ad->ios_time != 0)
				<div class="col-md-offset-2 col-md-2">
					<a class="btn btn-success btn-block" href="{{ url('/message/stop') }}?id={{ $ad->id }}&fn=2">停止推送</a>
				</div>
				@endif
			</div>
			</form>
		      </div>
		    </div>
		  </div>
		  @endforeach
		</div>
	</div>
	<div class="row">
		<?php echo $messages->render(); ?>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('switch/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">
	$("#ios,#android").bootstrapSwitch();
</script>
@endsection