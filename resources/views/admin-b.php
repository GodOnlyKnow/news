 @extends('app')

@section('styles')
<link rel="stylesheet" href="{{ asset('froala/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('froala/css/froala_editor.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jasny/jasny-bootstrap.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2 types">
			<div class="list-group">
				@foreach ($types as $type)
			  	<a class="list-group-item text-center" data-id="{{ $type->id }}" onclick="getNews(this)">
						<span>{{ $type->name }}</span>
					</a>
				@endforeach
			</div>
		</div>
		<div class="col-md-2 news"></div>
		<div class="col-md-1 opts"></div>
		<div class="col-md-7 content">
			<form class="form-horizontal" id="form-modify" action="{{ url('admin/modify') }}" enctype="multipart/form-data" target="hideFrame" method="post">

			</form>
		</div>
	</div>
	<div class="modal fade" id="alert">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">提示</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<iframe src="" id="hideFrame" name="hideFrame" style="display:none;"></iframe>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('jasny/jasny-bootstrap.min.js') }}"></script>
  <script src="{{ asset('froala/js/froala_editor.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/tables.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/lists.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/colors.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/media_manager.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/font_family.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/font_size.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/block_styles.min.js') }}"></script>
  <script src="{{ asset('froala/js/plugins/video.min.js') }}"></script>
  <script src="{{ asset('froala/js/langs/zh_cn.js') }}"></script>
