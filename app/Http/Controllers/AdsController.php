<?php namespace App\Http\Controllers;

use App\Ad;
use Request;

class AdsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		$ads = Ad::orderBy('created_at','desc')->where('type','=',0)->get();
		$dds = Ad::orderBy('created_at','desc')->where('type','=',1)->get();
		return view('ads')->withAds($ads)->withDds($dds);
	}

	public function postCreate()
	{
		$fileName = "";
		$link = Request::input('link');
		$name = Request::input('name');
		if (Request::hasFile('img'))
		{
			$fileName = str_random(32) . '.' . Request::file('img')->getClientOriginalExtension();
			Request::file('img')->move(public_path() . '/imgs/ads/',$fileName);
		}
		$tmp = new Ad;
		$tmp->link = $link;
		if (!empty($fileName))
			$tmp->img = 'imgs/ads/' . $fileName;
		$tmp->name = $name;
		$tmp->type = Request::input('type');
		$tmp->save();
		return redirect('/ads/index');
	}

	public function getDelete($id)
	{
		Ad::find($id)->delete();
		return redirect('/ads/index');
	}

	public function getStart()
	{
		$id = Request::input('id');
		$fn = Request::input('fn');
		if ($fn == 0) {
			$tmp = Ad::find($id);
			$tmp->is_start = 0;
			$tmp->save();
		} else {
			Ad::where('is_start','=',1)->update(array('is_start' => 0));
			$tmp = Ad::find($id);
			$tmp->is_start = 1;
			$tmp->save();
		}
		return redirect('/ads/index');
	}

	public function postModify()
	{
		$fileName = "";
		$link = Request::input('link');
		$name = Request::input('name');
		$id = Request::input('id');
		if (Request::hasFile('img'))
		{
			$fileName = str_random(32) . '.' . Request::file('img')->getClientOriginalExtension();
			Request::file('img')->move(public_path() . '/imgs/ads/',$fileName);
		}
		$tmp = Ad::find($id);
		$tmp->link = $link;
		if (!empty($fileName))
			$tmp->img = 'imgs/ads/' . $fileName;
		$tmp->name = $name;
		$tmp->save();
		return redirect('/ads/index');
	}

}

?>