<?php namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;

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
		if ($code == null || strlen($code) < 1) return '';
		
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
}

?>