$(function(){
	var pre1 = 'http://112.74.126.199:8023',
		pre2 = 'http://news.tuike520.com';
	$('img').each(function(){
		//console.log($(this).attr('src'));
		var src = $(this).attr('src').replace(pre1,'');
		src = src.replace(pre2,'');
		$(this).attr('src', '/phpThumb/phpThumb.php?src=' + src + '&w=800&q=80');
		
	});
});