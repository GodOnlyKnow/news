<?php namespace App\Http\Controllers\API;

use App\Ad;

class AdsController extends RestController {
	
	public function anyGet()
	{
		$ads = Ad::where('type','=',1)->orderBy('created_at','desc')->get();
		$out = array();
		foreach ($ads as $ad) {
			$out[] = [
				'link' => $ad->link,
				'img' => $this->getImg($ad->img)
			];
		}
		
		return $this->pack("获取成功",1,$out);
	}
}

?>