@extends('app')

@section('content')
<div class="container">
    <hr>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-2">
            <a href="/stat/index/3" class="btn btn-primary btn-block">最近3天</a>
        </div>
        <div class="col-md-2">
            <a href="/stat/index/5" class="btn btn-primary btn-block">最近5天</a>
        </div>
        <div class="col-md-2">
            <a href="/stat/index/10" class="btn btn-primary btn-block">最近10天</a>
        </div>
        <div class="col-md-2">
            <a href="/stat/index/20" class="btn btn-primary btn-block">最近20天</a>
        </div>
        <div class="col-md-2">
            <a href="/stat/index/30" class="btn btn-primary btn-block">最近30天</a>
        </div>
    </div>
    <hr>
	<div class="row">
		<div id="main" style="height:500px;"></div>
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
				        text: '每日访问量与活跃用户量统计',
				        subtext: 'version 1.0'
				    },
				    tooltip : {
				        trigger: 'axis'
				    },
				    legend: {
				        data:['访问量','活跃量']
				    },
				    toolbox: {
				        show : true,
				        feature : {
				            mark : {show: true},
                            dataZoom : {show: true},
				            dataView : {show: true, readOnly: false},
				            magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
				            restore : {show: true},
				            saveAsImage : {show: true}
				        }
				    },
				    calculable : true,
                    dataZoom : {
                        show : true,
                        realtime : true,
                        start : 20,
                        end : 80
                    },
				    xAxis : [
				        {
				            type : 'category',
				            boundaryGap : true,
				            data : [
				            	@foreach ($ts as $t)
				            		"{{ $t }}",
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
				            name:'访问量',
				            type:'line',
				            smooth:true,
				            itemStyle: {normal: {areaStyle: {type: 'default'}}},
				            data:[
				            	@foreach ($pvs as $c)
				            		{{ $c }},
				            	@endforeach
				            ]
				        },
				        {
				            name:'活跃量',
				            type:'line',
				            smooth:true,
				            itemStyle: {normal: {areaStyle: {type: 'default'}}},
				            data:[
				            	@foreach ($avs as $c)
				            		{{ $c }},
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