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
		<div class="col-md-offset-1 col-md-8 news"></div>
		<div class="content">
			<form class="form-horizontal" id="form-modify" action="{{ url('admin/modify') }}" enctype="multipart/form-data" target="hideFrame" method="post">

			</form>
			<div class="content-close">
				<a href="javascript:$('.content').css('display','none');">
					<i class="glyphicon glyphicon-remove"></i>
				</a>
			</div>
			<div id="content-color">
				<i class="glyphicon glyphicon-th-large"></i>
			</div>
		</div>
		<!-- <div class="col-md-1 opts"></div>
		<div class="col-md-7 content">
			<form class="form-horizontal" id="form-modify" action="{{ url('admin/modify') }}" enctype="multipart/form-data" target="hideFrame" method="post">

			</form>
		</div> -->
		<div class="detail">
			<div class="content-back">
				<button onclick="back()" class="btn btn-primary">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</button>
			</div>
			<div class="content-close">
				<a href="javascript:$('.detail').css('display','none');">
					<i class="glyphicon glyphicon-remove"></i>
				</a>
			</div>
			<br>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>用户名</th>
						<th>内容</th>
						<th>发布时间</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody class="detail-body">
					
				</tbody>
			</table>
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
		var type = 1,page = 1,pageCount = 1,dPage = 1,dCount = 1,cId = 1;
		var dBody = $('.detail-body'),
			det = $('.detail');
		$(function(){
			$('.content,.detail').css({
				'width':$(window).width() * 0.8 + "px",
				'height':$(window).height() * 0.8 + "px",
				'right':($(window).width() * 0.1) + 'px'
			});
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
		if (parseInt($(t).data('id')) !== type) {
			type = parseInt($(t).data('id'));
			page = 1;
		}
		$(t).append($('<span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>'));
    	$(t).parent().children().each(function(){
	      $(this).removeClass('active');
	      $(this).children('span.pull-right').remove();
    	});
	    $(t).addClass('active');
	    var str = '<div class="row"><a onclick="addContent()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;新增文章</a></div><hr>';
	    $.post("{{ url('admin/content') }}",{ typeId:$(t).data('id'),_token:"{{ csrf_token() }}" },function(dat){
	    	str += '<div class="row"><div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>新闻标题</th><th>发布时间</th><th>状态</th><th>访问量</th><th>分享数</th><th>点赞数</th><th>操作</th></tr></thead><tbody>';
	    	// Focus Content
				for (var d = 0;d < dat.length;d++) {
					var loc = dat[d].is_lock == 0 ? '显示' : '不显示';
						str += '<tr><td>' +
										dat[d].title + '</td><td>' +
										dat[d].created_at + '</td><td><a class="btn btn-primary" onclick="changeStatus(this,'+dat[d].id+')">' +
										loc + '</a></td><td>' +
										dat[d].visited + '</td><td>' +
										dat[d].shared + '</td><td>' +
										dat[d].parised + '</td><td>' +
										'<button class="btn btn-success" onclick="getContent('+dat[d].id+')"><i class="glyphicon glyphicon-edit"></i></button><button onclick="delContent('+ dat[d].id +')" class="btn btn-small btn-danger"><i class="glyphicon glyphicon-remove-sign"></i></button><button onclick="editContent('+dat[d].id+')" class="btn btn-primary"><i class="glyphicon glyphicon-bookmark"></i></button><button onclick="detail('+dat[d].id+')" class="btn btn-info"><i class="glyphicon glyphicon-th-list"></i></button>';
				}
			$.post("{{ url('admin/news') }}",{ typeId:$(t).data('id'),"page":page,_token:"{{ csrf_token() }}" },function(ds){
				var data = ds.data;
				console.log(ds);
				pageCount = ds.last_page;
				for (var d = 0;d < data.length;d++) {
					var loc = data[d].is_lock == 0 ? '显示' : '不显示';
						str += '<tr><td>' +
										data[d].title + '</td><td>' +
										data[d].created_at + '</td><td><a class="btn btn-primary" onclick="changeStatus(this,'+data[d].id+')">' +
										loc + '</a></td><td>' +
										data[d].visited + '</td><td>' +
										data[d].shared + '</td><td>' +
										data[d].parised + '</td><td>' +
										'<button class="btn btn-success" onclick="getContent('+data[d].id+')"><i class="glyphicon glyphicon-edit"></i></button><button onclick="delContent(' +data[d].id +')" class="btn btn-small btn-danger"><i class="glyphicon glyphicon-remove-sign"></i></button><button onclick="editContent('+data[d].id+')" class="btn btn-default"><i class="glyphicon glyphicon-bookmark"></i></button><button onclick="detail('+data[d].id+')" class="btn btn-info"><i class="glyphicon glyphicon-th-list"></i></button>';
				}
				str += '</tbody></table></div></div><div class="row"><div><ul class="pager"><li><a href="javascript:prePage();">上一页</a></li><li>&nbsp;第' + ds.current_page +'页，共'+pageCount+'页&nbsp;</li><li><a href="javascript:nexPage();">下一页</a></li></ul></div></div>';
				$('.news').html(str);
				$(t).children().last().remove();
	      		$(t).append($('<span class="pull-right"><i class="glyphicon glyphicon-chevron-right"></i></span>'));
			});
	 	});
	}

	function getContent(t)
	{
		$.post("{{ url('admin/detail') }}",{ id:t,_token:"{{ csrf_token() }}" },function(data){
			var str = '<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">' +
				'<input type="hidden" id="id" name="id" value="' +
			  data.id +'" ><div class="form-group"><label class="control-label col-md-2">标题</label><div class="col-md-8"><input type="text" id="title" name="title" class="form-control" value="'+
				data.title +'"></div></div><div class="form-group"><label class="control-label col-md-2">焦点图片</label><div class="col-md-4"><div class="fileinput fileinput-new" data-provides="fileinput"><div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"><img src="{{ asset('/') }}'+
				data.img +'" alt="" /></div><div><span class="btn btn-default btn-file"><span class="fileinput-new">选择图像</span><span class="fileinput-exists">更换</span><input type="file" id="img" multiple="multiple" name="img"></span><a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除</a></div></div></div></div></div><div class="form-group"><label class="control-label col-md-2">内容</label><div class="col-md-8"><div id="editor">'+
				data.body +'</div></div></div><div class="form-group"><div class="col-md-offset-2 col-md-8"><button type="submit" class="btn btn-primary">确定</button></div></div>';
			$('#form-modify').html(str);
			initEditor(data.id);
		});
	}

	function changeStatus(t,id)
	{
		$.get("{{ url('admin/changestatus') }}",{ "id":id },function(data){
			$(t).html(data);
		});
	}
	
	function back()
	{
		detail(cId);
	}
	
	function delContent(id)
	{
		$.post("{{ url('admin/delcontent') }}",{ _token:"{{ csrf_token() }}","id":id },function(data){
			getNews($('.types > .list-group > .active'));
		});
	}
	
	function detail(i)
	{
		cId = i;
		dBody.html($('<p><span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>&nbsp;&nbsp;加载中...</p>'));
		det.css('display','block');
		$('.content-back').css('display','none');
		$.post("{{ url('/api/comment/get') }}",{ id:i,pageSize:10,page:dPage },function(res){
			var data = res.data;
			for (var d in data) {
				var str = '<tr><td>' + 
						data[d].userName + '</td><td>' +
						data[d].body + '</td><td>' +
						getLocalTime(data[d].createdAt) + '</td><td><button class="btn btn-danger" onclick="del(this)" data-href="/api/comment/delete?id=' +
						data[d].id + '&type=0"><i class="glyphicon glyphicon-remove-sign"></i></button><button class="btn btn-success" onclick="moreDetail(' +
						data[d].id +')"><i class="glyphicon glyphicon-th-list"></i></button>'
				dBody.append($(str));
			}
			dBody.children('p').remove();
		});
	}
	
	function getLocalTime(nS) {     
       return (new Date(parseInt(nS) * 1000)).Format("yyyy-MM-dd hh:mm:ss");      
  	}
	  
	function moreDetail(i)
	{
		dBody.html($('<p><span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>&nbsp;&nbsp;加载中...</p>'));
		$('.content-back').css('display','block');
		$.post("{{ url('/api/comment/detail') }}",{ id:i },function(res){
			var data = res.data;
			for (var d in data) {
				var str = '<tr><td>' + 
						data[d].userFrom + '@' + data[d].userTo + '</td><td>' +
						data[d].body + '</td><td>' +
						getLocalTime(data[d].createdAt) + '</td><td><button class="btn btn-danger" onclick="del(this)" data-href="/api/comment/delete?id=' +
						data[d].id + '&type=1"><i class="glyphicon glyphicon-remove-sign"></i></button>';
				dBody.append($(str));
			}
			dBody.children('p').remove();
		});
	}
	
	function del(t)
	{
		$.get($(t).data('href'));
		$(t).parent().parent().remove();
	}
	
	function prePage()
	{
		page--;
		if (page < 1) return;
		getNews($('.types > .list-group > .active'));
	}

	function nexPage()
	{
		page++;
		if (page > pageCount) return;
		getNews($('.types > .list-group > .active'));	
	}

	function editContent(id)
	{
		$.post("{{ url('admin/editcontent') }}",{ _token:"{{ csrf_token() }}","id":id },function(data){
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
		$('.content').css('display','block');
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
