<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\PageView;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;
	
	protected function pv()
	{
		$str = intval(file_get_contents(public_path() . '/logs/pv.txt'));
        $str++;
        if ($str >= 300) {
            $now = date("Y-m-d",strtotime('now'));
            $pv = PageView::where('created_at','like',"%$now%")->first();
            if ($pv == null)
                $pv = new PageView;
            $pv->count += $str;
            $pv->save();
            $str = 0;
        }
        file_put_contents(public_path() . '/logs/pv.txt',$str,LOCK_EX);
	}

}
