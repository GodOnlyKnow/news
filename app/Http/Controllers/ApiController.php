<?php namespace App\Http\Controllers;

use App\Ad;
use App\User;
use App\UserGroup;
use App\Downlog;

use App\ContentComment;
use App\ContentCommentReply;
use App\Feedback;
use App\UserComment;
use App\UserCommentReply;

use Request,DB,Hash;

class ApiController extends Controller {
	
	public function anyUserreply()
	{
		$randId = Request::input('randId');
		$page = Request::input('page');
		$pageSize = Request::input('pageSize');
		$count = ContentCommentReply::where('user2_id','=',$randId)->count() + UserCommentReply::where('user2_id','=',$randId)->count();
		$res = DB::select("select * from ((SELECT id,user1_id,user2_id,body,parent_id,created_at,updated_at,1 as type FROM news.user_comment_replys where user2_id = ?) union (select id,user1_id,user2_id,body,comment_id as parent_id,created_at,updated_at,0 as type from content_comment_replys where user2_id = ?)) a order by a.created_at desc limit ?,?",[$randId,$randId,($page - 1) * $pageSize,$pageSize]);
		$cnt = intval($count / $pageSize);
		if ($count % $pageSize != 0)
			$cnt++;
		$out = array();
		foreach ($res as $r) {
			$user1 = User::where('rand_id','=',$r->user1_id)->first();
			if ($r->type == 0)
				$r->parent_id = ContentComment::where('id','=',$r->parent_id)->first()->content_id;
			$out[] = [
				'userFromImg' => $user1->img,
				'userFromName' => $user1->username,
				'body' => $r->body,
				'createdAt' => strtotime($r->created_at),
				'parentId' => $r->parent_id,
				'type' => $r->type
			];
		}
		return $this->pack("获取成功",1,[
			'result' => $out,
			'last' => $cnt
		]);
	}
	
	public function anyProfile()
	{
		$randId = Request::input('randId');
		$user = User::where('rand_id','=',$randId)->first();
		return $this->pack("获取成功",1,[
			'username' => $user->username,
			'phone' => $user->phone,
			'img' => $user->img,
			'desc' => $user->desci,
			'randId' => $user->rand_id,
			'groupName' => UserGroup::where('id','=',$user->group_id)->select('name')->first()->name
		]);
	}
	
	public function anyModify()
	{
		$user = User::where('rand_id','=',Request::input('randId'))->first();
		if (Request::has('username'))
			$user->username = Request::input('username');
		if (Request::has('desc'))
			$user->desci = Request::input('desc');
		if (Request::has('code'))
			$user->img = $this->saveImg(Request::input('randId'),Request::input('code'));
		$user->save();
		
		return $this->pack("更新成功",1,[
			'username' => $user->username,
			'phone' => $user->phone,
			'img' => $user->img,
			'desc' => $user->desci,
			'randId' => $user->rand_id,
			'groupName' => UserGroup::where('id','=',$user->group_id)->select('name')->first()->name
		]);
	}
	
	// 注册
	public function anyRegist()
	{
		$username = Request::input('username');
		$phone = Request::input('phone');
		$password = Request::input('password');
		$desci = Request::input('desc');
		if (User::where('username','=',$username)->count() > 0)
			return $this->pack("用户名已存在",0);
		if (User::where('phone','=',$phone)->count() > 0)
			return $this->pack("手机号已注册",0);
		$file = 'default.png';
		if (Request::has('img')) {
			$code = Request::input('img');
			$sp = explode(',', $code);
			$data = $sp[1];
			$h = explode(':', $sp[0]);
			$m = explode(';',$h[1]);
			$type = explode('/',$m[0]);
			$tmp = base64_decode($data);
			$file = md5(time() . mt_rand(0,1000)) . '.' . $type[1];
			file_put_contents(public_path('/imgs/users') . '/' . $file, $data);
		}
		$user = new User;
		$user->username = $username;
		$user->password = Hash::make($password);
		$user->phone = $phone;
		$user->desci = $desci;
		$user->img = 'imgs/users/' . $file;
		$user->group_id = 1;
		$user->rand_id = $this->getRandId();
		$user->save();

		return $this->pack("注册成功",1,[
			'username' => $user->username,
			'phone' => $user->phone,
			'img' => $user->img,
			'desc' => $user->desci,
			'randId' => $user->rand_id,
			'groupName' => UserGroup::where('id','=',$user->group_id)->select('name')->first()->name
		]);
	}

	// 登陆
	public function anyLogin()
	{
		$phone = Request::input('phone');
		$password = Request::input('password');
		if (User::where('phone','=',$phone)->count() < 1)
			return $this->pack("手机号未注册",0);
		$user = User::where('phone','=',$phone)->first();
		if (!Hash::check($password,$user->password))
			return $this->pack("密码错误",0);
		return $this->pack("登陆成功",1,[
			'username' => $user->username,
			'phone' => $user->phone,
			'img' => $user->img,
			'desc' => $user->desci,
			'randId' => $user->rand_id,
			'groupName' => UserGroup::where('id','=',$user->group_id)->select('name')->first()->name
		]);
	}

