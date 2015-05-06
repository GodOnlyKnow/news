<?php namespace App\Http\Controllers\API;

use App\ContentComment;
use App\ContentCommentReply;
use App\ContentCommentGood;
use App\User;
use App\Content;
use Request;
	
class ContentCommentController extends RestController {
		
	public function anyGet()
	{
		$id = Request::input('id');
		$pageSize = Request::input('pageSize');
		$cms = ContentComment::
						join('users','users.rand_id','=','content_comments.user_id')
						->where('content_comments.content_id','=',$id)
						->orderBy('content_comments.created_at','desc')
						->select(array(
										'users.username as userName',
										'users.img as userImg',
										'users.rand_id as randId',
										'content_comments.id as id',
										'content_comments.body as body',
										'content_comments.parised as parised',
										'content_comments.created_at as createdAt'))
						->paginate($pageSize);
		$out = array();
		foreach ($cms as $cm) {
			$out[] = [
				'id' => $cm->id,
				'userName' => $cm->userName,
				'userImg' => ($cm->userImg),
				'randId' => $cm->randId,
				'body' => $cm->body,
				'replys' => ContentCommentReply::where('comment_id','=',$cm->id)->count(), 
				'parised' => $cm->parised,
				'createdAt' => strtotime($cm->createdAt)
			];
		} 
		
		return $this->pack("获取成功",1,[
			'result' => $out,
			'last' => $cms->lastPage(),
			'goods' => Content::where('id','=',$id)->first()->parised
		]);
	}
	
	public function anyCreate() 
	{
		$id = Request::input('randId');
		if (!$this->checkCount($id))
			return $this->pack("已达到今日评论上限~~",0);
		$contentId = Request::input('contentId');
		$com = new ContentComment;
		$com->user_id = $id;
		$com->body = Request::input('body');
		//$com->img = $this->saveImg($id,Request::input('code'));
		$com->parised = 0;
		$com->shared = 0;
		$com->content_id = $contentId;
		$com->save();
		
		$this->addPoints($id,1);
		
		return $this->pack("发布成功");
	}
	
	public function anyDetail()
	{
		$id = Request::input('id');
		$res = ContentCommentReply::where('comment_id','=',$id)->orderBy('created_at','desc')->get();
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
	
	public function anyDelete()
	{
		$id = Request::input('id');
		$type = Request::input('type');
		if ($type == 0) {
			ContentCommentReply::where('comment_id','=',$id)->delete();
			ContentComment::where('id','=',$id)->delete();
		} else {
			ContentCommentReply::where('id','=',$id)->delete();
		}
		
		return $this->pack("Done");
	}

	public function anyReply()
	{
		if (!$this->checkCount(Request::input('userFromId')))
			return $this->pack("已达到今日评论上限~~",0);
		$reply = new ContentCommentReply; 
		$reply->body = Request::input('body');
		$reply->user1_id = Request::input('userFromId');
		$reply->user2_id = Request::input('userToId');
		$reply->comment_id = Request::input('id');
		$reply->save();
		
		$this->addPoints(Request::input('userFromId'),1);
		
		return $this->pack("回复成功");
	}

	public function anyParised()
	{
		$userId = Request::input('randId');
		$id = Request::input('id');
		if (ContentCommentGood::where('user_id','=',$userId)->where('parent_id','=',$id)->count() > 0)
			return $this->pack("已经赞过了",0);
			
		$com = Content::where('id','=',Request::input('id'))->first();
		$com->parised++;
		$com->save();
		
		$good = new ContentCommentGood;
		$good->user_id = $userId;
		$good->parent_id = $id;
		$good->save();
		
		return $this->pack("操作成功");
	}

	public function anyShare()
	{
		$com = Content::where('id','=',Request::input('id'))->first();
		$com->shared++;
		$com->save();

		return $this->pack("分享成功");
	}
	
}
?>