<?php namespace App\Http\Controllers\API;

use App\Feedback;
use Request;

class FeedbackController extends RestController {
	
	public function anyCreate()
	{
		$feed = new Feedback;
		$feed->body = Request::input('body');
		$feed->img = $this->saveImg(Request::input('code'));
		$feed->concat = Request::input('con');
		$feed->save();
		
		return $this->pack("提交成功");
	}
}

?>