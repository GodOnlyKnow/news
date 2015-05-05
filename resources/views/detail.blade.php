@extends('default')

@section('title')
{{ $content->title }}
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('froala/css/froala_page.min.css') }}">
@endsection

@section('content')
<div class="container">
  <hr>
  <div class="row detail-title">
    <div class="col-xs-12 col-md-12 android-title">
      {{ $content->title }}
    </div>
  </div>
  <div class="row title-tip">
    <p class="col-xs-8">
      {{ $content->created_at }}
    </p>
    <p class="col-xs-4">
      访问量：{{ $content->visited }}
    </p>
  </div>
  <div class="row bodys">
    <div class="col-xs-12 col-md-12">
      <?php echo htmlspecialchars_decode($content->body) ?>
    </div>
  </div>
  <hr>
  <div class="row ads"></div>
  <hr>
  <div class="lead-div">
    <p class="lead">最新评论</p>
  </div>
  <div class="comments">
   
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/zipimg.js') }}"></script>
<script type="text/javascript">
  $(function(){
    var cmt = $('.comments');
    $.get("{{ url('/api/ads') }}",function(data){
      var str = "<div class='col-md-12 col-xs-12'><a href='" +
              data.link + "'><img class='img-responsive' src='/phpThumb/phpThumb.php?src=/" +
              data.img + "&w=800&q=80' ></a></div>";
      $('.ads').html(str);
    })
    console.log((new Date()).getTime());
    $.get("/api/comment/get",{ id:{{ $content->id }},pageSize:3 },function(res){
      var data = res.data.result;
      console.log(res);
      if (data.length == 0) {
        cmt.html("暂无评论");
      } else {
        for (var d in data) {
          cmt.append($("<div class='row'><div class='col-xs-3'><img class='img-responsive img-circle' src='" +
                        data[d].userImg + "'></div><div class='col-xs-9'><h4>" +
                        data[d].userName + "</h4><h5>" + 
                        data[d].body + "</h5><br><h6>" +
                        getTimeDesci(data[d].createdAt) + "</h6></div></div><hr>"));
        }
        cmt.append($("<div class='panel panel-default'><div class='panel-body text-center'><a href='men:{{ $content->id }}'>加载更多</a></div></div>"));
      }
    });
  });
  
  function getTimeDesci(c)
	{
		var n = (new Date()).getTime();
		var tmp = n - c * 1000;
		if (tmp < 60000)
			return parseInt(tmp / 1000) + "秒前发布";
		else if (tmp < 3600000)
			return parseInt(tmp / 60000) + "分钟前发布";
		else if (tmp < 216000000)
			return parseInt(tmp / 3600000) + "小时前发布";
		else
			return "发布于：" + getLocalTime(c);
	}
  
  function getLocalTime(nS) {     
       return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");      
  } 
</script>
@endsection
