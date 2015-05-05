@extends('app')

@section('content')
<div class="container">
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>用户名</th>
					<th>内容</th>
					<th>发布时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody class="table-body">
				
			</tbody>
		</table>
		<div class="pagbtn">
			
		</div>
	</div>
	<div class="detail">
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
@endsection

@section('scripts')
<script>
	var page = 1,pageCount = 1;
	var dBody = $('.table-body'),
		pBtn = $('.pagbtn'),
		tBody = $('.detail-body');
	$(function(){
		$('.detail').css({
			'width':$(window).width() * 0.8 + "px",
			'height':$(window).height() * 0.8 + "px",
			'right':($(window).width() * 0.1) + 'px'
		});
		getComment();
	});
	
	function getComment()
	{
		dBody.html($('<p><span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>&nbsp;&nbsp;加载中...</p>'));
		$.post("/api/usercomment/get",{ "page":page,pageSize:10 },function(res){
			var data = res.data.result;
			pageCount = res.data.last;
			for (var d in data) {
				var str = '<tr><td>' + 
						data[d].userName + '</td><td>' +
						data[d].body + '</td><td>' +
						getLocalTime(data[d].createdAt) + '</td><td><button class="btn btn-danger" onclick="del(this)" data-href="/api/usercomment/delete?id=' +
						data[d].id + '&type=0"><i class="glyphicon glyphicon-remove-sign"></i></button><button class="btn btn-success" onclick="detail(' +
						data[d].id +')"><i class="glyphicon glyphicon-th-list"></i></button>'
				dBody.append($(str));
			}
			dBody.children('p').remove();
			pBtn.html('<ul class="pager"><li><a href="javascript:prePage();">上一页</a></li><li>&nbsp;第' + page +'页，共'+ pageCount +'页&nbsp;</li><li><a href="javascript:nexPage();">下一页</a></li></ul>');
		});
	}
	
	function nexPage()
	{
		if (page == pageCount) return;
		page++;
		getComment();
	}
	
	function prePage()
	{
		if (page == 1) return;
		page--;
		getComment();
	}
	
	function getLocalTime(nS) 
	{     
       return (new Date(parseInt(nS) * 1000)).Format("yyyy-MM-dd hh:mm:ss");      
  	}
	  
	function del(t)
	{
		$.post($(t).data('href'));
		$(t).parent().parent().remove();
	}
	
	function detail(i)
	{
		$('.detail').css('display','block');
		tBody.html($('<p><span class="re-icon"><i class="glyphicon glyphicon-refresh"></i></span>&nbsp;&nbsp;加载中...</p>'));
		$.post("{{ url('/api/usercomment/detail') }}",{ id:i },function(res){
			var data = res.data;
			for (var d in data) {
				var str = '<tr><td>' + 
						data[d].userFrom + '@' + data[d].userTo + '</td><td>' +
						data[d].body + '</td><td>' +
						getLocalTime(data[d].createdAt) + '</td><td><button class="btn btn-danger" onclick="del(this)" data-href="/api/usercomment/delete?id=' +
						data[d].id + '&type=1"><i class="glyphicon glyphicon-remove-sign"></i></button>';
				tBody.append($(str));
			}
			tBody.children('p').remove();
		});
	}
</script>
@endsection