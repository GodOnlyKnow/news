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
  <div class="row ads">
  </div>
  <hr>
  <div class="lead-div">
    <p class="lead">最新评论</p>
  </div>
  <div class="comments">
   
  </div>
  <hr>
  <div class="row">
    <!-- <div class="close" onclick="javascript:$(this).parent().css('display','none');">
      <i class="glyphicon glyphicon-remove"></i>
    </div> -->
    <a id="wx" href="http://www.tuike520.com/download" onclick="click()">
      <img src="{{ asset('/imgs/ads/download.png') }}" class="img-responsive">
    </a>
  </div>
</div>

<div class="select">
    <img src="{{ asset('/imgs/ads/select.png') }}" class="img-responsive">
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  var cmt = $('.comments');
  $(function(){
    
    $.get("{{ url('/api/ads') }}",function(data){
      var str = "<div class='col-md-12 col-xs-12'><a href='" +
              data.link + "'><img class='img-responsive' src='{{ asset('/') }}" +
              data.img + "' ></a></div>";
      $('.ads').html(str);
    })

    $('.select').css({
      'height' : $(window).height()
    });

    if (is_weixin()) {
      $('#wx').attr('href','#').click(function(){
        $('.select').css('display','block');
      });
    } else {
    }
    
    $.get("/api/comment/get",{ id:{{ $content->id }},pageSize:3 },function(res){
      var data = res.data.result;
      console.log(res);
      if (data.length == 0) {
        cmt.html("<div class='panel panel-default'><div class='panel-body text-center'><a href='men:{{ $content->id }}'>没人评论，快来抢沙发吧</a></div></div>");
      } else {
        for (var d in data) {
          cmt.append($("<div class='row'><div class='col-xs-2'><img class='img-responsive img-circle head-img' src='" +
                        data[d].userImg + "'></div><div class='col-xs-9'><h4>" +
                        data[d].userName + "</h4><h6 class='head-time'>"+
                        getTimeDesci(data[d].createdAt) +"</h6><h5>" + 
                        data[d].body + "</h5></div></div><hr class='hr'>"));
        }
        cmt.append($("<div class='panel panel-default'><div class='panel-body text-center'><a href='men:{{ $content->id }}'>我也要评论</a></div></div>"));
      }
    });
  });

  function is_weixin(){
    var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
      return true;
    } else {
      return false;
    }
  }
  
  function getTimeDesci(c)
	{
		var n = (new Date()).getTime();
		var tmp = n - c * 1000;
		if (tmp < 60000)
			return parseInt(tmp / 1000) + " 刚刚";
		else if (tmp < 3600000)
			return parseInt(tmp / 60000) + "分钟前";
		else if (tmp < 216000000)
			return parseInt(tmp / 3600000) + "小时前";
		else
			return getLocalTime(c);
	}
  
  function getLocalTime(nS) {     
       return (new Date(parseInt(nS) * 1000)).Format("yyyy-MM-dd hh:mm");     
  } 
</script>
@endsection
