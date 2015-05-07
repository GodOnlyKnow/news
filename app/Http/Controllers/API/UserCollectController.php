<?php namespace App\Http\Controllers\API;

use App\UserCollect;
use App\Content;
use App\UserComment;
use Request;

class UserCollectController extends RestController {

	public function anyGet()
	{
		$id = Request::input('randId');
		$pageSize = Request::input('pageSize');
		$cols = UserCollect::where('user_id','=',$id)->orderBy('created_at','desc')->paginate($pageSize);
		$out = array();
		foreach ($cols as $col) {
			if ($col->type == 0) {
				$con = Content::where('id','=',$col->parent_id)->first();
				$out[] = [
					'id' => $col->id,
					'parentId' => $con->id,
					'title' => $con->title,
					'img' => $this->getImg($con->img),
					'type' => 0
				];
			} else {
				$com = UserComment::where('id','=',$col->parent_id)->first();
				$out[] = [
					'id' => $col->id,
					'parentId' => $com->id,
					'title' => $com->body,
					'img' => $this->getImg($com->img),
					'type' => 1
				];
			}
		}

		return $this->pack("获取成功",1,[
			'result' => $out,
			'last' => $cols->lastPage()
		]);
	}

	public function anyCreate() 
	{
		$id = Request::input('randId');
		$parentId = Request::input('parentId');
		$type = Request::input('type');
		if (UserCollect::where('user_id','=',$id)->where('parent_id','=',$parentId)->where('type','=',$type)->count() > 0)
			return $this->pack("已经收藏过了~~",0);
		$col = new UserCollect;
		$col->user_id = $id;
		$col->parent_id = $parentId;
		$col->type = $type;
		$col->save();
		return $this->pack("新增成功");
	}

	public function anyDelete()
	{
		$id = Request::input('id');
		UserCollect::where('id','=',$id)->delete();
		return $this->pack("删除成功");
	}
}

?>
