<?php namespace App\Http\Controllers\API;

use Request;
use App\User;
use App\UserComment;
use App\UserCommentReply;
use App\UserCommentGood;
use App\UserCollect;

class UserCommentController extends RestController {

	public function anyGet()
	{
		$pageSize = Request::input('pageSize');
		$out = array();
		if (Request::has('randId')) {
			$id = Request::input('randId');
			$cols = UserComment::join('users','users.rand_id','=','user_comments.user_id')
							->where('user_comments.user_id','=',$id)
							->orderBy('user_comments.created_at','desc')
							->select(array(
								'user_comments.id as id',
								'users.username as userName',
								'users.img as userImg',
								'users.rand_id as randId',
								'user_comments.body as body',
								'user_comments.img as img',
								'user_comments.parised as parised',
								'user_comments.created_at as createdAt'))
							->paginate($pageSize);
		} else {
			$cols = UserComment::join('users','users.rand_id','=','user_comments.user_id')
							->orderBy('user_comments.is_top','desc')
							->orderBy('user_comments.created_at','desc')
							->select(array(
								'user_comments.id as id',
								'users.username as userName',
								'users.img as userImg',
								'users.rand_id as randId',
								'user_comments.body as body',
								'user_comments.img as img',
								'user_comments.parised as parised',
								'user_comments.created_at as createdAt',
								'user_comments.is_top as isTop'))
							->paginate($pageSize);
		}
		//return $this->pack("s",1,$cols['lastPage']);
		//return var_dump($cols->lastPage());
		foreach ($cols as $col) {
			$out[] = [
				'id' => $col->id,
				'userName' => $col->userName,
				'userImg' => ($col->userImg),
				'randId' => $col->randId,
				'body' => $col->body,
				'img' => $this->getImg($col->img),
				'isTop' => $col->isTop,
				'replys' => UserCommentReply::where('parent_id','=',$col->id)->count(),
				'parised' => $col->parised,
				'createdAt' => strtotime($col->createdAt)
			];
		}

		return $this->pack("获取成功",1,[
			'result' => $out,
			'last' => $cols->lastPage()
		]);
	}

	public function anyCreate() 
	{
		$id = Request::input('randId');
		if (!$this->checkCount($id))
			return $this->pack("已达到今日发布动态上限~~",0);
		$com = new UserComment;
		$com->user_id = $id;
		$com->body = Request::input('body');
		$com->img = $this->saveImg($id,Request::input('code'));
		$com->parised = 0;
		$com->shared = 0;
		$com->is_top = 0;
		$com->save();
		
		$this->addPoints($id,5);
		
		return $this->pack("发布成功");
	}
	
	public function anyDelete()
	{
		$id = Request::input('id');
		$type = Request::input('type');
		if ($type == 0) {
			UserCollect::where('parent_id','=',$id)->where('type','=',1)->delete();
			UserCommentReply::where('parent_id','=',$id)->delete();
			UserComment::where('id','=',$id)->delete();
		} else {
			UserCommentReply::where('id','=',$id)->delete();
		}
		
		return $this->pack("Done");
	}

	public function anyDetail()
	{
		$id = Request::input('id');
		$res = UserCommentReply::where('parent_id','=',$id)->orderBy('created_at','desc')->get();
		$out = array();
		foreach ($res as $r) {
			$user1 = User::where('rand_id','=',$r->user1_id)->first();
			$user2 = User::where('rand_id','=',$r->user2_id)->first();
			$out[] = [
				'id' => $r->id,
				'userFromId' => $user1->rand_id,
				'userToId' => $user2->rand_id,
				'userFrom' => $user1->username,
				'userTo' => $user2->username,
				'userFromImg' => ($user1->img),
				'userToImg' => ($user2->img),
				'body' => $r->body,
				'createdAt' => strtotime($r->created_at)
			];
		}

		return $this->pack("获取成功",1,[
			'result' => $out,
			'last' => 1
		]);
	}

	public function anyReply()
	{
		$reply = new UserCommentReply;
		if (!$this->checkCount(Request::input('userFromId')))
			return $this->pack("已达到今日评论上限~~",0);
		$reply->body = Request::input('body');
		$reply->user1_id = Request::input('userFromId');
		$reply->user2_id = Request::input('userToId');
		$reply->parent_id = Request::input('id');
		$reply->save();
		
		$this->addPoints(Request::input('userFromId'),1);
		
		return $this->pack("回复成功");
	}

	public function anyParised()
	{
		$userId = Request::input('randId');
		$id = Request::input('id');
		if (UserCommentGood::where('user_id','=',$userId)->where('parent_id','=',$id)->count() > 0)
			return $this->pack("已经赞过了",0);
			
		$com = UserComment::where('id','=',Request::input('id'))->first();
		$com->parised++;
		$com->save();
		
		$good = new UserCommentGood;
		$good->user_id = $userId;
		$good->parent_id = $id;
		$good->save();
		
		return $this->pack("操作成功");
	}

	public function anyShare()
	{
		$com = UserComment::where('id','=',Request::input('id'))->first();
		$com->shared++;
		$com->save();

		return $this->pack("分享成功");
	}
	
	public function anyAll()
	{
		$id = Request::input('id');
		$col = UserComment::join('users','users.rand_id','=','user_comments.user_id')
							->where('user_comments.id','=',$id)
							->orderBy('user_comments.is_top','desc')
							->orderBy('user_comments.created_at','desc')
							->select(array(
								'user_comments.id as id',
								'users.username as userName',
								'users.img as userImg',
								'users.rand_id as randId',
								'user_comments.body as body',
								'user_comments.img as img',
								'user_comments.parised as parised',
								'user_comments.created_at as createdAt',
								'user_comments.is_top as isTop'))
							->first();
		$res = UserCommentReply::where('parent_id','=',$id)->orderBy('created_at','desc')->get();
		$out = array();
		foreach ($res as $r) {
			$user1 = User::where('rand_id','=',$r->user1_id)->first();
			$user2 = User::where('rand_id','=',$r->user2_id)->first();
			$out[] = [
				'id' => $r->id,
				'userFromId' => $user1->rand_id,
				'userToId' => $user2->rand_id,
				'userFrom' => $user1->username,
				'userTo' => $user2->username,
				'userFromImg' => ($user1->img),
				'userToImg' => ($user2->img),
				'body' => $r->body,
				'createdAt' => strtotime($r->created_at)
			];
		}
		
		return $this->pack("获取成功",1,[
			'id' => $col->id,
			'userName' => $col->userName,
			'userImg' => ($col->userImg),
			'randId' => $col->randId,
			'body' => $col->body,
			'img' => $this->getImg($col->img),
			'isTop' => $col->isTop,
			'replys' => UserCommentReply::where('parent_id','=',$col->id)->count(),
			'parised' => $col->parised,
			'createdAt' => strtotime($col->createdAt),
			'data' => $out
		]);
	}
}

?>