<script charset="utf-8">

		$(function(){
			$('form').submit(function(){
				$('form').append($("<input type='hidden' name='body' value='" + $('#editor').editable("getHTML",true,true) + "'>"));
				return true;
			});
		});

	function callback(data)
	{
		
						$('.modal-body').html($('<p>' + data + '</p>'));
						$('#alert').modal();
						getNews($('.types > .list-group > .active'));
	}

	function getNews(t)
	{
		$(t).append($('<span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>'));
    	$(t).parent().children().each(function(){
	      $(this).removeClass('active');
	      $(this).children('span.pull-right').remove();
    	});
	    $(t).addClass('active');
	    var str = '<div class="list-group"><a onclick="addContent()" class="list-group-item list-group-item-info"><i class="glyphicon glyphicon-plus"></i>&nbsp;新增文章</a>';
	    $.post("{{ url('admin/content') }}",{ typeId:$(t).data('id'),_token:"{{ csrf_token() }}" },function(dat){
	    	str += '<a class="list-group-item list-group-item-danger">焦点新闻</a>';
	    	// Focus Content
				
				
				for (var d = 0;d < dat.length;d++) {
						str += '<a class="list-group-item sec-list-item"><div class="row"><div class="col-md-8" data-id="' + dat[d].id
								+ '" onclick="getContent(this)">' + dat[d].title
								+ '</div><div class="col-md-2" onclick="delContent(this)"><i class="glyphicon glyphicon-remove-sign"></i></div><div class="col-md-2 acticess" onclick="editContent(this)"><i class="glyphicon glyphicon-bookmark"></i></div></div></a>';
				}
			$.post("{{ url('admin/news') }}",{ typeId:$(t).data('id'),_token:"{{ csrf_token() }}" },function(data){
				
				var d = 0;
				
				// Content
				str += '<a class="list-group-item list-group-item-danger">普通新闻</a>';
				for (;d < data.length;d++) {
					str += '<a class="list-group-item sec-list-item"><div class="row"><div class="col-md-8" data-id="' + data[d].id
							+ '" onclick="getContent(this)">' + data[d].title
							+ '</div><div class="col-md-2" onclick="delContent(this)"><i class="glyphicon glyphicon-remove-sign"></i></div><div class="col-md-2" onclick="editContent(this)"><i class="glyphicon glyphicon-bookmark"></i></div></div></a>';
				}
				str += '</div>';
				$('.news').html(str);
				$(t).children().last().remove();
	      		$(t).append($('<span class="pull-right"><i class="glyphicon glyphicon-chevron-right"></i></span>'));
			});
	 	});
	}

	function getContent(t)
	{
		$(t).append($('<span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>'));
    $(t).parent().children().each(function(){
      $(this).removeClass('active');
    });
    $(t).addClass('active');
		$.post("{{ url('admin/detail') }}",{ id:$(t).data('id'),_token:"{{ csrf_token() }}" },function(data){
			var str = '<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">' +
				'<input type="hidden" id="id" name="id" value="' +
			  data.id +'" ><div class="form-group"><label class="control-label col-md-2">标题</label><div class="col-md-8"><input type="text" id="title" name="title" class="form-control" value="'+
				data.title +'"></div></div><div class="form-group"><label class="control-label col-md-2">焦点图片</label><div class="col-md-4"><div class="fileinput fileinput-new" data-provides="fileinput"><div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"><img src="{{ asset('/') }}'+
				data.img +'" alt="" /></div><div><span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span><a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a></div></div></div></div></div><div class="form-group"><label class="control-label col-md-2">内容</label><div class="col-md-8"><div id="editor">'+
				data.body +'</div></div></div><div class="form-group"><div class="col-md-offset-2 col-md-8"><button type="submit" class="btn btn-primary">确定</button></div></div>';
			$('#form-modify').html(str);
			$(t).children().last().remove();
			initEditor(data.id);
		});
	}

	function delContent(t)
	{
		$.post("{{ url('admin/delcontent') }}",{ _token:"{{ csrf_token() }}",id:$(t).prev().data('id') },function(data){
			getNews($('.types > .list-group > .active'));
		});
	}

	function editContent(t)
	{
		$.post("{{ url('admin/editcontent') }}",{ _token:"{{ csrf_token() }}",id:$(t).prev().prev().data('id') },function(data){
			getNews($('.types > .list-group > .active'));
		});
	}

	function addContent()
	{
		var str = '<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">' +
			'<input type="hidden" id="type_id" name="type_id" value="'+
		$('.types > .list-group > .active').data('id') +'"><div class="form-group"><label class="control-label col-md-2">标题</label><div class="col-md-8"><input type="text" id="title" name="title" class="form-control" value="'+
		  '"></div></div><div class="form-group"><label class="control-label col-md-2">焦点图片</label><div class="col-md-4"><div class="fileinput fileinput-new" data-provides="fileinput"><div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div><div><span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span><a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a></div></div></div></div><div class="form-group"><label class="control-label col-md-2">内容</label><div class="col-md-8"><div id="editor">'+
		  '</div></div></div><div class="form-group"><div class="col-md-offset-2 col-md-8"><button type="submit" class="btn btn-primary">确定</button></div></div>';
		$('#form-modify').html(str);
		initEditor(0);
	}

	function initEditor(i)
	{
		$('.edit-img').editable({
			inlineMode: false, alwaysBlank: true,
			maxCharacters:1,
			language: "zh_cn",
			buttons:['insertImage'],
			allowedImageTypes: ["jpeg", "jpg", "png","gif"],
			imageUploadURL: "{{ url('admin/modimg') }}",//上传到本地服务器
			imageUploadParams: { _token:"{{ csrf_token() }}",id:i },
			imageDeleteURL: "{{ url('admin/deleteimg') }}",//删除图片
			imagesLoadURL: 'lib/load_images.php'//管理图片
		}).on('editable.afterRemoveImage',function(e,editor,oImg){
			editor.options.imageDeleteParams = {
				src: oImg.attr('src'),
				_token: "{{ csrf_token() }}"
			};
			editor.deleteImage(oImg);
		});
		$('#editor').editable({
				inlineMode: false, alwaysBlank: true,
				language: "zh_cn",
				allowedImageTypes: ["jpeg", "jpg", "png","gif"],
        videoAllowedAttrs:  [
                'quality',
                'src',
                'width',
                'height',
                'align',
                'allowScriptAccess',
                'mode',
                'frameborder',
                'allowfullscreen',
                'webkitallowfullscreen',
                'mozallowfullscreen',
                'href',
                'target',
                'id',
                'controls',
                'value',
                'name'
        ],
        videoAllowedTags:  ['iframe', 'object', 'param', 'video', 'source', 'embed'],
				imageUploadURL: "{{ url('admin/upload') }}",//上传到本地服务器
				imageUploadParams: { _token:"{{ csrf_token() }}" },
				imageDeleteURL: "{{ url('admin/deleteimg') }}",//删除图片
				imagesLoadURL: 'lib/load_images.php'//管理图片
			}).on('editable.afterRemoveImage',function(e,editor,oImg){
				editor.options.imageDeleteParams = {
					src: oImg.attr('src'),
					_token: "{{ csrf_token() }}"
				};
				editor.deleteImage(oImg);
			});
	}

</script>
@endsection
