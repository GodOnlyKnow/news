<?php namespace App\Http\Controllers;

use App\User;
use App\UserGroup;
use Request;

class UserController extends Controller {
	
	public function getIndex()
	{
		$username = '%';
		$page = 1;
		if (Request::has('username'))
			$username = Request::input('username');
		if (Request::has('page'))
			$page = Request::input('page');
		$users = User::where('username','like',"%$username%")->paginate(15);
		$groups = UserGroup::get();
		return view('users')->withUsers($users)->withUsername($username)->withPage($page)->withGroups($groups);
	}
	
	public function getChange()
	{
		$user = User::where('id','=',Request::input('id'))->first();
		$user->is_lock = $user->is_lock == 0 ? 1 : 0;
		$user->save();
		$username = Request::input('username');
		$page = Request::input('page');
		return redirect("user/index?username=$username&page=$page");
	}
}

?>