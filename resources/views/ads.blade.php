@extends('app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('jasny/jasny-bootstrap.min.css') }}">
@endsection

@section('content')
<div class="container">
	<hr>
    <!-- Create Form -->
	<div class="row">
		
		<div class="panel panel-primary">
			<div class="panel-heading">
				<a class="btn btn-default" data-toggle="collapse" href="#collapseOne" aria-controls="collapseOne">新增广告</a>

			</div>
			<div class="panel-body collapse" id="collapseOne">
			<form action="{{ url('/ads/create') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label class="control-label col-md-2">广告名称</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告图片</label>
				<div class="col-md-8">
					<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a>
                            </div>
                        </div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告链接</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="link" id="link" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告类型</label>
				<div class="col-md-8">
					<select name="type" id="type" class="form-control">
						<option value="0">页面底部广告</option>
						<option value="1">活动中心</option>
						<option value="2">页面广告-撸吧</option>
						<option value="3">页面广告-看吧</option>
						<option value="4">页面广告-笑吧</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-2">
					<button class="btn btn-primary" type="submit">新增</button>
				</div>
			</div>
			</form>
		</div>
		</div>
		</div>
	<!-- Ads List -->
	<h3>
		页面底部广告
	</h3>
	<div class="row">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <?php $cnt = 0; ?>
		  @foreach ($ads as $ad)
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="heading{{ $ad->id }}">
		      <h4 class="panel-title">
		      	<div class="row">
		        <div class="col-md-4">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $ad->id }}" aria-expanded="true" aria-controls="collapse{{ $ad->id }}">
			          # {{ ++$cnt }} {{ $ad->name }} 
			        </a>
		        </div>
		        <div class="col-md-4">
		        	{{ $ad->created_at }}
		        </div>
		        <div class="col-md-2">
		        	@if ($ad->is_start == 0)
		        		<a href="{{ url('/ads/start') }}?id={{ $ad->id }}&fn=1" class="btn btn-primary">设为APP启动图</a>
		        	@else
		        		<a href="{{ url('/ads/start') }}?id={{ $ad->id }}&fn=0" class="btn btn-success">取消APP启动图</a>
		        	@endif
		        </div>
		        <div class="col-md-2">
		        	<a href="{{ url('/ads/delete') }}/{{ $ad->id }}" class="btn btn-danger">删除</a>
		        </div>
		        </div>
		      </h4>
		    </div>
		    <div id="collapse{{ $ad->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $ad->id }}">
		      <div class="panel-body">
		        <form action="{{ url('/ads/modify') }}" enctype="multipart/form-data" method="post" class="form-horizontal">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
		        <input type="hidden" name="id" value="{{ $ad->id }}" >
		        <div class="form-group">
				<label class="control-label col-md-2">广告名称</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" value="{{ $ad->name }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告图片</label>
				<div class="col-md-8">
					<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;">
                            	<img src="{{ asset('/') . $ad->img }}">
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a>
                            </div>
                        </div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告链接</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="link" id="link" value="{{ $ad->link }}" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-2">
					<button class="btn btn-primary" type="submit">保存</button>
				</div>
			</div>
			</form>
		      </div>
		    </div>
		  </div>
		  @endforeach
		</div>
	</div>
	<h3>
		活动中心
	</h3>
	<div class="row">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <?php $cnt = 0; ?>
		  @foreach ($dds as $ad)
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="heading{{ $ad->id }}">
		      <h4 class="panel-title">
		      	<div class="row">
		        <div class="col-md-4">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $ad->id }}" aria-expanded="true" aria-controls="collapse{{ $ad->id }}">
			          # {{ ++$cnt }} {{ $ad->name }} 
			        </a>
		        </div>
		        <div class="col-md-4">
		        	{{ $ad->created_at }}
		        </div>
		        <div class="col-md-2">
		        	@if ($ad->is_start == 0)
		        		<a href="{{ url('/ads/start') }}?id={{ $ad->id }}&fn=1" class="btn btn-primary">设为APP启动图</a>
		        	@else
		        		<a href="{{ url('/ads/start') }}?id={{ $ad->id }}&fn=0" class="btn btn-success">取消APP启动图</a>
		        	@endif
		        </div>
		        <div class="col-md-2">
		        	<a href="{{ url('/ads/delete') }}/{{ $ad->id }}" class="btn btn-danger">删除</a>
		        </div>
		        </div>
		      </h4>
		    </div>
		    <div id="collapse{{ $ad->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $ad->id }}">
		      <div class="panel-body">
		        <form action="{{ url('/ads/modify') }}" enctype="multipart/form-data" method="post" class="form-horizontal">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
		        <input type="hidden" name="id" value="{{ $ad->id }}" >
		        <div class="form-group">
				<label class="control-label col-md-2">广告名称</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" value="{{ $ad->name }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告图片</label>
				<div class="col-md-8">
					<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;">
                            	<img src="{{ asset('/') . $ad->img }}">
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a>
                            </div>
                        </div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告链接</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="link" id="link" value="{{ $ad->link }}" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-2">
					<button class="btn btn-primary" type="submit">保存</button>
				</div>
			</div>
			</form>
		      </div>
		    </div>
		  </div>
		  @endforeach
		</div>
	</div>
	<h3>
		页面镶嵌广告
	</h3>
	<div class="row">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <?php $cnt = 0; ?>
		  @foreach ($aas as $ad)
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="heading{{ $ad->id }}">
		      <h4 class="panel-title">
		      	<div class="row">
		        <div class="col-md-3">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $ad->id }}" aria-expanded="true" aria-controls="collapse{{ $ad->id }}">
			          # {{ ++$cnt }} {{ $ad->name }} 
			        </a>
		        </div>
				<div class="col-md-1">
					@if ($ad->type == 2)
						撸吧
					@elseif ($ad->type == 3)
						看吧
					@else
						笑吧
					@endif
				</div>
		        <div class="col-md-4">
		        	{{ $ad->created_at }}
		        </div>
		        <div class="col-md-2">
		        	@if ($ad->is_start == 0)
		        		<a href="{{ url('/ads/start') }}?id={{ $ad->id }}&fn=1" class="btn btn-primary">设为APP启动图</a>
		        	@else
		        		<a href="{{ url('/ads/start') }}?id={{ $ad->id }}&fn=0" class="btn btn-success">取消APP启动图</a>
		        	@endif
		        </div>
		        <div class="col-md-2">
		        	<a href="{{ url('/ads/delete') }}/{{ $ad->id }}" class="btn btn-danger">删除</a>
		        </div>
		        </div>
		      </h4>
		    </div>
		    <div id="collapse{{ $ad->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $ad->id }}">
		      <div class="panel-body">
		        <form action="{{ url('/ads/modify') }}" enctype="multipart/form-data" method="post" class="form-horizontal">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
		        <input type="hidden" name="id" value="{{ $ad->id }}" >
		        <div class="form-group">
				<label class="control-label col-md-2">广告名称</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" value="{{ $ad->name }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告图片</label>
				<div class="col-md-8">
					<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;">
                            	<img src="{{ asset('/') . $ad->img }}">
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a>
                            </div>
                        </div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">广告链接</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="link" id="link" value="{{ $ad->link }}" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-2">
					<button class="btn btn-primary" type="submit">保存</button>
				</div>
			</div>
			</form>
		      </div>
		    </div>
		  </div>
		  @endforeach
		</div>
	</div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('jasny/jasny-bootstrap.min.js') }}"></script>
@endsection