	// 微信注册
	public function anyWxreg()
	{
		$username = Request::input('username');
		$img = Request::input('img');
		$token = Request::input('token');
		if (User::where('username','=',$username)->count() > 0)
			return $this->pack("用户名已存在",0);
		if (User::where('wx_token','=',$token)->count() > 0)
			return $this->pack("微信号已注册",0);
		$user = new User;
		$user->username = $username;
		$user->img = $img;
		$user->wx_token = $token;
		$user->group_id = 1;
		$user->rand_id = $this->getRandId();
		$user->save();

		return $this->pack("注册成功",1,[
			'username' => $user->username,
			'phone' => $user->phone,
			'img' => $user->img,
			'desc' => $user->desci,
			'randId' => $user->rand_id,
			'groupName' => UserGroup::where('id','=',$user->group_id)->select('name')->first()->name
		]);
	}

	// 微信用户检测
	public function anyWxlog()
	{
		$token = Request::input('token');
		if (User::where('wx_token','=',$token)->count() < 1)
			return $this->pack("用户未注册",0);
		$user = User::where('wx_token','=',$token)->first();
		return $this->pack("微信登陆成功",1,[
			'username' => $user->username,
			'phone' => $user->phone,
			'img' => $user->img,
			'desc' => $user->desci,
			'randId' => $user->rand_id,
			'groupName' => UserGroup::where('id','=',$user->group_id)->select('name')->first()->name
		]);
	}

	//用户收藏
		// 获取收藏
	private function anyGetusercollects()
	{
		$id = Request::input('randId');
		$pageSize = Request::input('pageSize');
		$cols = UserCollect::where('rand_id','=',$id)->paginate($pageSize);
		$out = array();
		foreach ($cols as $col) {
			if ($col->type == 0) {
				$con = Content::where('id','=',$col->parent_id)->first();
				$out[] = [
					'id' => $col->id,
					'parent_id' => $con->id,
					'title' => $con->title,
					'img' => $this->getImg($con->img),
					'type' => 0
				];
			} else {
				$com = UserComment::where('id','=',$col->parent_id)->first();
				$out[] = [
					'id' => $col->id,
					'parent_id' => $com->id,
					'title' => $com->body,
					'img' => $this->getImg($com->img),
					'type' => 1
				];
			}
		}

		return $this->pack("获取成功",1,$out);
	}

		// 新增收藏
	private function anyAddusercollect()
	{
		$id = Request::input('randId');
		$parentId = Request::input('parentId');
		$type = Request::input('type');
		$col = new UserCollect;
		$col->user_id = $id;
		$col->parent_id = $parentId;
		$col->type = $type;
		$col->save();
		return $this->pack("新增成功");
	}

		// 删除收藏
	private function anyDelusercollect()
	{
		$id = Request::input('id');
		UserCollect::where('id','=',$id)->delete();
		return $this->pack("删除成功");
	}

	private function getRandId()
	{
		$id = $this->randId();
		while (User::where('rand_id','=',$id)->count() > 0)
			$id = $this->randId();
		return $id;
	}
	
	private function saveImg($randId,$code)
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
	
	private function getImg($url)
	{
		return "http://news.tuike520.com/phpThumb/phpThumb.php?src=/$url&w=120&q=30";
	}

	private function randId()
	{
		$str = rand(1,9);
		for ($i = 0;$i < 5;$i++) {
			$str = $str . rand(0,9);
		}
		return $str;
	}
	private function pack($info,$code = 1,$data = null)
	{
		return response()->json([
			'code' => $code,
			'info' => $info,
			'data' => $data
		]);
	}

	public function getStartup()
	{
		$tmp = Ad::where('is_start','=',1)->first();
		$tmp->img = 'http://112.74.126.199:8023/' . $tmp->img;
		$file = file_get_contents(__DIR__ . '/version');
		$v = explode('|',$file);
		$out = array(
			'img' => $tmp->img,
			'link' => $tmp->link,
			'version' => $v[0],
			'down' => $v[1]
		);
		return response()->json($out);
	}

	public function getAds()
	{
		$cnt = Ad::where('type','=',0)->count();
		// $n = rand(0,$cnt - 1);
		// $tmp = Ad::skip($n)->take(1)->first();
		// while ($tmp->is_start == 1) {
		// 	$n = rand(0,$cnt - 1);
		// 	$tmp = Ad::skip($n)->take(1)->first();
		// }
		if ($cnt < 2)
			return response()->json([]);
		$tmp = DB::select(" select * from ads where is_start = '0' and type = '0' order by rand() limit 1 ");
		return response()->json($tmp[0]);
	}

	public function getVersion()
	{
		$file = file_get_contents(__DIR__ . '/ios');
		$v = explode('|',$file);
		$out = array(
			'version' => $v[0],
			'link' => $v[1]
		);
		return response()->json($out);
	}

	public function getDowns()
	{
		$type = Request::input('type');
		$sql = Downlog::where('created_date','=',date("Y-m-d"));
		if ($sql->count() == 0) {
			$log = new Downlog;
			$log->created_date = date("Y-m-d");
			$log->android = 0;
			$log->ios = 0;
		} else {
			$log = $sql->first();
		}
		if ($type == 0) {
			$log->ios++;
			$file = file_get_contents(__DIR__ . '/ios');
			$v = explode('|',$file);
			$tmp = intval($v[2]);
			$tmp++;
			file_put_contents(__DIR__ . '/ios', $v[0] . '|' . $v[1] . '|' . $tmp);
			echo $log->ios;
		} else {
			$log->android++;
			$file = file_get_contents(__DIR__ . '/version');
			$v = explode('|',$file);
			$tmp = intval($v[2]);
			$tmp++;
			file_put_contents(__DIR__ . '/version', $v[0] . '|' . $v[1] . '|' . $tmp);
			echo $log->ios;
		}
		$log->save();
	}
}

?>