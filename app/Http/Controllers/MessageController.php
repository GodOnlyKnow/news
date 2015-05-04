<?php namespace App\Http\Controllers;

use App\Message;
use Request;

require __DIR__ . '/Gt/Push.class.php';

use IOS,Android;

class MessageController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		return view('message')->withMessages(Message::orderBy('created_at','desc')->paginate(15));
	}

	public function getStop()
	{
		$id = Request::input('id');
		$fn = Request::input('fn');
		$tmp = Message::find($id);
		if ($fn == 0) {
			$tmp->ios_time = 0;
		} else if ($fn == 1) {
			$tmp->android_time = 0;
		} else {
			$tmp->ios_time = 0;
			$tmp->android_time = 0;
		}
		$tmp->save();
		return redirect('/message/index');
	}

	public function getDelete($id)
	{
		Message::find($id)->delete();
		return redirect('/message/index');
	}

	public function postCreate()
	{
		$name = Request::input('name');
		$body = Request::input('body');
		$bIos = Request::has('ios');
		$bAndroid = Request::has('android');
		$time = 1000 * 12 * 3600;
		$oIos = new IOS();
		$oAndroid = new Android();
		$tmp = new Message;
		if ($bIos) {
			$r = $oIos->Push($body,$name,"推送消息",$time);
			$tmp->ios_task = $r['contentId'];
			$tmp->ios_time = $time;
		}
		if ($bAndroid) {
			$r = $oAndroid->Push($body,$name,"推送消息",$time);
			$tmp->android_task = $r['contentId'];
			$tmp->android_time = $time;
		}
		$tmp->name = $name;
		$tmp->body = $body;
		$tmp->save();
		return redirect('/message/index');
	}
}

?>