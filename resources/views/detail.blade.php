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
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/zipimg.js') }}"></script>
<script type="text/javascript">
  $(function(){
    $.get("{{ url('/api/ads') }}",function(data){
      var str = "<div class='col-md-12 col-xs-12'><a href='" +
              data.link + "'><img class='img-responsive' src='/phpThumb/phpThumb.php?src=/" +
              data.img + "&w=800&q=80' ></a></div>";
      $('.ads').html(str);
    })
  });
</script>
@endsection
