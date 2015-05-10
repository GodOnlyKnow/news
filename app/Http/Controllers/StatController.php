<?php namespace App\Http\Controllers;

use App\PageView;
use App\User;

class StatController extends Controller {
	
	public function getIndex($days = 3)
	{
		$pv = array();
		$av = array();
		$ts = array();
		for ($i = $days - 1;$i >= 0;$i--) {
			$now = date("Y-m-d",strtotime("-$i days"));
			$ts[] = $now;
			$tmp = PageView::where('created_at','like',"%$now%")->first();
			if ($tmp == null) {
				$pv[] = 0;
				$av[] = 0;
			}
			else {
				$pv[] = $tmp->count;
				$av[] = $tmp->cnts;
			}
		}
		return view('stats')->withPvs($pv)->withAvs($av)->withTs($ts);
	}
}

?>