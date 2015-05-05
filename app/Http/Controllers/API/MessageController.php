<?php namespace App\Http\Controllers\API;

use App\Message;
use Request;

class MessageController extends RestController {
	
	public function anyGet()
	{
		$mes = Message::orderBy('created_at','desc')->paginate(Request::input('pageSize'));
		$out = array();
		foreach ($mes as $m) {
			$out[] = [
				'title' => $m->name,
				'body' => $m->body,
				'createdAt' => strtotime($m->created_at)
			];
		}
		
		return $this->pack("获取成功",1,[
			'result' => $out,
			'last' => $mes->lastPage()
		]);
	}
}

?>