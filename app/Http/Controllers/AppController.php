<?php namespace App\Http\Controllers;

use Request;
use App\Downlog;

class AppController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		$file = file_get_contents(__DIR__ . '/version');
		$v = explode('|',$file);
		$file = file_get_contents(__DIR__ . '/ios');
		$vs = explode('|',$file);
		$out = array(
			'version' => $v[0],
			'down' => $v[1],
			'android_downs' => $v[2],
			'ios_version' => $vs[0],
			'ios_down' => $vs[1],
			'ios_downs' => $vs[2]
		);
		$chart = array();
		$logs = Downlog::where('created_date','>',date('Y-m-d',strtotime("-5 days")))->orderBy('created_date')->get();

		return view('admin_app')->withOut($out)->withChart($logs);
	}

	public function postModify()
	{
		$version = Request::input('version');
		$down = Request::input('down');
		$ios = Request::input('ios_version');
		$link = Request::input('ios_down');
		$android_downs = Request::input('android_downs');
		$ios_downs = Request::input('ios_downs');
		file_put_contents(__DIR__ . '/version', $version . '|' . $down . '|' . $android_downs);
		file_put_contents(__DIR__ . '/ios', $ios . '|' . $link . '|' . $ios_downs);
		return redirect('/app/index');
	}
}

?>