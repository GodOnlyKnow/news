<?php namespace App\Http\Controllers;

use App\Content;
use App\ContentType;
use App\Ad;
use Request;

class HomeController extends Controller {

	private $pageSize = 10;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getIndex($type = 1)
	{
		$t = ContentType::find($type);
		$cnt = Content::where('type_id','=',$type)->where('is_lock','=','0')->where('is_focus','=',0)->count();
		$cnt = intval($cnt / 10) + 1;
		$contents = Content::where('type_id','=',$type)->where('is_lock','=','0')->where('is_focus','=',1)->get();
		$outContents = array();
		foreach ($contents as $k) {
			$outContents[] = array(
				'id' => $k->id,
				'title' => $k->title,
				'img' => $k->img
			);
		}
		$contents = Content::where('type_id','=',$type)->where('is_lock','=','0')->where('is_focus','=',0)->orderBy('created_at','desc')->paginate($this->pageSize);
		date_default_timezone_set("PRC");
		$out = array();
		$now = time();
		$ads = Ad::where('type','=',$type + 1)->orderBy('created_at','desc')->get();
		foreach ($contents as $k) {
			$tm = strtotime(strval($k->created_at));
			$tmp = $now - $tm;
			$ts = "";
			if ($tmp < 60)
				$ts = intval($tmp) . "秒前发布";
			else if ($tmp < 3600)
				$ts = intval($tmp / 60) . "分钟前发布";
			else if ($tmp < 216000)
				$ts = intval($tmp / 3600) . "小时前发布";
			else
				$ts = "发布于：" . strval($k->created_at);
			$out[] = array(
				'id' => $k->id,
				'title' => $k->title,
				'img' => $k->img,
				'times' => $ts
			);
		}
		$this->pv();
		return view('home')->withType($t)->withFcnt(count($outContents))->withCnt($cnt)->withFocus($outContents)->withContents($out)->withAds($ads);
	}

	public function getNews($type = 1)
	{
		$t = ContentType::find($type);
		$cnt = Content::where('type_id','=',$type)->where('is_lock','=','0')->where('is_focus','=',0)->count();
		$cnt = intval($cnt / 10) + 1;
		return view('home')->withType($t)->withCnt($cnt);
	}

	public function getDetail($id)
	{
		$d = Content::lockForUpdate()->find($id);
		$d->visited += 1;
		$d->save();
		$this->pv();
		if (Request::has('guest'))
			return view('detail_guest')->withContent($d);
		return view('detail')->withContent($d);
	}


	/**
	 *
	 *  REST FUL API
	 *  Must request csrf token but GET
	 */
	public function anyFocus()
	{
		$typeId = 1;
		if (Request::has('typeId')) {
			$typeId = Request::input('typeId');
		}
		$contents = Content::where('type_id','=',$typeId)->where('is_lock','=','0')->where('is_focus','=',1)->get();
		$out = array();
		foreach ($contents as $k) {
			$out[] = array(
				'id' => $k->id,
				'title' => $k->title,
				'img' => $k->img
			);
		}
		return response()->json($out);
	}

	public function anyContent()
	{
		$typeId = 1;
		if (Request::has('typeId')) {
			$typeId = Request::input('typeId');
		}
		$contents = Content::where('type_id','=',$typeId)->where('is_lock','=','0')->where('is_focus','=',0)->orderBy('created_at','desc')->paginate($this->pageSize);
		$out = array();
		foreach ($contents as $k) {
			$out[] = array(
				'id' => $k->id,
				'title' => $k->title,
				'img' => $k->img,
				'times' => strval($k->created_at)
			);
		}
		return response()->json($out);
	}

}
