<?php namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use App\User;
use App\UserGroup;
use App\UserComment;
use App\UserCommentReply;
use App\ContentComment;
use App\ContentCommentReply;

abstract class RestController extends BaseController {

	use DispatchesCommands;


	protected function pack($info,$code = 1,$data = null)
	{
		return response()->json([
			'code' => $code,
			'info' => $info,
			'data' => $data
		]);
	}

	protected function getImg($url)
	{
		if ($url == null || strlen($url) < 0) return '';
		return "http://news.tuike520.com/phpThumb/phpThumb.php?src=/$url&w=480&q=70";
	}

	protected function saveImg($randId,$code)
	{
		if ($code == null || strlen($code) < 1) return null;
		
		$split = explode(',',$code);
		$data = $split[1];
		$head = explode(':',$split[0]);
		$main = explode(';',$head[1]);
		$type = explode('/',$main[0]);
		$tmp = base64_decode($data);
		$fileName = md5(time() . mt_rand(0,1000)) . '.' . $type[1];
		$base = 'imgs/users/' . $randId;
		if (!is_dir(public_path() . '/' . $base)) {
			mkdir(public_path() . '/' . $base);
		}
		file_put_contents(public_path() . '/' . $base . '/' . $fileName, $tmp);

		return $base . '/' . $fileName;
	}
	
	protected function addPoints($id,$points)
	{
		$user = User::where('rand_id','=',$id)->first();
		$user->points += $points;
		$group = UserGroup::where('points','<=',$user->points)->orderBy('points','desc')->first();
		$user->group_id = $group->id;
		$user->save();
	}
	
	protected function checkCount($id)
	{
		$user = User::where('rand_id','=',$id)->first();
		$points = UserGroup::where('id','=',$user->group_id)->first()->points;
		$now = date("Y-m-d",strtotime('now'));
		$cnt = UserComment::where('user_id','=',$id)->where('created_at','like','%' . $now . '%')->count();
		if ($cnt >= $points)
			return false;
		$cnt += UserCommentReply::where('user1_id','=',$id)->where('created_at','like','%' . $now . '%')->count();
		if ($cnt >= $points)
			return false;
		$cnt += ContentComment::where('user_id','=',$id)->where('created_at','like','%' . $now . '%')->count();
		if ($cnt >= $points)
			return false;
		$cnt += ContentCommentReply::where('user1_id','=',$id)->where('created_at','like','%' . $now . '%')->count();
		if ($cnt >= $points)
			return false;
		return true;
	}
	
	protected function isLock($id)
	{
		$user = User::where('rand_id','=',$id)->first();
		return $user->is_lock == 1;
	}
}

?>