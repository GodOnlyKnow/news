<?php namespace App\Http\Controllers;

use App\UserComment;
use App\UserCommentReply;

class CommentController extends Controller {
	
	public function getIndex() {
		$cms = UserComment::orderBy('created_at','desc')->paginate(15);
		return view('comment')->withCms($cms);
	}
}

?>