@extends('default')

@section('title')
{{ $type->name }}
@endsection

@section('content')
<div class="container">
  <!--Carousel-->
	<div class="row">
		<div class="span12">
			<div class="carousel slide" id="carousel-370612">
				<ol class="carousel-indicators">
					<li class="active" data-slide-to="0" data-target="#carousel-370612"></li>
					@for ($i = 1;$i < $fcnt;$i++)
					<li class="" data-slide-to="{{ $i }}" data-target="#carousel-370612"></li>
					@endfor
				</ol>
				<div class="carousel-inner">
					<?php $isoo = 0; ?>
					@foreach ($focus as $css)
					<div @if ($isoo == 0) class="item active" @else class="item" @endif>
					   <?php $isoo = 1; ?>
						<a href="{{ url('home/detail') }}/{{ $css['id'] }}">
							<img src="http://news.tuike520.com/{{ $css['img'] }}" />
						</a>
						<div class="carousel-caption">
							<h4>{{ $css['title'] }}</h4>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
  <!--Content-->
  <div id="contentes" class="row contents">
	  <?php $cnt = 0; ?>
  @foreach ($contents as $cs)
  	<?php $cnt++;$tmps = ($cnt / 4) - 1; ?>
	@if ($cnt % 4 == 0 && count($ads) >= $cnt / 4)
		<div class="col-xs-12 col-sm-6" onclick='goLink("{{ $ads[$tmps]['link'] }}")' >
  			<a class="title">{{ $ads[$tmps]['name'] }}</a>
			<img class="img-responsive" src="http://news.tuike520.com/phpThumb/phpThumb.php?src=/{{ $ads[$tmps]['img'] }}&w=120&q=30" alt="" />
  		</div>
	@endif
  	@if ($cs['img'] != null || $cs['img'] != "")
  		<div class="col-xs-8 col-sm-4" onclick="goTo({{ $cs['id'] }})">
  		<a class="title">{{ $cs['title'] }}</a>
  		<p class="time">{{ $cs['times'] }}</p>
  		</div><div class="col-xs-4 col-sm-2">
  		<img class="img-responsive" src="http://news.tuike520.com/phpThumb/phpThumb.php?src=/{{ $cs['img'] }}&w=120&q=30" alt="" /></div>
  	@else
  		<div class="col-xs-12 col-sm-6" onclick="goTo({{ $cs['id'] }})">
  		<a class="title">{{ $cs['title'] }}</a>
  		<p class="time">{{ $cs['times'] }}</p>
  		</div>
  	@endif

  @endforeach
  </div>
	<div class="row text-center" id="pullUp">
		<!-- <div class="loader-inner">
			<div class="pacman"><div></div><div></div><div></div><div></div><div></div></div>
		</div> -->
	</div>
</div>
<!-- <div class="debug" style="position:absolute;top:10px;left:10px;width:100px;height:100px;border:1px solid blue;background:blue;color:#fff;">

</div> -->
@endsection

@section('scripts')
<script src="{{ asset('js/hammer.min.js') }}"></script>
<script src="{{ asset('js/jquery.hammer.min.js') }}"></script>
<script charset="utf-8">
  var carouselDiv,nowPage = 2,mScroll = null,total = {{ $cnt }};
	var pull;

  $(function(){
	pull = $('#pullUp');
    carouselDiv = $('#carousel-370612');
    //getFocus();
    initCarousel();
	//getContent(nowPage++);
	initScroll();
  });

	function initScroll()
	{
		$(window).scroll(function(){
			var srollPos = $(window).scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)
			// debug ------------------------------- //
			// var de = $('.debug');
			// de.css('top',(srollPos + 10) + "px");
			// var str = ("垂直: "+$(document).scrollTop())
			// 					+ ("<br/>文档 ："+$(document).height())
			// 					+ ('<br/>浏览器：'+$(window).height())
			// 					+ ('<br/>sroll: ' + srollPos);
			// de.html(str);
			// ------------------------------------ //
			var totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
			if (($(document).height() - 10) <= totalheight) {
				if　(nowPage <= total){
					pull.html('加载中...');
					getContent(nowPage++);
				} else {
					pull.html('已是最后一页');
				}
			}
		});

	}
	
	function goLink(s)
	{
		window.location.href = s;
	}

	function getContent(p)
	{
		console.log(p);
		$.post("{{ url('home/content') }}",{ _token:"{{ csrf_token() }}",page:p,typeId:{{ $type->id }} },function(data){
			var str = "";
			for (var d in data) {
				getTimeDesci(data[d].times);
				if (data[d].img != null && data[d].img != "") {
					str = '<div class="col-xs-8 col-sm-4" onclick="goTo('+ data[d].id +')"><a class="title">'+
					data[d].title + '</a><p class="time">'+ getTimeDesci(data[d].times) +'</p></div><div class="col-xs-4 col-sm-2"><img class="img-responsive" src="http://news.tuike520.com/phpThumb/phpThumb.php?src=/'+
					data[d].img +'&w=120&q=30" alt="" /></div>';
					$('.contents').append($(str));
				} else {
					str = '<div class="col-xs-12 col-sm-6" onclick="goTo('+ data[d].id +')"><a class="title">'+
					data[d].title + '</a><p class="time">'+ getTimeDesci(data[d].times) +'</p></div>';
					$('.contents').append($(str));
				}
			}
		});
	}

	function goTo(i)
	{
		window.location.href = "{{ url('home/detail') }}/" + i;
	}

  function getFocus()
  {
    $.post("{{ url('home/focus') }}",{ _token:"{{ csrf_token() }}",typeId:{{ $type->id }} },function(data){
      var strLi = "",strItem = "";
      for (var d in data) {
        if (d == 0){
          strLi += '<li class="active" data-slide-to="' + d + '" data-target="#carousel-370612"></li>';
          strItem += '<div class="item active"><a href="{{ url('home/detail') }}/'+
									data[d].id +'"><img src="http://news.tuike520.com/' + data[d].img + '" /></a><div class="carousel-caption"><h4>' + data[d].title + "</h4></div></div>";
        }
        else{
          strLi += '<li data-slide-to="' + d + '" data-target="#carousel-370612"></li>';
          strItem += '<div class="item"><a href="{{ url('home/detail') }}/'+
									data[d].id +'"><img src="http://news.tuike520.com/' + data[d].img + '" /><div class="carousel-caption"><h4>' + data[d].title + "</h4></div></div>";
        }
      }
      $('.carousel-indicators').html(strLi);
      $('.carousel-inner').html(strItem);
    });
  }

	function getTimeDesci(cur)
	{
		var n = (new Date()).getTime();
		var c = (new Date(cur)).getTime();
		var tmp = n - c;
		if (tmp < 60000)
			return parseInt(tmp / 1000) + "秒前发布";
		else if (tmp < 3600000)
			return parseInt(tmp / 60000) + "分钟前发布";
		else if (tmp < 216000000)
			return parseInt(tmp / 3600000) + "小时前发布";
		else
			return "发布于：" + cur;
	}

  function initCarousel()
  {
    carouselDiv.carousel();
    carouselDiv.hammer().on('swipeleft', function(){
      $(this).carousel('next');
    });

    carouselDiv.hammer().on('swiperight', function(){
      $(this).carousel('prev');
    });
  }

</script>
@endsection
