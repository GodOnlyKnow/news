@extends('app')

@section('content')
<div class="container">
	<hr>
	<div class="row">
		<form class="form-horizontal" action="{{ url('/app/modify') }}" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" >
			<div class="form-group">
				<label class="control-label col-md-2">Android当前版本</label>
				<div class="col-md-6">
					<input class="form-control" type="text" name="version" value="{{ $out['version'] }}">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Android当前下载地址</label>
				<div class="col-md-6">
					<input class="form-control" type="text" name="down" value="{{ $out['down'] }}">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Android下载量</label>
				<div class="col-md-6">
					<input class="form-control" type="text"  value="{{ $out['android_downs'] }}" disabled>
					<input type="hidden" name="android_downs" value="{{ $out['android_downs'] }}">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">IOS当前版本</label>
				<div class="col-md-6">
					<input class="form-control" type="text" name="ios_version" value="{{ $out['ios_version'] }}">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">IOS当前下载地址</label>
				<div class="col-md-6">
					<input class="form-control" type="text" name="ios_down" value="{{ $out['ios_down'] }}">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">IOS下载量</label>
				<div class="col-md-6">
					<input class="form-control" type="text" disabled value="{{ $out['ios_downs'] }}">
					<input type="hidden" name="ios_downs" value="{{ $out['ios_downs'] }}">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2">
					<button class="btn btn-primary" type="submit">保存</button>
				</div>
			</div>
		</form>
	</div>
	<hr>
	<div class="row">
		<div id="main" style="height:286px;"></div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('echarts/build/dist/echarts.js') }}"></script>
<script type="text/javascript">
		require.config({
            paths: {
                echarts: "/echarts/build/dist"
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/line',   // 按需加载所需图表
                'echarts/chart/bar'
            ],
            function (ec) {
                var myChart = ec.init(document.getElementById('main'));
                var option = {
				    title : {
				        text: '男人帮下载统计',
				        subtext: 'version 1.0'
				    },
				    tooltip : {
				        trigger: 'axis'
				    },
				    legend: {
				        data:['IOS','Android']
				    },
				    toolbox: {
				        show : true,
				        feature : {
				            mark : {show: true},
				            dataView : {show: true, readOnly: false},
				            magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
				            restore : {show: true},
				            saveAsImage : {show: true}
				        }
				    },
				    calculable : true,
				    xAxis : [
				        {
				            type : 'category',
				            boundaryGap : true,
				            data : [
				            	@foreach ($chart as $c)
				            		"{{ $c->created_date }}",
				            	@endforeach
				            ]
				        }
				    ],
				    yAxis : [
				        {
				            type : 'value'
				        }
				    ],
				    series : [
				        {
				            name:'IOS',
				            type:'line',
				            smooth:true,
				            itemStyle: {normal: {areaStyle: {type: 'default'}}},
				            data:[
				            	@foreach ($chart as $c)
				            		{{ $c->ios }},
				            	@endforeach
				            ]
				        },
				        {
				            name:'Android',
				            type:'line',
				            smooth:true,
				            itemStyle: {normal: {areaStyle: {type: 'default'}}},
				            data:[
				            	@foreach ($chart as $c)
				            		{{ $c->android }},
				            	@endforeach
				            ]
				        }
				    ]
				};
                myChart.setOption(option);
            }
        );
</script>
@endsection