<?php

class Gt {
	public $appKey;
	public $appId;
	public $masterSecret;
	public $host = 'http://sdk.open.api.igexin.com/apiex.htm';

	public function mtp($title,$text,$content) {
		$tp = new IGtNotificationTemplate();
		$tp->set_appId($this->appId);
		$tp->set_appKey($this->appKey);
		$tp->set_transmissionType(1);
		$tp->set_transmissionContent($content);
		$tp->set_title($title);
		$tp->set_text($text);
		$tp->set_logo("http://wwww.igetui.com/logo.png");
		$tp->set_isRing(true);//是否响铃
	    $tp->set_isVibrate(true);//是否震动
	    $tp->set_isClearable(true);//通知栏是否可清除
	    return $tp;
	}
}

class Android extends Gt {
	public $appId = 'tARzw1YJvO89CcIVV6SNG1';
	public $appKey = 'wLrAb9Ysn99D1pEKhWgPc4';
	public $masterSecret = 'LKAgOpMlXn7SVaA5XmZ043';

	public function Push($text,$title,$content,$time = 0) {
		$igt = new IGeTui($this->host,$this->appKey,$this->masterSecret);
		$tp = $this->mtp($title,$text,$content);
		$message = new IGtAppMessage();
		$message->set_isOffline(true);
		$message->set_offlineExpireTime($time);
		$message->set_data($tp);
		$message->set_PushNetWorkType(0);
		$message->set_appIdList(array($this->appId));
		$rep = $igt->pushMessageToApp($message);
		return $rep;
	}
}

class IOS extends Gt {
	public $appId = 'tARzw1YJvO89CcIVV6SNG1';
	public $appKey = 'wLrAb9Ysn99D1pEKhWgPc4';
	public $masterSecret = 'LKAgOpMlXn7SVaA5XmZ043';

	public function Push($text,$title,$content,$time = 0) {
		$igt = new IGeTui($this->host,$this->appKey,$this->masterSecret);
		$tp = $this->mtp($title,$text,$content);
		$tp->set_pushInfo($title,1,$text,"","","","","");
		$message = new IGtAppMessage();
		$message->set_isOffline(true);
		$message->set_offlineExpireTime($time);
		$message->set_data($tp);
		$message->set_PushNetWorkType(0);
		$message->set_appIdList(array($this->appId));
		$rep = $igt->pushMessageToApp($message);
		return $rep;
	}
}
?>