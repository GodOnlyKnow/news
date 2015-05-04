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
  });

  function is_weixin(){
    var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
      return true;
    } else {
      return false;
    }
  }
</script>
@endsection
