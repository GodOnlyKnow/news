<?php namespace App\Http\Controllers;

use App\ContentType;
use App\Content;
use Request;

class AdminController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('admin')->withTypes(ContentType::all());
	}

	public function anyNews()
	{
		$typeId = 1;
		if (Request::has('typeId'))
			$typeId = Request::input('typeId');
		$news = Content::where('type_id','=',$typeId)->where('is_focus','=',0)->orderBy('created_at','desc')->select(array('id','title','created_at','is_lock','visited','shared','parised'))->paginate(10);
		return response()->json($news);
	}

	public function anyContent()
	{
		$typeId = 1;
		if (Request::has('typeId'))
			$typeId = Request::input('typeId');
		$news = Content::where('type_id','=',$typeId)->where('is_focus','=',1)->orderBy('created_at','desc')->select(array('id','title','created_at','is_lock','visited','shared','parised'))->get();
		
		return response()->json($news);
	}

	public function anyChangestatus()
	{
		$id = Request::input('id');
		$tmp = Content::where('id','=',$id)->first();
		$tmp->is_lock = $tmp->is_lock == 0 ? 1 : 0;
		$tmp->save();
		return $tmp->is_lock == 0 ? '显示' : '不显示';
	}

	public function anyDelcontent()
	{
		$id = Request::input('id');
		Content::find($id)->delete();
		return 'ok';
	}

	public function anyEditcontent()
	{
		$id = Request::input('id');
		$tmp = Content::find($id);
		$tmp->is_focus = !$tmp->is_focus;
		$tmp->save();
		return 'ok';
	}



	public function anyDetail()
	{
		$id = Request::input('id');
		return response()->json(Content::find($id));
	}

	public function anyUpload()
	{
		if (Request::hasFile('file'))
		{
			$fileName = str_random(32) . '.' . Request::file('file')->getClientOriginalExtension();
			Request::file('file')->move(public_path() . '/imgs/',$fileName);
			$fileName = asset('imgs') . '/' . $fileName;
			return response()->json(['link' => $fileName]);
		}
		return response()->json(['error' => '上传失败，请重试']);
	}

	public function anyModimg()
	{
		if (Request::hasFile('file'))
		{
			$id = Request::input('id');
			$fileName = str_random(32) . '.' . Request::file('file')->getClientOriginalExtension();
			Request::file('file')->move(public_path() . '/imgs/',$fileName);
			$tmp = Content::find($id);
			$tmp->img = 'imgs/' . $fileName;
			$tmp->save();
			$fileName = asset('imgs') . '/' . $fileName;
			return response()->json(['link' => $fileName]);
		}
		return response()->json(['error' => '上传失败，请重试']);
	}

	public function anyModify()
	{
		$title = Request::input('title');
		$body = Request::input('body');
		$fileName = "";
		if (Request::hasFile('img'))
		{
			$fileName = str_random(32) . '.' . Request::file('img')->getClientOriginalExtension();
			Request::file('img')->move(public_path() . '/imgs/',$fileName);
		}
		if (Request::has('id')) {
			$id = Request::input('id');
			$tmp = Content::find($id);
			$tmp->title = $title;
			$tmp->body = $body;
			if (!empty($fileName))
				$tmp->img = 'imgs/' . $fileName;
			$tmp->save();
			return "<script>parent.callback('修改成功')</script>";
		} else {
			$typeId = Request::input('type_id');
			$tmp = new Content;
			$tmp->title = $title;
			$tmp->body = $body;
			$tmp->type_id = $typeId;
			if (!empty($fileName))
				$tmp->img = 'imgs/' . $fileName;
			$tmp->save();
			return "<script>parent.callback('添加成功')</script>";
		}
	}

	public function anyDeleteimg()
	{
		$src = Request::input('src');
		$exp = explode('/',$src);
		$fileName = end($exp);
		unlink(public_path() . '/imgs/' . $fileName);
	}

}
