<?php namespace App\Http\Middleware;

use App\PageView;
use Closure;

class PageViewLog {
	
	public function handle($request, Closure $next)
    {
        $str = intval(file_get_contents(public_path() . '/logs/pv.txt'));
        $str++;
        if ($str >= 100) {
            $str = 0;
            $now = date("Y-m-d",strtotime('now'));
            $pv = PageView::where('created_at','like',"%$now%")->first();
            if ($pv == null)
                $pv = new PageView;
            $pv->count += $str;
            $pv->save();
        }
        file_put_contents(public_path() . '/logs/pv.txt',$str,LOCK_EX);
        return $next($request);
    }
}
?>