<?php namespace App\Http\Controllers\API;

use Request;
use App\User;
use App\UserComment;
use App\UserCommentReply;
use App\UserCommentGood;

class UserCommentController extends RestController {

	public function anyGet()
	{
		$pageSize = Request::input('pageSize');
		$out = array();
		if (Request::has('randId')) {
			$id = Request::input('randId');
			$cols = UserComment::join('users','users.rand_id','=','user_comments.user_id')
							->where('users.user_id','=',$id)
							->orderBy('user_comments.created_at','desc')
							->select(array(
								'user_comments.id as id',
								'users.username as userName',
								'users.img as userImg',
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
		$com = new UserComment;
		$com->user_id = $id;
		$com->body = Request::input('body');
		$com->img = $this->saveImg($id,Request::input('code'));
		$com->parised = 0;
		$com->shared = 0;
		$com->is_top = 0;
		$com->save();

		return $this->pack("发布成功");
	}
	
	public function anyDelete()
	{
		$id = Request::input('id');
		$type = Request::input('type');
		if ($type == 0) {
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
		$res = UserCommentReply::where('parent_id','=',$id)->get();
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
				'userFromImg' => $this->getImg($user1->img),
				'userToImg' => $this->getImg($user2->img),
				'body' => $r->body,
				'createdAt' => strtotime($r->created_at)
			];
		}

		return $this->pack("获取成功",1,$out);
	}

	public function anyReply()
	{
		$reply = new UserCommentReply; 
		$reply->body = Request::input('body');
		$reply->user1_id = Request::input('userFromId');
		$reply->user2_id = Request::input('userToId');
		$reply->parent_id = Request::input('id');
		$reply->save();

		return $this->pack("回复成功");
	}

	public function anyParised()
	{
		$com = UserComment::where('id','=',Request::input('id'))->first();
		$com->parised++;
		$com->save();
		
		$userId = Request::input('randId');
		$id = Request::input('id');
		if (UserCommentGood::where('user_id','=',$userId)->where('parent_id','=',$id)->count() > 0)
			return $this->pack("已经赞过了",0);
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
}

?>